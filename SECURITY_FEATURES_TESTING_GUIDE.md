# 🎯 **How to Access & Test Security Features**

## 🔐 **Admin Security Features Testing Guide**

### **1. Two-Factor Authentication (2FA) Setup**

#### **🚀 How to Enable 2FA for Admin:**
```
1. Login to Admin Panel: http://127.0.0.1:8000/admin/login
2. Go to Admin Profile: http://127.0.0.1:8000/admin/profile  
3. Look for "Two-Factor Authentication" section
4. Click "Enable 2FA" or visit: http://127.0.0.1:8000/admin/2fa/setup
5. Scan QR code with Google Authenticator app
6. Enter verification code to confirm setup
```

#### **📱 Google Authenticator Setup:**
- Download **Google Authenticator** from App Store/Play Store
- Scan the QR code displayed on setup page
- Enter the 6-digit code to verify

---

### **2. reCAPTCHA Protection Testing**

#### **✅ What's Already Active:**
- **Admin Login:** reCAPTCHA required on `/admin/login`
- **Resident Login:** reCAPTCHA required on `/login` 
- **Collector Login:** reCAPTCHA required on `/collector/login`

#### **🧪 How to Test:**
```
1. Visit any login page
2. Try to submit without completing reCAPTCHA → Should get error
3. Complete reCAPTCHA → Should allow login
4. Check for "Please complete the reCAPTCHA verification" error
```

---

### **3. Laravel Sanctum API Features**

#### **🔑 Generate API Tokens:**
```php
// In Laravel Tinker or Controller
$admin = App\Models\Admin::find(1);
$token = $admin->createToken('admin-api-token')->plainTextToken;
echo $token; // Use this token for API requests
```

#### **🌐 API Endpoints (if created):**
```bash
# Test with Bearer token
curl -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Accept: application/json" \
     http://127.0.0.1:8000/api/admin/profile

# Get user data
curl -H "Authorization: Bearer YOUR_TOKEN" \
     http://127.0.0.1:8000/api/user
```

---

### **4. Security Logging & Monitoring**

#### **📊 Check Security Logs:**
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Look for security events
grep "Admin Security Event" storage/logs/laravel.log
```

#### **🔍 Security Events Tracked:**
- ✅ Login successes and failures
- ✅ 2FA enable/disable events  
- ✅ Password changes
- ✅ Session management
- ✅ IP access attempts

---

## 🎨 **Add Security Dashboard to Admin Panel**

Let me create a security dashboard that makes these features accessible via the web interface.

### **Admin Security Dashboard Features:**
1. **2FA Management** - Enable/disable, view recovery codes
2. **Security Logs** - Recent login attempts and activities  
3. **Session Management** - Active sessions, force logout
4. **API Token Management** - Create/revoke API tokens
5. **Security Settings** - Configure security options

---

## 🔧 **Quick Access URLs**

### **For Admins:**
- **2FA Setup:** `http://127.0.0.1:8000/admin/2fa/setup`
- **Profile Settings:** `http://127.0.0.1:8000/admin/profile`
- **Security Dashboard:** `http://127.0.0.1:8000/admin/security` (will create)

### **For Testing:**
- **Admin Login:** `http://127.0.0.1:8000/admin/login`
- **Resident Login:** `http://127.0.0.1:8000/login`
- **Collector Login:** `http://127.0.0.1:8000/collector/login`

---

## 🧪 **Testing Scenarios**

### **Test 1: reCAPTCHA Protection**
```
❌ Submit login without reCAPTCHA → Error message
✅ Complete reCAPTCHA → Allow submission  
✅ Invalid reCAPTCHA → Error message
```

### **Test 2: 2FA Flow**
```
✅ Enable 2FA → QR code shown
✅ Scan with Google Authenticator → 6-digit codes generated
✅ Login with 2FA → Redirected to verification page
✅ Enter correct code → Access granted
❌ Enter wrong code → Error message
```

### **Test 3: API Token Usage**
```
✅ Generate token → Token created
✅ Use token in API call → Data returned  
✅ Revoke token → Access denied
```

### **Test 4: Security Logging**
```
✅ Login attempt → Event logged
✅ Failed login → Security event recorded
✅ 2FA changes → Audit trail created
```

---

## 🚀 **Next: Create Web Interface**

Would you like me to create:
1. **Security Dashboard** - Web interface for managing all security features
2. **2FA Management Page** - Easy enable/disable 2FA
3. **API Token Manager** - Create/manage API tokens via web
4. **Security Logs Viewer** - See security events in admin panel

This will make all the security features easily accessible through the website interface! 🛡️✨