# BRM Deployment Guide

This guide walks through deploying the Barangay Records Management (BRM) system to Railway.

## Prerequisites

- Git repository initialized (✅ Done)
- GitHub account (required for Railway authentication)
- Railway account (free tier available)

## Deployment Steps

### 1. Push to GitHub

Create a new repository on GitHub and push the code:

```bash
git remote add origin https://github.com/YOUR_USERNAME/brm.git
git branch -M main
git push -u origin main
```

Replace `YOUR_USERNAME` with your GitHub username.

### 2. Connect to Railway

1. Go to https://railway.app
2. Click "New Project"
3. Select "Deploy from GitHub"
4. Authorize Railway to access your GitHub account
5. Select the `brm` repository
6. Railway will automatically detect the Dockerfile and deploy

### 3. Configure Environment Variables

In the Railway dashboard:

1. Go to your project
2. Add MySQL plugin:
   - Click "Add" → Select "MySQL"
   - Railway will create a MySQL database automatically
3. Set environment variables in the Web service:
   - `DB_HOST` - Set to MySQL service hostname (Railway provides this)
   - `DB_USER` - MySQL username (default: `root`)
   - `DB_PASS` - MySQL password (Railway generates this)
   - `DB_NAME` - `barangay_records`

### 4. Database Setup

Railway will automatically:
1. Create the MySQL service
2. Set up environment variables for connection

To import the schema:
1. Get MySQL connection details from Railway dashboard
2. Import `db/database.sql`:
   ```bash
   mysql -h <hostname> -u <username> -p<password> barangay_records < db/database.sql
   ```

Or use phpMyAdmin once the app is deployed.

### 5. Deploy

1. Railway will automatically deploy when you push to GitHub (if you've connected the repo)
2. Monitor deployment logs in Railway dashboard
3. Once deployed, visit your app at the provided Railway URL

## Troubleshooting

- **Build fails**: Check railway.json and Dockerfile are valid
- **Database connection fails**: Verify environment variables match Railway's MySQL credentials
- **Port issues**: Railway automatically assigns PORT; ensure Apache listens on it

## Local Development

To run locally:

```bash
php -S localhost:8000
```

Then access at http://localhost:8000

## Files Included

- `Dockerfile` - Docker image configuration
- `Procfile` - Process configuration
- `package.json` - Node/PHP version config
- `railway.json` - Railway deployment manifest
