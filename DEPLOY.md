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
    | `FIREBASE_DATABASE_URL` | `https://autocar-9a1a7-default-rtdb.asia-southeast1.firebasedatabase.app/` |
    | `FIREBASE_API_KEY` | (Copy from `.env`) |
    | `FIREBASE_CREDENTIALS` | (Copy the **content** of your `firebase_credentials.json` file here) |

4.  **Deploy**:
    -   Click "Deploy".
    -   Vercel will build and deploy your Laravel application.

## Troubleshooting

-   **500 Server Error**: Check Vercel Function Logs. usually missing environment variables.
-   **Database Error**: Remember that tables are created in memory. If you need persistent SQL data, change `DB_CONNECTION` to a remote MySQL server.
