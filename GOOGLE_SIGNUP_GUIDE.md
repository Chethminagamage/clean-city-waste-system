# Google OAuth Sign-Up Integration Guide

## **Overview**
This guide explains how to implement and configure Google OAuth authentication for the CleanCity application, specifically for resident sign-up and sign-in functionality.

## **🔧 Setup Requirements**

### **1. Google Cloud Console Setup:**
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing project
3. Enable Google+ API and Google OAuth 2.0
4. Go to Credentials > Create Credentials > OAuth 2.0 Client ID
5. Set authorized redirect URI: http://localhost:8000/auth/google/callback
6. Copy Client ID and Client Secret

### **2. Environment Configuration:**
Your .env file should have:
```bash
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### **3. Server Setup:**
Always run on port 8000 for consistency:
```bash
php artisan serve --port=8000
```

## **📋 Implementation Details**

### **Database Schema Updates:**
The system includes two new migrations:

1. **Google OAuth Fields** (`add_google_id_to_users_table.php`):
   - `google_id` (string, nullable, indexed)
   - `avatar` (string, nullable)

2. **Email Verification Fix** (`fix_google_users_email_verification.php`):
   - Automatically verifies email for Google OAuth users

### **Authentication Flow:**

#### **Sign-Up Process:**
1. User clicks "Sign up with Google" → Intent: `signup`
2. Google OAuth handles authentication
3. If email exists → Error: "Account already exists, please sign in"
4. If new email → Creates account with verified email
5. Redirects to dashboard

#### **Sign-In Process:**
1. User clicks "Sign in with Google" → Intent: `signin`
2. Google OAuth handles authentication
3. If email doesn't exist → Error: "No account found, please sign up first"
4. If account exists → Logs in user
5. Redirects to dashboard

### **Error Handling:**
- **Sign-up with existing email**: Shows error on register page
- **Sign-in with non-existent email**: Shows error on login page
- **Invalid Google response**: Redirects to appropriate page with error

## **🔒 Security Features**

### **Authentication Guards:**
The system uses Laravel's multi-guard authentication:
- `web` guard for residents
- `admin` guard for administrators
- `collector` guard for waste collectors

### **Session Management:**
- Intent tracking via `google_auth_intent` session variable
- Automatic email verification for Google users
- Secure redirect handling

### **Data Protection:**
- Google ID is indexed but nullable
- Avatar URLs are stored securely
- No sensitive Google data is stored locally

## **🎯 User Experience**

### **Registration Page (`/register`):**
- "Sign up with Google" button with proper intent
- Error messages displayed if account already exists
- Seamless integration with existing form

### **Login Page (`/login`):**
- "Sign in with Google" button with proper intent
- Error messages displayed if account doesn't exist
- Maintains existing password login functionality

### **Dashboard Access:**
- Google users can access all resident features
- Profile information populated from Google account
- Avatar displayed from Google profile

## **🧪 Testing Checklist**

### **Sign-Up Flow:**
- [ ] New user can sign up with Google
- [ ] Existing email shows appropriate error
- [ ] User redirected to dashboard after successful signup
- [ ] Email is automatically verified

### **Sign-In Flow:**
- [ ] Existing user can sign in with Google
- [ ] Non-existent email shows appropriate error
- [ ] User redirected to dashboard after successful signin
- [ ] Session persists correctly

### **Error Handling:**
- [ ] Errors display on correct pages (register vs login)
- [ ] Error messages are user-friendly
- [ ] Users can retry authentication

## **🚀 Production Deployment**

### **Hostinger Configuration:**
When deploying to Hostinger, update these values:

```bash
# Replace localhost with your actual domain
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
APP_URL=https://yourdomain.com
```

### **Google Cloud Console:**
Update authorized redirect URIs to include:
- `https://yourdomain.com/auth/google/callback`

### **SSL Requirements:**
- Google OAuth requires HTTPS in production
- Ensure SSL certificate is properly configured
- Test redirect URIs after deployment

## **📝 Code Structure**

### **Controllers:**
- `GoogleController.php`: Handles OAuth authentication logic
- `RegisteredUserController.php`: Updated for Google integration

### **Routes:**
```php
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
```

### **Views:**
- `register.blade.php`: Updated with Google sign-up button
- `login.blade.php`: Updated with Google sign-in button

## **🔍 Troubleshooting**

### **Common Issues:**

1. **"Redirect URI mismatch"**
   - Verify URL matches exactly in Google Console
   - Check for trailing slashes or protocol differences

2. **"Invalid client"**
   - Verify Client ID and Secret are correct
   - Check .env file configuration

3. **"Account already exists" but user can't log in**
   - User might have registered with different method
   - Check if email exists in database

4. **"No account found" but user has account**
   - User might not have Google ID linked
   - Check database for google_id field

### **Debug Steps:**
1. Check Laravel logs in `storage/logs/`
2. Verify .env configuration
3. Test with different Google accounts
4. Check Google Cloud Console settings

## **📚 Additional Resources**

- [Laravel Socialite Documentation](https://laravel.com/docs/11.x/socialite)
- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Hostinger Laravel Deployment Guide](https://www.hostinger.com/tutorials/how-to-deploy-laravel-project)

---

*This implementation provides a secure, user-friendly Google OAuth integration that distinguishes between sign-up and sign-in flows while maintaining compatibility with existing authentication methods.*
