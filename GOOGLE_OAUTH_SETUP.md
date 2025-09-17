## 🔧 Google OAuth Setup for Production Hosting

### **Environment Variables for Production:**

Update your production `.env` file with these values:

```env
# For Production (HTTPS)
GOOGLE_CLIENT_ID=YOUR_GOOGLE_CLIENT_ID_HERE
GOOGLE_CLIENT_SECRET=YOUR_GOOGLE_CLIENT_SECRET_HERE
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback

# For Local Development (HTTP)
# GOOGLE_CLIENT_ID=YOUR_LOCAL_CLIENT_ID
# GOOGLE_CLIENT_SECRET=YOUR_LOCAL_CLIENT_SECRET
# GOOGLE_REDIRECT_URI=http://127.0.0.1:8001/auth/google/callback
```

### **Google Cloud Console Settings:**

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services > Credentials**
3. Edit your OAuth 2.0 Client ID
4. Add these **Authorized redirect URIs**:
   ```
   https://yourdomain.com/auth/google/callback
   http://127.0.0.1:8001/auth/google/callback
   http://localhost:8001/auth/google/callback
   ```

### **DNS Issues Fix for Local Development:**

If you still get DNS errors locally, add this to your `/etc/hosts` file:
```
74.125.200.95 www.googleapis.com
142.250.191.106 accounts.google.com
```

### **Production Checklist:**
- ✅ HTTPS enabled on production domain
- ✅ Google OAuth credentials configured with production domain
- ✅ Environment variables updated for production
- ✅ Laravel caches cleared after deployment