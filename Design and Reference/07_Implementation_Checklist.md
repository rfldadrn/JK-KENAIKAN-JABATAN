# IMPLEMENTATION CHECKLIST & DEVELOPMENT GUIDE
## SISTEM INFORMASI PENGAJUAN KENAIKAN GOLONGAN JABATAN PEKERJA
### BRI WILAYAH PADANG

---

## 1. SETUP PROJECT

### Phase 1: Environment Setup ✓
```
□ Install Laragon (Apache, PHP 7.4+, MySQL)
□ Create project folder: sistem_kenaikan_golongan_bri
□ Setup virtual host di Laragon
  URL: http://sikgol-bri.test
□ Install Composer
□ Clone/Create folder structure
```

### Phase 2: Database Setup ✓
```
□ Buka phpMyAdmin / MySQL client
□ Execute script: 03_Database_Schema.sql
□ Execute script: 04_Sample_Data.sql
□ Verify tables created (10 tables)
□ Verify sample data inserted
□ Test queries: SELECT * FROM v_pekerja_detail
```

### Phase 3: Project Files Setup ✓
```
□ Create .env file dari .env.example
□ Update .env dengan konfigurasi database
□ Run: composer install
□ Verify vendor/autoload.php created
□ Create folder uploads/ dengan permissions 755
  └── uploads/
      ├── documents/
      ├── sk/
      └── foto/
```

---

## 2. DEVELOPMENT ROADMAP

### Sprint 1: Core & Authentication (Week 1)
```
□ Setup Core Files
  □ core/App.php (Router)
  □ core/Controller.php (Base Controller)
  □ core/Model.php (Base Model)

□ Config Files
  □ app/config/Database.php
  □ app/config/Config.php

□ Helper Files
  □ app/helpers/Session.php
  □ app/helpers/Helper.php
  □ app/helpers/Validation.php

□ Authentication Module
  □ controllers/AuthController.php
    □ login() method
    □ logout() method
    □ forgot_password() method
  □ models/User.php
  □ views/auth/login.php
  □ views/auth/forgot_password.php

□ Testing
  □ Test login dengan data dummy
  □ Test role-based redirect
  □ Test session management
```

---

### Sprint 2: Dashboard & Master Data (Week 2)
```
□ Dashboard Module
  □ controllers/DashboardController.php
  □ views/dashboard/admin.php
  □ views/dashboard/pekerja.php
  □ views/dashboard/atasan.php
  □ views/dashboard/manager.php
  □ views/dashboard/kepala_wilayah.php
  □ Implement statistics & charts

□ Master Golongan Jabatan
  □ controllers/GolonganController.php (CRUD)
  □ models/GolonganJabatan.php
  □ views/golongan/index.php (DataTables)
  □ views/golongan/create.php
  □ views/golongan/edit.php

□ Master Divisi
  □ controllers/DivisiController.php (CRUD)
  □ models/Divisi.php
  □ views/divisi/index.php
  □ views/divisi/create.php
  □ views/divisi/edit.php

□ Master Jabatan
  □ controllers/JabatanController.php (CRUD)
  □ models/Jabatan.php
  □ views/jabatan/index.php
  □ views/jabatan/create.php
  □ views/jabatan/edit.php

□ Layouts
  □ views/layouts/header.php (with navbar)
  □ views/layouts/footer.php
  □ views/layouts/sidebar.php (role-based menu)
```

---

### Sprint 3: Pekerja Management (Week 2)
```
□ Pekerja Module
  □ controllers/PekerjaController.php
    □ index() - List pekerja dengan filter & search
    □ create() - Form tambah pekerja
    □ store() - Process insert
    □ edit() - Form edit pekerja
    □ update() - Process update
    □ delete() - Soft/hard delete
    □ detail() - Detail pekerja
    □ import() - Import dari Excel (optional)
  
  □ models/Pekerja.php
    □ getAll() with pagination
    □ getById()
    □ getByNIP()
    □ insert()
    □ update()
    □ delete()
    □ getByDivisi()
    □ getByGolongan()
    □ calculateMasaKerja()
  
  □ views/pekerja/
    □ index.php - DataTables, filter, search
    □ create.php - Form validation
    □ edit.php
    □ detail.php

□ Testing
  □ CRUD operations
  □ Validation rules
  □ Upload foto pekerja
```

---

### Sprint 4: Pengajuan Module (Week 3)
```
□ Pengajuan Controller
  □ controllers/PengajuanController.php
    □ index() - List pengajuan user
    □ create() - Form pengajuan baru
    □ store() - Process submit
      □ Validate syarat kenaikan
      □ Upload dokumen
      □ Generate nomor pengajuan
      □ Send notification
    □ detail() - Detail pengajuan
    □ edit() - Edit pengajuan (jika pending)
    □ update() - Update pengajuan
    □ cancel() - Batalkan pengajuan
    □ riwayat() - Riwayat pengajuan user

□ Pengajuan Model
  □ models/Pengajuan.php
    □ getByPekerja()
    □ getPendingByAtasan()
    □ insert()
    □ update()
    □ updateStatus()
    □ checkActivePengajuan()
    □ validateSyaratKenaikan()
    □ generateNomorPengajuan()
  
  □ models/DokumenPengajuan.php
    □ insert()
    □ getByPengajuan()
    □ delete()

□ Upload Helper
  □ app/helpers/Upload.php
    □ uploadDocument()
    □ validateFile()
    □ deleteFile()
    □ getFileSize()
    □ getMimeType()

□ Views
  □ views/pengajuan/index.php
  □ views/pengajuan/create.php (multi-step form)
  □ views/pengajuan/detail.php (timeline status)
  □ views/pengajuan/riwayat.php

□ Testing
  □ Test validasi syarat
  □ Test upload multiple files
  □ Test generate nomor
  □ Test insert ke database
```

---

### Sprint 5: Approval Module (Week 4)
```
□ Approval Controller
  □ controllers/ApprovalController.php
    □ index() - List pending approval based on role
    □ review() - Form review pengajuan
    □ approve() - Process approve
      □ Update status pengajuan
      □ Insert approval history
      □ Send notification ke next approver
    □ reject() - Process reject
      □ Update status
      □ Insert history
      □ Send notification ke pekerja
    □ history() - Riwayat approval user

□ Approval Model
  □ models/ApprovalHistory.php
    □ insert()
    □ getByPengajuan()
    □ getByApprover()

□ Business Logic
  □ Approval Atasan (Level 1)
    □ Validate role = 'atasan'
    □ Update status → 'disetujui_atasan' / 'ditolak_atasan'
    □ Forward ke Manager jika approve
  
  □ Approval Manager (Level 2)
    □ Validate role = 'manager'
    □ Update status → 'disetujui_manager' / 'ditolak_manager'
    □ Forward ke Kepala Wilayah jika approve
  
  □ Final Approval Kepala Wilayah (Level 3)
    □ Validate role = 'kepala_wilayah'
    □ Update status → 'disetujui' / 'ditolak_kepala_wilayah'
    □ Jika approve:
      - Update golongan pekerja
      - Generate SK
      - Send congratulation email

□ Views
  □ views/approval/index.php (list by level)
  □ views/approval/review.php (form dengan timeline)

□ Testing
  □ Test 3-level approval flow
  □ Test reject di setiap level
  □ Test notification trigger
```

---

### Sprint 6: Notification & Email (Week 4)
```
□ Notification Module
  □ controllers/NotifikasiController.php
    □ index() - List notifikasi
    □ markAsRead() - Mark notification as read
    □ delete() - Hapus notifikasi
  
  □ models/Notifikasi.php
    □ insert()
    □ getByUser()
    □ getUnreadCount()
    □ markAsRead()
    □ delete()

□ Email Helper
  □ app/helpers/Email.php (PHPMailer)
    □ send()
    □ sendPengajuanBaru() - Template email
    □ sendApprovalNotification()
    □ sendRejectionNotification()
    □ sendFinalApprovalNotification()

□ Email Templates (HTML)
  □ Create email templates
  □ Use placeholders: {nama}, {nomor_pengajuan}, etc.

□ Views
  □ views/notifikasi/index.php
  □ Notification bell in navbar (badge counter)

□ Testing
  □ Test email sending
  □ Test in-app notification
  □ Test mark as read
```

---

### Sprint 7: Generate SK & PDF (Week 5)
```
□ SK Generation
  □ app/helpers/Pdf.php (TCPDF/mPDF)
    □ generateSK()
    □ Create SK template
    □ Add header/footer
    □ Add logo BRI
    □ Generate unique filename

□ SK Template Design
  □ KOP Surat BRI
  □ Nomor SK
  □ Isi surat (dynamic data)
  □ Tanda tangan digital/placeholder

□ Integration
  □ Call generateSK() after final approval
  □ Save PDF to uploads/sk/
  □ Update pengajuan.file_sk
  □ Provide download link

□ Testing
  □ Test PDF generation
  □ Test file save
  □ Test download link
```

---

### Sprint 8: Laporan & Export (Week 5)
```
□ Laporan Controller
  □ controllers/LaporanController.php
    □ pengajuan() - Laporan pengajuan
      □ Filter: periode, status, divisi, golongan
      □ Export PDF/Excel
    □ pekerjaPerGolongan() - Distribusi pekerja
      □ Chart & table
      □ Export
    □ riwayatKenaikan() - Riwayat per pekerja
      □ Timeline kenaikan
      □ Export

□ Views
  □ views/laporan/pengajuan.php
  □ views/laporan/pekerja_per_golongan.php
  □ views/laporan/riwayat_kenaikan.php
  □ Use Chart.js for visualization

□ Export Functionality
  □ PDF Export (TCPDF)
  □ Excel Export (PHPExcel/PhpSpreadsheet)

□ Testing
  □ Test filter & search
  □ Test export formats
  □ Test chart rendering
```

---

### Sprint 9: Profile & Settings (Week 6)
```
□ Profile Module
  □ controllers/ProfilController.php
    □ index() - View profile
    □ edit() - Edit profile
    □ update() - Update profile
    □ changePassword() - Ubah password

  □ views/profil/index.php
  □ views/profil/edit.php
  □ views/profil/change_password.php

□ Testing
  □ Update profile data
  □ Change password
  □ Upload foto profil
```

---

### Sprint 10: Security & Validation (Week 6)
```
□ Security Implementation
  □ CSRF Token
    □ Generate token di form
    □ Validate di controller
  
  □ XSS Prevention
    □ htmlspecialchars() di output
    □ Validation::sanitize() di input
  
  □ SQL Injection Prevention
    □ Prepared statements (already implemented in PDO)
  
  □ Password Security
    □ password_hash() & password_verify()
    □ Min 8 characters
    □ Force change password after first login (optional)
  
  □ File Upload Security
    □ Validate file type
    □ Validate file size
    □ Rename file (unique)
    □ Malware scan (optional)
  
  □ Session Security
    □ Session timeout
    □ Regenerate session ID
    □ Secure cookie flags

□ Validation Enhancement
  □ Client-side validation (JavaScript)
  □ Server-side validation (PHP)
  □ Error message handling
  □ Form validation feedback

□ Testing
  □ Test CSRF protection
  □ Test XSS attempts
  □ Test SQL injection attempts
  □ Test file upload vulnerabilities
```

---

### Sprint 11: UI/UX Enhancement (Week 7)
```
□ Frontend Framework
  □ Bootstrap 5
  □ Font Awesome icons
  □ DataTables.js
  □ Chart.js
  □ SweetAlert2 (alerts)
  □ Select2 (dropdown)

□ Responsive Design
  □ Mobile-friendly layout
  □ Responsive tables
  □ Hamburger menu
  □ Touch-friendly buttons

□ User Experience
  □ Loading indicators
  □ Progress bars
  □ Tooltips
  □ Breadcrumbs
  □ Smooth transitions
  □ Form validation feedback
  □ Success/error messages

□ Accessibility
  □ Semantic HTML
  □ ARIA labels
  □ Keyboard navigation
  □ Color contrast

□ Testing
  □ Test on different screen sizes
  □ Test on mobile devices
  □ Cross-browser testing
```

---

### Sprint 12: Testing & Bug Fixing (Week 8)
```
□ Unit Testing
  □ Test models
  □ Test controllers
  □ Test helpers
  □ Test validation

□ Integration Testing
  □ Test complete workflows
  □ Test approval process end-to-end
  □ Test notification delivery
  □ Test file uploads

□ User Acceptance Testing (UAT)
  □ Create test scenarios
  □ Invite stakeholders to test
  □ Document feedback
  □ Fix issues

□ Performance Testing
  □ Load testing
  □ Database query optimization
  □ Caching implementation (optional)

□ Security Testing
  □ Penetration testing
  □ Vulnerability scanning
  □ Code review
```

---

### Sprint 13: Documentation & Deployment (Week 9)
```
□ Documentation
  □ README.md
    □ Installation guide
    □ Configuration guide
    □ Usage guide
  
  □ User Manual
    □ How to create pengajuan
    □ How to approve
    □ How to generate laporan
  
  □ API Documentation (if any)
  
  □ Database Documentation
    □ ER Diagram
    □ Table descriptions

□ Deployment Preparation
  □ Database backup
  □ Code optimization
  □ Remove debug code
  □ Update .env for production
  □ Set error_reporting = 0
  □ Enable HTTPS
  □ Setup SSL certificate

□ Production Deployment
  □ Upload files to server
  □ Import database
  □ Configure web server
  □ Set permissions
  □ Test all features
  □ Monitor for errors

□ Training
  □ User training sessions
  □ Admin training
  □ Create training materials
```

---

## 3. TESTING CHECKLIST

### Functional Testing
```
□ Login/Logout
  □ Valid credentials
  □ Invalid credentials
  □ Account locked after 3 failed attempts
  □ Session timeout

□ Pengajuan
  □ Create pengajuan dengan dokumen valid
  □ Validasi syarat (masa kerja, nilai kinerja)
  □ Upload multiple documents
  □ Edit pengajuan (status pending only)
  □ Cancel pengajuan

□ Approval
  □ Approve di level 1 (Atasan)
  □ Approve di level 2 (Manager)
  □ Final approve di level 3 (Kepala)
  □ Reject di setiap level
  □ Notification trigger

□ Master Data CRUD
  □ Create, Read, Update, Delete
  □ Validation
  □ Foreign key constraints

□ Laporan
  □ Generate laporan dengan filter
  □ Export PDF
  □ Export Excel
  □ Chart rendering

□ Notifikasi
  □ Email notification sent
  □ In-app notification created
  □ Mark as read
  □ Badge counter update
```

### Non-Functional Testing
```
□ Performance
  □ Page load time < 3 seconds
  □ Database query optimization
  □ Image optimization

□ Security
  □ XSS prevention
  □ CSRF protection
  □ SQL injection prevention
  □ File upload security
  □ Password strength

□ Usability
  □ Intuitive navigation
  □ Clear error messages
  □ Helpful tooltips
  □ Consistent design

□ Compatibility
  □ Chrome (latest)
  □ Firefox (latest)
  □ Safari (latest)
  □ Edge (latest)
  □ Mobile browsers
```

---

## 4. DEPLOYMENT CHECKLIST

### Pre-Deployment
```
□ Code review completed
□ All tests passed
□ Documentation updated
□ Database backup created
□ Production environment prepared
```

### Deployment Steps
```
□ Upload files to server
□ Import database
□ Configure .env file
□ Set file permissions
  □ uploads/ → 755
  □ vendor/ → 755
  □ .env → 644
□ Test database connection
□ Test all features
□ Enable error logging
□ Monitor for errors
```

### Post-Deployment
```
□ Verify all features working
□ Check email notifications
□ Monitor server logs
□ User training completed
□ Stakeholder sign-off
```

---

## 5. MAINTENANCE PLAN

### Daily
```
□ Monitor error logs
□ Check email delivery
□ Verify database backup
```

### Weekly
```
□ Review user feedback
□ Check system performance
□ Update statistics
```

### Monthly
```
□ Security audit
□ Database optimization
□ Archive old data
□ Update documentation
```

### Quarterly
```
□ Feature enhancements
□ Performance optimization
□ User satisfaction survey
□ System upgrade (if needed)
```

---

## 6. CONTACT & SUPPORT

```
Developer: [Nama Developer]
Email: [email@example.com]
Phone: [No Telepon]

Project Manager: [Nama PM]
Email: [email@example.com]

Technical Support: [Nama Support]
Email: support@bri.co.id
```

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Status**: Ready for Development
