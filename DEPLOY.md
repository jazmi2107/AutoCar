# Deploying AutoCar to Vercel

This guide explains how to deploy the AutoCar application to Vercel.

## Prerequisites

1.  A Vercel account.
2.  This repository pushed to GitHub/GitLab/Bitbucket.

## Configuration

The project is configured to use **Firebase** for User Authentication and Profile Data.
However, other data (Assistance Requests, Mechanic Details, etc.) currently relies on a **SQL Database**.

**Important Note:**
This deployment is configured to use **SQLite (In-Memory)** for the SQL database (`DB_CONNECTION=sqlite`).
This means:
-   **User Accounts & Login** will work perfectly and persist (saved in Firebase).
-   **Assistance Requests & Job History** will be **reset** every time the server restarts or re-deploys (saved in temporary memory).
-   For a production app, you must connect a real database (like MySQL on PlanetScale, Supabase, or AWS RDS) or migrate all data to Firebase.

## Steps to Deploy

1.  **Push your code to GitHub**:
    ```bash
    git add .
    git commit -m "Prepare for Vercel deployment"
    git push origin main
    ```

2.  **Import Project in Vercel**:
    -   Go to [Vercel Dashboard](https://vercel.com/dashboard).
    -   Click "Add New..." -> "Project".
    -   Import your `AutoCar` repository.

3.  **Environment Variables**:
    In the Vercel Project Settings, add the following Environment Variables.
    *You can find the values in your local `.env` file.*

    | Key | Value |
    | --- | --- |
    | `APP_KEY` | (Copy from `.env` or generate a new one) |
    | `APP_URL` | Your Vercel domain (e.g., `https://your-app.vercel.app`) |
    | `FIREBASE_PROJECT` | `app` |
    | `FIREBASE_CREDENTIALS` | **The entire JSON content** of your service account file. |
    | `FIREBASE_API_KEY` | Your Firebase Web API Key (found in Firebase Project Settings). |
    | `FIREBASE_DATABASE_URL` | Your Firebase Realtime Database URL. |

### **Important: FIREBASE_CREDENTIALS**
Since Vercel is a serverless environment, you cannot upload the `.json` file. Instead, you must copy the **text content** of your service account file and paste it into the Vercel Environment Variables.

**Steps:**
1. Open `storage/app/firebase/firebase_credentials.json`.
2. Copy everything inside the file.
3. Go to Vercel Dashboard -> Settings -> Environment Variables.
4. Add `FIREBASE_CREDENTIALS` and paste the JSON content as the value.
5. Save and redeploy.

4.  **Deploy**:
    -   Click "Deploy".
    -   Vercel will build and deploy your Laravel application.

## Troubleshooting

-   **500 Server Error**: Check Vercel Function Logs. usually missing environment variables.
-   **Database Error**: Remember that tables are created in memory. If you need persistent SQL data, change `DB_CONNECTION` to a remote MySQL server.
