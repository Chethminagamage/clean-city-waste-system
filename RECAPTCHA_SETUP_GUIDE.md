# 🔐 Google reCAPTCHA Setup Guide

## 📋 **Step 1: Get Google reCAPTCHA Keys**

### **Visit Google reCAPTCHA Admin Console:**
🔗 **https://www.google.com/recaptcha/admin/create**

### **Create New Site:**

**1. Site Configuration:**
- **Label**: `Clean City Waste Management - Local Dev`
- **reCAPTCHA Type**: ✅ **reCAPTCHA v2** → "I'm not a robot" Checkbox
- **Domains**: Add these localhost domains (NO http:// or ports needed):
  ```
  localhost
  127.0.0.1
  ```
  
**⚠️ Important:** 
- Don't include `http://` or `https://`
- Don't include port numbers like `:8001`
- Just use `localhost` and `127.0.0.1`
  
**💡 Pro Tip:** Google reCAPTCHA works perfectly with localhost for development! You can add multiple localhost ports.

**2. Advanced Settings:**
- ✅ **Send alerts to owners**: Check this
- **Security Preference**: Default is fine

**3. Click "Submit"**

### **Copy Your Keys:**
After creation, you'll get:
- 🔑 **Site Key** (Public) - Goes in frontend
- 🔐 **Secret Key** (Private) - Goes in backend

**Example Keys (yours will be different):**
```
Site Key: 6LdxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxXX
Secret Key: 6LdxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxXX
```

### **🧪 Alternative: Use Google's Test Keys (Development Only)**

Google provides test keys that always work for localhost development:

**Test Site Key:** `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
**Test Secret Key:** `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

**⚠️ Note:** Test keys will always return SUCCESS, so use only for development and UI testing!

---

## 🚀 **Step 2: Add Keys to Your Project**

Add these to your `.env` file:
```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

---

## ⚡ **Next Steps:**
Once you have the keys:
1. Add them to `.env` file
2. I'll update the code to use Google reCAPTCHA
3. Test on mobile and desktop

**Ready to proceed once you have your reCAPTCHA keys! 🎯**