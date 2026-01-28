<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::factory()
            ->withApiKey(config('services.openai.key'))
            ->withBaseUri(config('services.openai.url'))
            ->withHttpHeader('HTTP-Referer', config('app.url'))
            ->withHttpHeader('X-Title', config('app.name'))
            ->make();
    }

    /**
     * Get AI recommendation for the best mechanic based on multiple factors
     *
     * @param array $mechanics Array of mechanics with their details
     * @param array $requestContext Context about the breakdown (type, location, urgency)
     * @return array Mechanics with AI recommendation scores
     */
    public function recommendMechanic(array $mechanics, array $requestContext = [])
    {
        if (empty($mechanics)) {
            return [];
        }

        // Prepare mechanics data for AI analysis
        $mechanicsData = array_map(function($mechanic) {
            return [
                'id' => $mechanic['id'],
                'name' => $mechanic['user']['name'] ?? 'Unknown',
                'experience_years' => $mechanic['years_of_experience'],
                'rating' => $mechanic['rating'],
                'distance_km' => $mechanic['distance'] ?? 999,
                'availability' => $mechanic['availability_status'] ?? 'available',
            ];
        }, $mechanics);

        $breakdownType = $requestContext['breakdown_type'] ?? 'general';
        $urgency = $requestContext['urgency'] ?? 'normal';

        $prompt = $this->buildPrompt($mechanicsData, $breakdownType, $urgency);

        try {
            $response = $this->client->chat()->create([
                'model' => 'openai/gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert automotive service dispatcher with 20 years of experience matching customers with the most suitable mechanics. You analyze mechanic qualifications, ratings, distance, and breakdown urgency to make optimal recommendations.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            $aiResponse = $response->choices[0]->message->content;
            
            // Parse AI response to extract recommendation
            $recommendation = $this->parseAIRecommendation($aiResponse, $mechanics);
            
            // Mark that AI was used successfully
            foreach ($recommendation as &$mech) {
                $mech['ai_used'] = true;
            }
            
            return $recommendation;

        } catch (\OpenAI\Exceptions\ErrorException $e) {
            // OpenAI API specific errors (rate limit, authentication, etc)
            \Log::warning('OpenAI API Error - Using fallback algorithm', [
                'error' => $e->getMessage(),
                'breakdown_type' => $breakdownType,
                'mechanics_count' => count($mechanics)
            ]);
            
            return $this->fallbackRecommendation($mechanics, $breakdownType, 'OpenAI API limit reached or error occurred');
            
        } catch (\Exception $e) {
            // General errors (network, timeout, etc)
            \Log::error('OpenAI Service Error - Using fallback algorithm', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->fallbackRecommendation($mechanics, $breakdownType, 'AI service temporarily unavailable');
        }
    }

    /**
     * Build the prompt for OpenAI
     */
    protected function buildPrompt(array $mechanicsData, string $breakdownType, string $urgency): string
    {
        $mechanicsList = '';
        foreach ($mechanicsData as $index => $mech) {
            $mechanicsList .= sprintf(
                "%d. %s - %d years exp, %.1f★ rating, %.1f km away, %s\n",
                $index + 1,
                $mech['name'],
                $mech['experience_years'],
                $mech['rating'],
                $mech['distance_km'],
                $mech['availability']
            );
        }

        return <<<PROMPT
Analyze these mechanics and recommend the BEST ONE for this breakdown:

BREAKDOWN TYPE: {$breakdownType}
URGENCY: {$urgency}

AVAILABLE MECHANICS:
{$mechanicsList}

Consider these factors:
1. Distance (closer is better, especially for urgent cases)
2. Experience (more years = better technical skills)
3. Rating (customer satisfaction indicator)
4. Breakdown type specialization (some issues need specific expertise)
5. Availability status

Respond in this EXACT format:
RECOMMENDED: [mechanic number]
REASON: [one sentence explaining why this is the best choice]
ALTERNATIVE: [second best mechanic number]

Example:
RECOMMENDED: 1
REASON: Closest to breakdown location with excellent rating and sufficient experience for this type of issue.
ALTERNATIVE: 3
PROMPT;
    }

    /**
     * Parse the AI recommendation response
     */
    protected function parseAIRecommendation(string $aiResponse, array $mechanics): array
    {
        $lines = explode("\n", $aiResponse);
        $recommendedId = null;
        $reason = '';
        $alternativeId = null;

        foreach ($lines as $line) {
            if (preg_match('/RECOMMENDED:\s*(\d+)/', $line, $matches)) {
                $index = (int)$matches[1] - 1;
                if (isset($mechanics[$index])) {
                    $recommendedId = $mechanics[$index]['id'];
                }
            } elseif (preg_match('/REASON:\s*(.+)/', $line, $matches)) {
                $reason = trim($matches[1]);
            } elseif (preg_match('/ALTERNATIVE:\s*(\d+)/', $line, $matches)) {
                $index = (int)$matches[1] - 1;
                if (isset($mechanics[$index])) {
                    $alternativeId = $mechanics[$index]['id'];
                }
            }
        }

        // Mark recommended mechanic
        foreach ($mechanics as &$mechanic) {
            $mechanic['ai_recommended'] = ($mechanic['id'] == $recommendedId);
            $mechanic['ai_alternative'] = ($mechanic['id'] == $alternativeId);
            $mechanic['ai_reason'] = $mechanic['ai_recommended'] ? $reason : null;
        }

        // Sort: recommended first, then by basic score
        usort($mechanics, function($a, $b) {
            if ($a['ai_recommended']) return -1;
            if ($b['ai_recommended']) return 1;
            if ($a['ai_alternative']) return -1;
            if ($b['ai_alternative']) return 1;
            
            // Fallback to basic score
            $scoreA = ($a['years_of_experience'] * 2) + ($a['rating'] * 20) + max(0, 100 - ($a['distance'] ?? 999));
            $scoreB = ($b['years_of_experience'] * 2) + ($b['rating'] * 20) + max(0, 100 - ($b['distance'] ?? 999));
            return $scoreB <=> $scoreA;
        });

        return $mechanics;
    }

    /**
     * Fallback recommendation when AI fails
     * Uses intelligent scoring algorithm based on multiple factors
     */
    protected function fallbackRecommendation(array $mechanics, string $breakdownType = 'general', string $fallbackReason = ''): array
    {
        foreach ($mechanics as &$mechanic) {
            // Base scoring factors
            $expScore = $mechanic['years_of_experience'] * 2;
            $ratingScore = $mechanic['rating'] * 20;
            $distanceScore = isset($mechanic['distance']) && $mechanic['distance'] < 999 
                ? max(0, 100 - $mechanic['distance']) 
                : 0;
            
            // Bonus for high ratings
            $ratingBonus = $mechanic['rating'] >= 4.5 ? 10 : 0;
            
            // Bonus for experienced mechanics on complex issues
            $complexIssues = ['Engine Problem', 'Transmission Problem', 'Accident'];
            $expBonus = (in_array($breakdownType, $complexIssues) && $mechanic['years_of_experience'] >= 5) ? 15 : 0;
            
            // Distance penalty for far mechanics
            $distancePenalty = isset($mechanic['distance']) && $mechanic['distance'] > 20 ? -10 : 0;
            
            $mechanic['score'] = $expScore + $ratingScore + $distanceScore + $ratingBonus + $expBonus + $distancePenalty;
            $mechanic['ai_recommended'] = false;
            $mechanic['ai_alternative'] = false;
            $mechanic['ai_used'] = false;
            $mechanic['fallback_used'] = true;
        }

        // Sort by score
        usort($mechanics, fn($a, $b) => $b['score'] <=> $a['score']);
        
        // Mark top 2 mechanics
        if (!empty($mechanics)) {
            $mechanics[0]['ai_recommended'] = true;
            
            // Generate intelligent reason based on mechanic's strengths
            $topMech = $mechanics[0];
            $reasons = [];
            
            if ($topMech['rating'] >= 4.5) {
                $reasons[] = 'excellent customer rating (' . number_format($topMech['rating'], 1) . '★)';
            }
            if ($topMech['years_of_experience'] >= 5) {
                $reasons[] = $topMech['years_of_experience'] . ' years of experience';
            }
            if (isset($topMech['distance']) && $topMech['distance'] < 10) {
                $reasons[] = 'closest to your location (' . number_format($topMech['distance'], 1) . ' km)';
            } elseif (isset($topMech['distance']) && $topMech['distance'] < 999) {
                $reasons[] = 'nearby location (' . number_format($topMech['distance'], 1) . ' km)';
            }
            
            $mechanics[0]['ai_reason'] = !empty($reasons) 
                ? 'Recommended based on ' . implode(', ', $reasons) 
                : 'Best overall combination of experience, rating, and proximity';
            
           
            
            // Mark second best as alternative
            if (isset($mechanics[1])) {
                $mechanics[1]['ai_alternative'] = true;
            }
        }

        return $mechanics;
    }
}
