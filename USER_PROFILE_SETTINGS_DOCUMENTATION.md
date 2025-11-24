# User Profile & Settings Feature Documentation

## ✅ Fitur yang Sudah Diimplementasikan

### 1. **Profile Management**

-   ✅ Halaman edit profil user (`/user/profile`)
-   ✅ Form dengan validasi lengkap
-   ✅ Upload foto profil (wajib, JPG/PNG, max 2MB)
-   ✅ Upload KTP (opsional, JPG/PNG/PDF, max 5MB)
-   ✅ Upload sertifikat (opsional, multiple files, JPG/PNG/PDF, max 5MB)
-   ✅ Preview file yang sudah diupload
-   ✅ FilePond integration untuk UX yang lebih baik

### 2. **Settings Management**

-   ✅ Halaman pengaturan akun (`/user/settings`)
-   ✅ Form ganti password dengan validasi current password
-   ✅ Form ganti email dengan validasi unique
-   ✅ Toggle password visibility
-   ✅ Security tips section

### 3. **Validation & Security**

-   ✅ UpdateUserProfileRequest dengan validasi:
    -   nama: required, string, max:255
    -   nik: required, numeric, digits:16, unique (ignore current user)
    -   foto: required (jika upload), image, mimes:jpg,jpeg,png, max:2048
    -   ktp: nullable, file, mimes:jpg,jpeg,png,pdf, max:5120
    -   sertifikat.\*: nullable, file, mimes:jpg,jpeg,png,pdf, max:5120
-   ✅ UpdatePasswordRequest dengan validasi:
    -   current_password: required, must match user's current password
    -   password: required, min:8, confirmed
-   ✅ UpdateEmailRequest dengan validasi:
    -   email: required, email, unique (ignore current user)

### 4. **Controller Methods**

-   ✅ `profile()` - Menampilkan halaman profile
-   ✅ `updateProfileNew()` - Update profile dengan file handling
-   ✅ `settings()` - Menampilkan halaman settings
-   ✅ `updatePassword()` - Update password dengan Hash check
-   ✅ `updateEmail()` - Update email dengan unique validation

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── User/
│   │       └── UserDashboardController.php   (Updated: 3 new methods)
│   └── Requests/
│       ├── UpdateUserProfileRequest.php      (New)
│       ├── UpdatePasswordRequest.php         (New)
│       └── UpdateEmailRequest.php            (New)

resources/
└── views/
    └── user/
        ├── profile.blade.php                  (New: Complete with FilePond)
        └── settings.blade.php                 (New: Password & Email forms)

routes/
└── web.php                                    (Updated: 3 new routes)
```

## 🎨 UI Features

### Profile Page (`/user/profile`)

**Components:**

-   **Form Fields:**
    -   Nama Lengkap (text input, required)
    -   NIK (16 digits, numeric only, required)
    -   Foto Profil (FilePond, JPG/PNG, max 2MB, required)
    -   Scan KTP (FilePond, JPG/PNG/PDF, max 5MB, optional)
    -   Sertifikat (FilePond, multiple, JPG/PNG/PDF, max 5MB, optional)

**Features:**

-   ✅ Preview foto profil existing
-   ✅ Preview KTP (image/PDF link)
-   ✅ Grid display untuk multiple sertifikat
-   ✅ FilePond dengan drag & drop
-   ✅ Progress bar saat upload
-   ✅ File validation real-time
-   ✅ Auto NIK validation (numbers only)
-   ✅ Responsive design

### Settings Page (`/user/settings`)

**Sections:**

1. **Ganti Password Card**
    - Current password dengan toggle visibility
    - New password dengan toggle visibility
    - Password confirmation dengan toggle visibility
    - Validation messages
2. **Ganti Email Card**
    - Email baru input
    - Display current email
    - Unique validation
3. **Security Tips Card**
    - Best practices untuk password
    - Security recommendations

## 🔧 Routes

```php
// Profile Routes
GET  /user/profile              → UserDashboardController@profile
PUT  /user/profile              → UserDashboardController@updateProfileNew

// Settings Routes
GET  /user/settings             → UserDashboardController@settings
PUT  /user/password             → UserDashboardController@updatePassword
PUT  /user/email                → UserDashboardController@updateEmail
```

## 📝 Validation Rules

### Profile Update

```php
'nama' => 'required|string|max:255'
'nik' => 'required|numeric|digits:16|unique:user,nik,{user_id},id_user'
'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
'ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
'sertifikat.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
```

### Password Update

```php
'current_password' => 'required|string' (+ manual Hash::check)
'password' => 'required|string|min:8|confirmed'
'password_confirmation' => 'required|string'
```

### Email Update

```php
'email' => 'required|email|max:255|unique:user,email,{user_id},id_user'
```

## 📦 File Storage

**Storage Structure:**

```
storage/app/public/
├── uploads/
│   └── user/
│       ├── foto/           (Profile photos)
│       ├── ktp/            (KTP scans)
│       └── sertifikat/     (Certificates)
```

**File Handling:**

-   ✅ Old files deleted when uploading new ones
-   ✅ Unique filenames with Laravel's `store()` method
-   ✅ Sertifikat stored as JSON array for multiple files
-   ✅ Public disk untuk easy access

## 🧪 Testing Scenarios

### ✅ Profile Update - Success Cases

1. **Upload semua field**

    - Isi nama, NIK
    - Upload foto (JPG, < 2MB)
    - Upload KTP (PDF, < 5MB)
    - Upload 2-3 sertifikat (PNG, < 5MB each)
    - **Expected:** All saved, redirected with success message

2. **Update foto only**

    - Change profile photo
    - **Expected:** Old photo deleted, new photo saved

3. **Add certificates**
    - Upload new certificates while existing ones remain
    - **Expected:** New certificates appended to array

### ❌ Profile Update - Validation Errors

1. **NIK invalid**

    - NIK < 16 digits → Error: "NIK harus 16 digit angka"
    - NIK non-numeric → Error: "NIK harus berupa angka"
    - NIK already exists → Error: "NIK sudah terdaftar"

2. **Foto invalid**

    - Upload PDF → Error: "Format foto harus JPG, JPEG, atau PNG"
    - Upload 5MB file → Error: "Ukuran foto maksimal 2MB"

3. **KTP invalid**

    - Upload 10MB file → Error: "Ukuran file KTP maksimal 5MB"

4. **Sertifikat invalid**
    - Upload .docx → Error: "Format sertifikat harus JPG, JPEG, PNG, atau PDF"

### ✅ Password Update - Success Case

1. **Valid password change**
    - Current password: correct
    - New password: "NewPass123" (8+ chars)
    - Confirmation: "NewPass123" (matches)
    - **Expected:** Password updated, success message

### ❌ Password Update - Validation Errors

1. **Wrong current password**

    - **Expected:** Error: "Password lama tidak sesuai"

2. **Password too short**

    - New password: "123" (< 8 chars)
    - **Expected:** Error: "Password baru minimal 8 karakter"

3. **Password mismatch**
    - New password: "NewPass123"
    - Confirmation: "NewPass456"
    - **Expected:** Error: "Konfirmasi password tidak cocok"

### ✅ Email Update - Success Case

1. **Valid email change**
    - New email: "newemail@example.com"
    - Email not in database
    - **Expected:** Email updated, success message

### ❌ Email Update - Validation Errors

1. **Email already exists**

    - **Expected:** Error: "Email sudah digunakan oleh user lain"

2. **Invalid email format**
    - Email: "notanemail"
    - **Expected:** Error: "Format email tidak valid"

## 🔒 Security Features

1. **Authentication Required**

    - All routes protected by `user` middleware
    - Redirect to login if not authenticated

2. **Password Hashing**

    - Current password verified with Hash::check()
    - New password hashed with Hash::make()

3. **Unique Constraints**

    - NIK unique validation (ignore current user)
    - Email unique validation (ignore current user)

4. **File Validation**

    - MIME type checking
    - File size limits
    - Extension validation

5. **CSRF Protection**
    - @csrf token in all forms
    - Laravel automatic validation

## 🎯 Usage Examples

### Access Profile Page

```
URL: http://127.0.0.1:8000/user/profile
Method: GET
Auth: Required (user middleware)
```

### Update Profile

```
URL: http://127.0.0.1:8000/user/profile
Method: PUT
Auth: Required
Form Data:
  - nama: "John Doe"
  - nik: "1234567890123456"
  - foto: [File] photo.jpg
  - ktp: [File] ktp.pdf (optional)
  - sertifikat[]: [File] cert1.pdf, cert2.jpg (optional)
```

### Access Settings Page

```
URL: http://127.0.0.1:8000/user/settings
Method: GET
Auth: Required
```

### Update Password

```
URL: http://127.0.0.1:8000/user/password
Method: PUT
Auth: Required
Form Data:
  - current_password: "OldPassword123"
  - password: "NewPassword456"
  - password_confirmation: "NewPassword456"
```

### Update Email

```
URL: http://127.0.0.1:8000/user/email
Method: PUT
Auth: Required
Form Data:
  - email: "newemail@example.com"
```

## 🚀 Integration Notes

### FilePond CDN

**Required Scripts:**

```html
<!-- CSS -->
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link
    href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
    rel="stylesheet"
/>

<!-- JS -->
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
```

### Storage Link

**Pastikan symbolic link sudah dibuat:**

```bash
php artisan storage:link
```

## 📊 Database Schema

### User Table Columns (relevant)

```sql
nama VARCHAR(255)           -- Full name
nik VARCHAR(16) UNIQUE      -- 16-digit ID number
foto VARCHAR(255)           -- Profile photo path (required)
ktp VARCHAR(255) NULL       -- KTP scan path (optional)
sertifikat TEXT NULL        -- JSON array of certificate paths (optional)
email VARCHAR(255) UNIQUE   -- Email address
password VARCHAR(255)       -- Hashed password
```

## 🎨 Custom Error Messages

All validation errors have user-friendly Indonesian messages:

-   ✅ "Foto profil wajib diupload."
-   ✅ "NIK harus 16 digit angka."
-   ✅ "Format foto harus JPG, JPEG, atau PNG."
-   ✅ "Ukuran foto maksimal 2MB."
-   ✅ "Password lama tidak sesuai."
-   ✅ "Password baru minimal 8 karakter."
-   ✅ "Email sudah digunakan oleh user lain."

## ✨ Features Summary

**Profile:**

-   [x] Edit nama dan NIK
-   [x] Upload/update foto profil (required)
-   [x] Upload/update KTP (optional)
-   [x] Upload multiple sertifikat (optional)
-   [x] Preview existing files
-   [x] FilePond drag & drop
-   [x] Real-time validation
-   [x] Responsive design

**Settings:**

-   [x] Change password (with current password check)
-   [x] Change email (with unique validation)
-   [x] Toggle password visibility
-   [x] Security tips & best practices
-   [x] Separate forms for better UX

**Security:**

-   [x] Authentication required
-   [x] CSRF protection
-   [x] Password hashing
-   [x] File validation
-   [x] Unique constraints
-   [x] Error handling

Semua fitur sudah lengkap dan siap digunakan! 🎉
