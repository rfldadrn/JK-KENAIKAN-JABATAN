# SISTEM INFORMASI PENGAJUAN KENAIKAN GOLONGAN JABATAN PEKERJA
## BRI WILAYAH PADANG

[![PHP](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)]()

---

## 📋 DESKRIPSI PROJECT

Sistem informasi berbasis web untuk mengelola proses pengajuan kenaikan golongan jabatan pekerja di Bank Rakyat Indonesia (BRI) Wilayah Padang. Sistem ini dibangun menggunakan **PHP Native** dengan arsitektur **MVC (Model-View-Controller)** dan database **MySQL**.

### Fitur Utama:
✅ Pengajuan kenaikan golongan online  
✅ Approval 3 tingkat (Atasan → Manager → Kepala Wilayah)  
✅ Upload dokumen pendukung  
✅ Tracking status real-time  
✅ Notifikasi email & in-app  
✅ Generate Surat Keputusan (SK) otomatis  
✅ Laporan dan statistik  
✅ Manajemen data master (Golongan, Divisi, Jabatan, Pekerja)  

---

## 📁 STRUKTUR DOKUMENTASI

Dokumentasi project ini terdiri dari 7 file utama:

### 1️⃣ **01_BRD_Sistem_Kenaikan_Golongan_BRI.md**
📄 Business Requirements Document (BRD)
- Executive Summary
- Stakeholder Analysis
- Functional Requirements (FR-001 s/d FR-020)
- Non-Functional Requirements
- Business Rules
- Success Criteria
- Risk Analysis

### 2️⃣ **02_Database_Design.md**
🗄️ Database Design & Structure
- Entity Relationship Diagram (ERD)
- Table Structure (10 tables)
- Relationships
- Views (v_pekerja_detail, v_pengajuan_detail)
- Stored Procedures
- Triggers
- Indexes Optimization

### 3️⃣ **03_Database_Schema.sql**
💾 SQL Script untuk membuat database
- CREATE DATABASE
- CREATE TABLE (10 tables)
- CREATE VIEW (2 views)
- CREATE PROCEDURE (3 stored procedures)
- CREATE TRIGGER (2 triggers)

### 4️⃣ **04_Sample_Data.sql**
🎲 Data Dummy untuk Testing
- Golongan Jabatan (16 records: I-A s/d IV-D)
- Divisi (10 divisi)
- Jabatan (20 jabatan)
- Pekerja (19 sample pekerja)
- Users (20 users dengan berbagai role)
- Pengajuan (7 sample pengajuan dengan berbagai status)
- Dokumen & Approval History

### 5️⃣ **05_System_Flow_and_Use_Case.md**
🔄 Flow Diagram & Use Case
- Use Case Diagram (5 actors)
- Business Process Flow
  - Main Process: Pengajuan Kenaikan Golongan
  - Approval Level 1 (Atasan)
  - Approval Level 2 (Manager)
  - Approval Level 3 (Kepala Wilayah)
- Activity Diagrams (Login, Upload, Generate SK, Notification)
- Sequence Diagrams
- State Diagram (Status Pengajuan)
- Data Flow Diagram (DFD)
- Wireframe/Mockup Reference

### 6️⃣ **06_MVC_Structure_Implementation.md**
🏗️ Struktur Folder & Implementasi MVC
- Complete Folder Structure
- Core Files (App.php, Controller.php, Model.php)
- Config Files (Database.php, Config.php)
- Helper Files (Session, Validation, Upload, Email, PDF)
- Sample Implementation (AuthController, User Model)
- Composer Configuration

### 7️⃣ **07_Implementation_Checklist.md**
✅ Checklist Implementasi & Development Guide
- Setup Project (Environment, Database, Files)
- Development Roadmap (13 Sprints)
  - Sprint 1-2: Core, Auth, Dashboard, Master Data
  - Sprint 3-4: Pekerja, Pengajuan Module
  - Sprint 5-6: Approval, Notification, Email
  - Sprint 7-8: Generate SK, Laporan
  - Sprint 9-10: Profile, Security
  - Sprint 11-13: UI/UX, Testing, Deployment
- Testing Checklist (Functional & Non-Functional)
- Deployment Checklist
- Maintenance Plan

---

## 🚀 QUICK START

### Prerequisites
- Laragon (atau XAMPP/WAMP)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Composer

### Installation Steps

#### 1. Setup Database
```bash
# Buka phpMyAdmin atau MySQL client

# Import schema
mysql -u root -p < 03_Database_Schema.sql

# Import sample data
mysql -u root -p < 04_Sample_Data.sql
```

#### 2. Clone/Create Project
```bash
# Buat folder project di htdocs/www
mkdir sistem_kenaikan_golongan_bri
cd sistem_kenaikan_golongan_bri

# Copy struktur folder dari dokumentasi 06_MVC_Structure_Implementation.md
```

#### 3. Install Dependencies
```bash
composer install
```

#### 4. Configure Environment
```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env sesuaikan konfigurasi database
nano .env
```

Isi .env:
```env
APP_URL=http://localhost/sistem_kenaikan_golongan_bri/public
DB_HOST=localhost
DB_NAME=sistem_kenaikan_golongan_bri
DB_USER=root
DB_PASS=

SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
```

#### 5. Set Permissions
```bash
chmod -R 755 public/uploads/
```

#### 6. Access Application
```
URL: http://localhost/sistem_kenaikan_golongan_bri/public
```

---

## 👤 DEFAULT LOGIN CREDENTIALS

### Admin
```
Username: admin
Password: password123
```

### Kepala Wilayah
```
Username: 19900001
Password: password123
```

### Manager
```
Username: 20050002
Password: password123
```

### Atasan/Supervisor
```
Username: 20120006
Password: password123
```

### Pekerja
```
Username: 20210014
Password: password123
```

**⚠️ PENTING:** Ubah password default setelah login pertama!

---

## 📊 DATABASE SCHEMA

### 10 Tables:
1. `users` - Data pengguna sistem
2. `golongan_jabatan` - Master golongan (I-A s/d IV-D)
3. `divisi` - Master divisi/unit kerja
4. `jabatan` - Master jabatan
5. `pekerja` - Data karyawan BRI
6. `pengajuan` - Data pengajuan kenaikan golongan
7. `dokumen_pengajuan` - Dokumen pendukung
8. `approval_history` - Riwayat persetujuan
9. `notifikasi` - Notifikasi in-app
10. `log_aktivitas` - Audit trail

### 2 Views:
- `v_pekerja_detail` - View lengkap data pekerja
- `v_pengajuan_detail` - View lengkap data pengajuan

---

## 🔐 USER ROLES

| Role | Deskripsi | Akses |
|------|-----------|-------|
| **admin** | Admin/HC | Full access - manage all data |
| **pekerja** | Karyawan | Submit pengajuan, lihat status |
| **atasan** | Supervisor | Approve/reject level 1 |
| **manager** | Manager Wilayah | Approve/reject level 2 |
| **kepala_wilayah** | Kepala Wilayah | Final approval |

---

## 📈 WORKFLOW APPROVAL

```
PEKERJA
  ↓ Submit Pengajuan
[PENDING]
  ↓ Review
ATASAN LANGSUNG (Level 1)
  ↓ Approve
[DISETUJUI ATASAN]
  ↓ Review
MANAGER WILAYAH (Level 2)
  ↓ Approve
[DISETUJUI MANAGER]
  ↓ Review
KEPALA WILAYAH (Level 3)
  ↓ Final Approve
[DISETUJUI]
  ↓
• Update Golongan Pekerja
• Generate Surat Keputusan
• Send Notification
```

---

## 🛠️ TECH STACK

### Backend
- **PHP 7.4+** (Native, no framework)
- **MySQL 5.7+** dengan PDO
- **MVC Architecture**
- **Composer** (Autoloading & Dependencies)

### Frontend
- **Bootstrap 5** (Responsive UI)
- **jQuery 3.x**
- **DataTables.js** (Table management)
- **Chart.js** (Visualisasi data)
- **SweetAlert2** (Alerts)
- **Font Awesome** (Icons)

### Libraries
- **PHPMailer** (Email notification)
- **TCPDF/mPDF** (Generate PDF)
- **vlucas/phpdotenv** (Environment variables)

---

## 📂 PROJECT STRUCTURE

```
sistem_kenaikan_golongan_bri/
├── app/
│   ├── config/          # Database & App config
│   ├── controllers/     # Controllers (MVC)
│   ├── models/          # Models (MVC)
│   ├── views/           # Views (MVC)
│   └── helpers/         # Helper functions
├── core/
│   ├── App.php          # Router
│   ├── Controller.php   # Base Controller
│   └── Model.php        # Base Model
├── public/
│   ├── index.php        # Entry point
│   ├── assets/          # CSS, JS, Images
│   └── uploads/         # Uploaded files
├── vendor/              # Composer dependencies
├── .env                 # Environment variables
├── .htaccess
└── composer.json
```

---

## 🧪 TESTING

### Test Data yang Tersedia:
- ✅ 19 Sample Pekerja dengan berbagai golongan
- ✅ 7 Sample Pengajuan dengan berbagai status
- ✅ Complete approval history
- ✅ Sample dokumen references

### Test Scenarios:
1. **Login Test** - Test semua role
2. **CRUD Test** - Test master data
3. **Pengajuan Test** - Create, edit, cancel
4. **Approval Test** - 3-level approval flow
5. **Notification Test** - Email & in-app
6. **Report Test** - Generate & export
7. **Security Test** - XSS, CSRF, SQL Injection

Lihat detail di **07_Implementation_Checklist.md**

---

## 📖 DOKUMENTASI LENGKAP

Untuk mempelajari sistem secara detail, baca dokumentasi berikut sesuai urutan:

1. **BRD** → Pahami requirements & business rules
2. **Database Design** → Pahami struktur data
3. **SQL Scripts** → Setup database
4. **Flow & Use Case** → Pahami workflow sistem
5. **MVC Implementation** → Pahami struktur code
6. **Implementation Checklist** → Guide development

---

## 🎯 DEVELOPMENT ROADMAP

| Sprint | Duration | Focus Area |
|--------|----------|------------|
| 1 | Week 1 | Core & Authentication |
| 2 | Week 2 | Dashboard & Master Data |
| 3 | Week 2 | Pekerja Management |
| 4 | Week 3 | Pengajuan Module |
| 5 | Week 4 | Approval Module |
| 6 | Week 4 | Notification & Email |
| 7 | Week 5 | Generate SK & PDF |
| 8 | Week 5 | Laporan & Export |
| 9 | Week 6 | Profile & Settings |
| 10 | Week 6 | Security & Validation |
| 11 | Week 7 | UI/UX Enhancement |
| 12 | Week 8 | Testing & Bug Fixing |
| 13 | Week 9 | Documentation & Deployment |

**Total:** 9 minggu (2-3 bulan)

---

## 🤝 CONTRIBUTION

Project ini dikembangkan untuk keperluan penelitian/skripsi.

**Peneliti:** [Nama Anda]  
**Institusi:** [Nama Universitas/Kampus]  
**Tahun:** 2024  

---

## 📞 SUPPORT & CONTACT

Jika ada pertanyaan atau butuh bantuan:

- **Email:** [email@example.com]
- **GitHub:** [github.com/username]
- **LinkedIn:** [linkedin.com/in/username]

---

## 📄 LICENSE

© 2024 - Sistem Kenaikan Golongan BRI Wilayah Padang  
Proprietary License - For Research/Educational Purpose

**Disclaimer:** Project ini dibuat untuk keperluan penelitian dan bukan merupakan sistem resmi dari Bank Rakyat Indonesia.

---

## 🙏 ACKNOWLEDGMENTS

Terima kasih kepada:
- **Bank Rakyat Indonesia Wilayah Padang** - Sebagai studi kasus
- **Dosen Pembimbing** - Untuk guidance dan support
- **Tim Penguji** - Untuk feedback konstruktif

---

## 🔄 VERSION HISTORY

| Version | Date | Description |
|---------|------|-------------|
| 1.0 | 2024 | Initial release - Complete documentation |
| 1.1 | TBD | First implementation |
| 2.0 | TBD | Production ready |

---

**Last Updated:** 2024  
**Documentation Version:** 1.0  
**Status:** ✅ Ready for Development

---

**⭐ Star this project if it helps you!**
