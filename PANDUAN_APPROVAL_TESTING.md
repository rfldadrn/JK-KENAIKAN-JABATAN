# 📋 PANDUAN APPROVAL & TESTING SISTEM KENAIKAN GOLONGAN BRI

## 🎯 STRUKTUR ORGANISASI & HIERARKI

### **Level 1: KEPALA WILAYAH (Top Management)**
```
┌─────────────────────────────────────────────────┐
│ NIP: 19900001                                   │
│ Nama: Dr. Budi Santoso, MBA                     │
│ Role: kepala_wilayah                            │
│ Jabatan: Kepala Wilayah                         │
│ Golongan: IV-D                                  │
│ Username: 19900001 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Final Approval (Level 3)                     │
│ ✓ Approve pengajuan dengan status              │
│   "disetujui_manager"                          │
│ ✓ Tidak punya atasan                           │
└─────────────────────────────────────────────────┘
                        │
                        │ (ATASAN DARI SEMUA MANAGER)
                        ↓
```

---

### **Level 2: MANAGER (Manager Wilayah)**

#### **Manager 1 - Manager Operasional**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20050002                                   │
│ Nama: Ir. Siti Rahmawati, M.M.                  │
│ Role: manager                                   │
│ Jabatan: Manager Operasional                    │
│ Golongan: IV-B                                  │
│ Atasan: id_pekerja=1 (Kepala Wilayah)          │
│ Username: 20050002 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 2                             │
│ ✓ Approve pengajuan dengan status              │
│   "disetujui_atasan"                           │
│ ✓ Atasan dari Supervisor ID: 6, 8              │
└─────────────────────────────────────────────────┘
                        │
            ┌───────────┴───────────┐
            ↓                       ↓
    [Supervisor 6]          [Supervisor 8]
    Rina Marlina           Maya Sari
```

#### **Manager 2 - Manager Marketing**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20060003                                   │
│ Nama: Ahmad Yani, S.E., M.M.                    │
│ Role: manager                                   │
│ Jabatan: Manager Marketing                      │
│ Golongan: IV-B                                  │
│ Atasan: id_pekerja=1 (Kepala Wilayah)          │
│ Username: 20060003 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 2                             │
│ ✓ Atasan dari Supervisor ID: 7                 │
└─────────────────────────────────────────────────┘
                        │
                        ↓
                [Supervisor 7]
                Fadli Rahman
```

#### **Manager 3 - Manager Kredit**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20070004                                   │
│ Nama: Dewi Sartika, S.E.                        │
│ Role: manager                                   │
│ Jabatan: Manager Kredit                         │
│ Golongan: IV-A                                  │
│ Atasan: id_pekerja=1 (Kepala Wilayah)          │
│ Username: 20070004 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 2                             │
│ ✓ Bisa review semua pengajuan status           │
│   "disetujui_atasan"                           │
└─────────────────────────────────────────────────┘
```

#### **Manager 4 - Manager IT**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20080005                                   │
│ Nama: Hendri Prasetyo, S.Kom., M.T.             │
│ Role: manager                                   │
│ Jabatan: Manager IT                             │
│ Golongan: IV-A                                  │
│ Atasan: id_pekerja=1 (Kepala Wilayah)          │
│ Username: 20080005 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 2                             │
│ ✓ Atasan dari Supervisor ID: 9                 │
└─────────────────────────────────────────────────┘
                        │
                        ↓
                [Supervisor 9]
                Dedi Kurniawan
```

---

### **Level 3: SUPERVISOR (Atasan Langsung)**

#### **Supervisor 1 - Supervisor Operasional**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20120006                                   │
│ Nama: Rina Marlina, S.E.                        │
│ Role: atasan                                    │
│ Jabatan: Supervisor Operasional                 │
│ Golongan: III-B                                 │
│ Atasan: id_pekerja=2 (Manager Operasional)     │
│ Username: 20120006 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 1 (Atasan Langsung)          │
│ ✓ Approve pengajuan dari bawahannya dengan     │
│   status "pending"                             │
│                                                 │
│ BAWAHAN:                                        │
│ - Budi Setiawan (NIP: 20220017)                │
└─────────────────────────────────────────────────┘
                        │
                        ↓
            [Pekerja: Budi Setiawan]
```

#### **Supervisor 2 - Supervisor Marketing**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20130007                                   │
│ Nama: Fadli Rahman, S.Sos.                      │
│ Role: atasan                                    │
│ Jabatan: Supervisor Marketing                   │
│ Golongan: III-B                                 │
│ Atasan: id_pekerja=3 (Manager Marketing)       │
│ Username: 20130007 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 1                             │
│                                                 │
│ BAWAHAN:                                        │
│ - Lusi Handayani (NIP: 20180010)               │
│ - Rio Pratama (NIP: 20230019)                  │
└─────────────────────────────────────────────────┘
                        │
            ┌───────────┴───────────┐
            ↓                       ↓
    [Lusi Handayani]         [Rio Pratama]
```

#### **Supervisor 3 - Supervisor Customer Service**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20140008                                   │
│ Nama: Maya Sari, S.E.                           │
│ Role: atasan                                    │
│ Jabatan: Supervisor Customer Service            │
│ Golongan: III-A                                 │
│ Atasan: id_pekerja=2 (Manager Operasional)     │
│ Username: 20140008 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 1                             │
│                                                 │
│ BAWAHAN:                                        │
│ - Sari Dewi (NIP: 20210014) ⭐                 │
│ - Nia Kurnia (NIP: 20220016)                   │
│ - Fitri Ramadhani (NIP: 20230018)              │
└─────────────────────────────────────────────────┘
                        │
            ┌───────────┼───────────┐
            ↓           ↓           ↓
     [Sari Dewi]  [Nia Kurnia] [Fitri]
```

#### **Supervisor 4 - Supervisor IT**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20150009                                   │
│ Nama: Dedi Kurniawan, S.Kom.                    │
│ Role: atasan                                    │
│ Jabatan: Supervisor IT                          │
│ Golongan: III-A                                 │
│ Atasan: id_pekerja=5 (Manager IT)              │
│ Username: 20150009 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Approval Level 1                             │
│                                                 │
│ BAWAHAN:                                        │
│ - Rudi Hartono (NIP: 20190013)                 │
│ - Andi Wijaya (NIP: 20210015)                  │
└─────────────────────────────────────────────────┘
                        │
            ┌───────────┴───────────┐
            ↓                       ↓
    [Rudi Hartono]           [Andi Wijaya]
```

---

### **Level 4: PEKERJA (Staff)**

#### **Contoh: Sari Dewi (User Testing)**
```
┌─────────────────────────────────────────────────┐
│ NIP: 20210014 ⭐ (USER TESTING)                │
│ Nama: Sari Dewi, S.E.                           │
│ Role: pekerja                                   │
│ Jabatan: Customer Service                       │
│ Golongan: I-C                                   │
│ Atasan: id_pekerja=8 (Maya Sari)               │
│ Username: 20210014 | Password: password123      │
│                                                 │
│ FUNGSI:                                         │
│ ✓ Buat pengajuan kenaikan golongan             │
│ ✓ Lihat status pengajuan                       │
│ ✓ Lihat riwayat pengajuan                      │
│                                                 │
│ ALUR APPROVAL PENGAJUANNYA:                    │
│ 1. Submit → Status: PENDING                    │
│ 2. Atasan (Maya Sari - 20140008) approve      │
│    → Status: DISETUJUI_ATASAN                  │
│ 3. Manager (Siti Rahmawati - 20050002) approve│
│    → Status: DISETUJUI_MANAGER                 │
│ 4. Kepala Wilayah (Budi Santoso - 19900001)   │
│    → Status: DISETUJUI                         │
└─────────────────────────────────────────────────┘
```

---

## 🔄 ALUR APPROVAL LENGKAP

```
┌──────────────────────────────────────────────────────────────┐
│                     FLOW PENGAJUAN                            │
└──────────────────────────────────────────────────────────────┘

1️⃣ PEKERJA SUBMIT PENGAJUAN
   ↓
   Status: PENDING
   ↓
   Notifikasi ke: ATASAN LANGSUNG

2️⃣ ATASAN LANGSUNG REVIEW (Level 1)
   ↓
   ┌─────────┬─────────┐
   │ APPROVE │ REJECT  │
   └─────────┴─────────┘
       ↓           ↓
   DISETUJUI   DITOLAK_ATASAN
   _ATASAN     (SELESAI)
       ↓
   Notifikasi ke: SEMUA MANAGER

3️⃣ MANAGER REVIEW (Level 2)
   ↓
   ┌─────────┬─────────┐
   │ APPROVE │ REJECT  │
   └─────────┴─────────┘
       ↓           ↓
   DISETUJUI   DITOLAK_MANAGER
   _MANAGER    (SELESAI)
       ↓
   Notifikasi ke: KEPALA WILAYAH

4️⃣ KEPALA WILAYAH REVIEW (Level 3)
   ↓
   ┌─────────┬─────────────────┐
   │ APPROVE │ REJECT          │
   └─────────┴─────────────────┘
       ↓               ↓
   DISETUJUI   DITOLAK_KEPALA_WILAYAH
       ↓               (SELESAI)
       │
   ✅ UPDATE GOLONGAN PEKERJA
   ✅ GENERATE SK (Surat Keputusan)
   ✅ NOTIFIKASI KE PEKERJA
```

---

## 📊 TABEL RELASI ATASAN-BAWAHAN

| ID | NIP | Nama | Role | id_atasan | Nama Atasan |
|---|---|---|---|---|---|
| 1 | 19900001 | Dr. Budi Santoso | kepala_wilayah | NULL | - |
| 2 | 20050002 | Siti Rahmawati | manager | 1 | Dr. Budi Santoso |
| 3 | 20060003 | Ahmad Yani | manager | 1 | Dr. Budi Santoso |
| 4 | 20070004 | Dewi Sartika | manager | 1 | Dr. Budi Santoso |
| 5 | 20080005 | Hendri Prasetyo | manager | 1 | Dr. Budi Santoso |
| 6 | 20120006 | Rina Marlina | atasan | 2 | Siti Rahmawati |
| 7 | 20130007 | Fadli Rahman | atasan | 3 | Ahmad Yani |
| 8 | 20140008 | Maya Sari | atasan | 2 | Siti Rahmawati |
| 9 | 20150009 | Dedi Kurniawan | atasan | 5 | Hendri Prasetyo |
| 10 | 20180010 | Lusi Handayani | pekerja | 7 | Fadli Rahman |
| 11 | 20180011 | Eko Prasetyo | pekerja | 4 | Dewi Sartika |
| 12 | 20190012 | Putri Utami | pekerja | 2 | Siti Rahmawati |
| 13 | 20190013 | Rudi Hartono | pekerja | 9 | Dedi Kurniawan |
| 14 | 20210014 | Sari Dewi ⭐ | pekerja | 8 | Maya Sari |
| 15 | 20210015 | Andi Wijaya | pekerja | 9 | Dedi Kurniawan |
| 16 | 20220016 | Nia Kurnia | pekerja | 8 | Maya Sari |
| 17 | 20220017 | Budi Setiawan | pekerja | 6 | Rina Marlina |
| 18 | 20230018 | Fitri Ramadhani | pekerja | 8 | Maya Sari |
| 19 | 20230019 | Rio Pratama | pekerja | 7 | Fadli Rahman |

---

## 🧪 SKENARIO TESTING

### **Skenario 1: Pengajuan dari Sari Dewi (20210014)**

```
STEP 1: Login sebagai Sari Dewi
- Username: 20210014
- Password: password123
- Role: pekerja

STEP 2: Buat Pengajuan Baru
- Menu: Ajukan Kenaikan Golongan
- Golongan Sekarang: I-C
- Golongan Tujuan: I-D
- Upload dokumen
- Submit → Status: PENDING

STEP 3: Login sebagai Maya Sari (Atasan Langsung)
- Username: 20140008
- Password: password123
- Role: atasan
- Menu: Pending Approval
- AKAN MUNCUL: Pengajuan dari Sari Dewi
- Action: Review → Approve
- Status berubah: DISETUJUI_ATASAN

STEP 4: Login sebagai Siti Rahmawati (Manager)
- Username: 20050002
- Password: password123
- Role: manager
- Menu: Pending Approval
- AKAN MUNCUL: Pengajuan dari Sari Dewi
- Action: Review → Approve
- Status berubah: DISETUJUI_MANAGER

STEP 5: Login sebagai Budi Santoso (Kepala Wilayah)
- Username: 19900001
- Password: password123
- Role: kepala_wilayah
- Menu: Pending Approval
- AKAN MUNCUL: Pengajuan dari Sari Dewi
- Action: Review → Approve
- Status berubah: DISETUJUI
- Golongan Sari Dewi otomatis update ke I-D
```

---

### **Skenario 2: Pengajuan dari Andi Wijaya (20210015)**

```
STEP 1: Login sebagai Andi Wijaya
- Username: 20210015
- Password: password123
- Role: pekerja

STEP 2: Buat Pengajuan
- Submit → Status: PENDING

STEP 3: Login sebagai Dedi Kurniawan (Atasan)
- Username: 20150009
- Password: password123
- Role: atasan
- AKAN MUNCUL: Pengajuan dari Andi Wijaya
- Action: Approve → Status: DISETUJUI_ATASAN

STEP 4: Login sebagai Manager (pilih salah satu):
   Option A: Hendri Prasetyo (20080005)
   Option B: Siti Rahmawati (20050002)
   Option C: Ahmad Yani (20060003)
   Option D: Dewi Sartika (20070004)
- Semua manager bisa melihat pengajuan ini
- Action: Approve → Status: DISETUJUI_MANAGER

STEP 5: Login sebagai Kepala Wilayah
- Username: 19900001
- Final Approve → Status: DISETUJUI
```

---

## 🔍 CARA CEK PENDING APPROVAL

### **Untuk Atasan (Supervisor)**
```sql
SELECT p.*, pk.nama_lengkap, pk.nip
FROM pengajuan p
JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
WHERE pk.id_atasan = [ID_PEKERJA_ATASAN]
  AND p.status = 'pending'
ORDER BY p.tanggal_pengajuan ASC;
```

**Contoh: Maya Sari (id_pekerja=8)**
- Akan melihat pengajuan dari: Sari Dewi, Nia Kurnia, Fitri Ramadhani
- Karena mereka semua punya `id_atasan = 8`

---

### **Untuk Manager**
```sql
SELECT p.*, pk.nama_lengkap, pk.nip
FROM pengajuan p
JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
WHERE p.status = 'disetujui_atasan'
ORDER BY p.tanggal_pengajuan ASC;
```

**Semua Manager bisa melihat:**
- Semua pengajuan dengan status `disetujui_atasan`
- Tidak terbatas pada divisi/bawahan tertentu

---

### **Untuk Kepala Wilayah**
```sql
SELECT p.*, pk.nama_lengkap, pk.nip
FROM pengajuan p
JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
WHERE p.status = 'disetujui_manager'
ORDER BY p.tanggal_pengajuan ASC;
```

**Kepala Wilayah bisa melihat:**
- Semua pengajuan dengan status `disetujui_manager`
- Ini adalah final approval

---

## 📝 CATATAN PENTING

### ⚠️ Troubleshooting

**Jika Atasan tidak melihat pengajuan bawahannya:**
1. Cek `id_atasan` di tabel `pekerja`
2. Pastikan `id_atasan` sama dengan `id_pekerja` atasan yang login
3. Status pengajuan harus `pending`

**Jika Manager tidak melihat pengajuan:**
1. Pastikan status pengajuan adalah `disetujui_atasan`
2. Semua manager bisa melihat pengajuan dengan status ini

**Jika Kepala Wilayah tidak melihat pengajuan:**
1. Pastikan status pengajuan adalah `disetujui_manager`

### ✅ Validasi Data

```sql
-- Cek relasi atasan-bawahan
SELECT 
    p1.nip, 
    p1.nama_lengkap as nama_pekerja,
    p2.nip as nip_atasan,
    p2.nama_lengkap as nama_atasan
FROM pekerja p1
LEFT JOIN pekerja p2 ON p1.id_atasan = p2.id_pekerja
ORDER BY p1.id_pekerja;

-- Cek pengajuan dan status
SELECT 
    p.nomor_pengajuan,
    pk.nama_lengkap,
    p.status,
    atasan.nama_lengkap as nama_atasan
FROM pengajuan p
JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
LEFT JOIN pekerja atasan ON pk.id_atasan = atasan.id_pekerja;
```

---

## 🎉 HAPPY TESTING!

Gunakan panduan ini untuk memahami alur approval dan melakukan testing sistem dengan benar. Setiap role memiliki fungsi dan akses yang berbeda sesuai hierarki organisasi.
