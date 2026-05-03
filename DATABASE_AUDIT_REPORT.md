# Database Schema & Query Audit Report

## Database Structure (from schema)

### Tables & Key Columns:
1. **golongan_jabatan** - `id_golongan`, kode_golongan, nama_golongan
2. **divisi** - `id_divisi`, kode_divisi, nama_divisi
3. **jabatan** - `id_jabatan`, kode_jabatan, nama_jabatan, id_golongan_minimal
4. **pekerja** - `id_pekerja`, nip, nama_lengkap, id_divisi, id_jabatan, **id_golongan_saat_ini**, **id_atasan**
5. **users** - `id_user`, username, role, **id_pekerja**
6. **pengajuan** - `id_pengajuan`, **id_pekerja**, **id_golongan_saat_ini**, **id_golongan_diajukan**, status
7. **dokumen_pengajuan** - `id_dokumen`, **id_pengajuan**, file_path
8. **approval_history** - `id_approval`, **id_pengajuan**, **id_approver** (FK to users.id_user), level_approval

## Fixed Issues:

### ✅ Issue #1: ApprovalHistory.php - FIXED
**Problem**: Query used `ah.id_user` but column name is `ah.id_approver`
**Also Had**: Typo `pekerjda` instead of `pekerja`
```sql
-- BEFORE (WRONG):
LEFT JOIN users u ON ah.id_user = u.id_user
LEFT JOIN pekerjda p ON u.id_pekerja = p.id_pekerja

-- AFTER (FIXED):
LEFT JOIN users u ON ah.id_approver = u.id_user
LEFT JOIN pekerja p ON u.id_pekerja = p.id_pekerja
```

### ✅ Issue #2: Pengajuan.php getWithDetails() - FIXED
**Problem**: Missing `p.id_atasan` for access control check
```sql
-- ADDED:
p.id_atasan  -- Now includes atasan ID for proper access control
```

### ✅ Issue #3: PengajuanController.php detail() - FIXED
**Problem**: Only admin and owner could view, but approvers (atasan/manager/kepala_wilayah) need access too
```php
// FIXED: Now allows access for:
// 1. Admin (full access)
// 2. Owner (id_pekerja matches)
// 3. Atasan (direct supervisor - id_atasan matches)
// 4. Manager & Kepala Wilayah (approval rights)
```

## All Query Mappings Verified:

### ✅ Pengajuan Model
- getAllWithDetails() - ✓ Correct
- getByPekerja() - ✓ Correct
- getWithDetails() - ✓ Fixed (added id_atasan)
- getPendingForAtasan() - ✓ Correct (uses p.id_atasan)
- getPendingForManager() - ✓ Correct
- getPendingForKepalaWilayah() - ✓ Correct

### ✅ Pekerja Model
- getAllWithDetails() - ✓ Correct
- getWithDetails() - ✓ Correct
- getSubordinates() - ✓ Correct (uses id_atasan)

### ✅ ApprovalHistory Model
- getByPengajuan() - ✓ Fixed (id_approver & pekerja typo)

### ✅ User Model
- getByUsername() - ✓ Correct
- authenticate() - ✓ Correct

### ✅ DokumenPengajuan Model
- getByPengajuan() - ✓ Correct

## Controller Access Control Summary:

| Controller | Method | Required Role | Access Check |
|------------|--------|---------------|--------------|
| PengajuanController | index | logged_in | ✓ Admin sees all, others see own |
| PengajuanController | create | pekerja | ✓ Checks masa kerja & nilai kinerja |
| PengajuanController | detail | logged_in | ✓ Fixed - allows approvers |
| ApprovalController | index | atasan/manager/kepala | ✓ Role-based filtering |
| ApprovalController | review | atasan/manager/kepala | ✓ canReview() check |
| PekerjaController | * | admin | ✓ All methods admin-only |
| DivisiController | * | admin | ✓ All methods admin-only |
| GolonganController | * | admin | ✓ All methods admin-only |
| JabatanController | * | admin | ✓ All methods admin-only |
| LaporanController | * | admin | ✓ All methods admin-only |
| ProfilController | index | logged_in | ✓ Shows own profile |

## Notes on Data Discrepancy:

**User Question**: "Pengajuan detail shows I-A → I-B but master data shows III-B"

**Explanation**: This is EXPECTED behavior:
- **pengajuan table** stores a SNAPSHOT of golongan at submission time (`id_golongan_saat_ini`, `id_golongan_diajukan`)
- **pekerja table** stores the CURRENT golongan (`id_golongan_saat_ini`)

**Possible scenarios**:
1. Submission I-A → I-B was approved, so pekerja updated to I-B
2. Later submissions were approved, now pekerja is at III-B
3. Historical pengajuan still shows original I-A → I-B (as it should)

This is correct database design for audit trail purposes.

## All Fixes Applied:
1. ✅ ApprovalHistory: Fixed column name from `id_user` to `id_approver`
2. ✅ ApprovalHistory: Fixed typo from `pekerjda` to `pekerja`
3. ✅ Pengajuan getWithDetails(): Added `p.id_atasan` field
4. ✅ PengajuanController detail(): Enhanced access control for all approver roles

## Status: ALL DATABASE QUERIES & MAPPINGS VERIFIED ✓
