# Google OAuth Configuration for Different Environments

## For Localhost Development
GOOGLE_REDIRECT_URI=http://localhost:8001/auth/google/callback

## For Hosted Production (Replace with your actual domain)
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback

## For XAMPP Local Testing
GOOGLE_REDIRECT_URI=http://localhost:8002/auth/google/callback

## Current Issue Fix Steps:

### 1. Identify Your Current URL
- Check what URL you're accessing the site from
- Example: https://cleancity.yourhost.com

### 2. Update Google Console
Go to: https://console.developers.google.com
1. Select your Clean City project
2. Go to "Credentials" → "OAuth 2.0 Client IDs"
3. Click your Client ID
4. In "Authorized redirect URIs", add:
   - https://yourdomain.com/auth/google/callback
   - http://localhost:8001/auth/google/callback (keep for development)
   - http://localhost:8002/auth/google/callback (for current testing)

### 3. Update Environment Variables
For hosting platform, set:
- GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
- APP_URL=https://yourdomain.com

### 4. For Current Local Testing
Update .env file:
GOOGLE_REDIRECT_URI=http://localhost:8002/auth/google/callback
APP_URL=http://localhost:8002