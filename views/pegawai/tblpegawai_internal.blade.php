@extends('layouts.app')

@section('title', 'Pegawai | Admin - SMART Setjen DPR RI')
@section('content')
@include('partials.select2_satker_assets')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('partials.table_skin_lamaran_css')
<style>
    .role-detail-info-box { background: #f8fafc; border: 1px solid #e2e8f0; }
    .role-detail-label { color: #64748b; }
    .role-detail-roles-box { max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; background: #f8fafc; }
</style>

<div class="content-wrapper">
    <div class="container-fluid px-md-5">
        <div class="page-title-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">Pegawai</li>
                    <li class="breadcrumb-item active">{{ $showInactive ? 'Pegawai Internal Tidak Aktif' : 'Pegawai Internal' }}</li>
                </ol>
            </nav>
            <h1 class="page-title">{{ $showInactive ? 'Pegawai Internal Tidak Aktif' : 'Pegawai Internal' }}</h1>
        </div>

        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-bottom: 1.5rem; border-radius: 12px;">
                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="modern-card">
            <div class="card-toolbar flex-wrap gap-4">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 5px; height: 25px; background: {{ $showInactive ? '#dc2626' : 'var(--gold-gradient)' }}; border-radius: 10px;"></div>
                    <h5 class="m-0 fw-800 text-dark">{{ $showInactive ? 'Daftar Pegawai Internal Tidak Aktif' : 'Daftar Pegawai Internal' }}</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if(!$showInactive)
                        <form method="POST" action="{{ route('pegawai.set-access-flag') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background: var(--gold-gradient); color: white; border: none; font-weight: 700; padding: 0.6rem 1.5rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;">
                                <i class="ri-add-line"></i> Tambah Pegawai
                            </button>
                        </form>
                        <button type="button" id="btnSyncPegawai" class="btn btn-sm" style="background: #0f172a; color: white; border: none; font-weight: 700; padding: 0.6rem 1.5rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;">
                            <i class="ri-refresh-line"></i> Sinkron Data
                        </button>
                    @endif
                    <form method="GET" action="{{ request()->url() }}" class="search-container" id="searchForm">
                        <div class="input-group">
                            <i class="ri-search-2-line" style="color: var(--accent-gold); font-size: 1.2rem;"></i>
                            <input type="text" name="search" id="search" class="form-control shadow-none"
                                   placeholder="Cari nama atau NIP..."
                                   value="{{ request('search', '') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['search','page'])) }}" class="text-muted ms-2"><i class="ri-close-circle-fill"></i></a>
                            @endif
                        </div>
                        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                        @if(request('role'))
                            @foreach(request('role') as $roleId)
                                <input type="hidden" name="role[]" value="{{ $roleId }}">
                            @endforeach
                        @endif
                        @if(request('satker'))
                            @foreach(request('satker') as $ukId)
                                <input type="hidden" name="satker[]" value="{{ $ukId }}">
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section d-flex flex-wrap align-items-center gap-3">
                <form method="GET" action="{{ request()->url() }}" id="filterForm" class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
                    <!-- Filter Role -->
                    <div class="dropdown filter-dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-user-settings-line"></i> Role
                            @if(request()->has('role') && count((array)request('role')) > 0)
                                <span class="badge filter-badge">{{ count((array)request('role')) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 200px; max-height: 300px; overflow-y: auto;">
                            <li class="mb-2">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="searchRole" 
                                       placeholder="Cari role..."
                                       style="border-radius: 8px; font-size: 0.8rem;"
                                       autocomplete="off">
                            </li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <div id="roleList">
                                @if($rolesInternal->count() > 0)
                                    @foreach($rolesInternal as $role)
                                        <li class="role-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="role[]" 
                                                       value="{{ $role->id }}" 
                                                       id="role_{{ $role->id }}"
                                                       {{ in_array($role->id, $filterRoles ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="role_{{ $role->id }}" style="cursor: pointer;">
                                                    {{ $role->nama }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li><span class="text-muted">Tidak ada role tersedia</span></li>
                                @endif
                            </div>
                        </ul>
                    </div>

                    <!-- Filter Satuan Kerja -->
                    <div class="dropdown filter-dropdown">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-building-line"></i> Satuan Kerja
                            @if(request()->has('satker') && count((array)request('satker')) > 0)
                                <span class="badge filter-badge">{{ count((array)request('satker')) }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 250px; max-height: 300px; overflow-y: auto;">
                            <li class="mb-2">
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="searchSatker" 
                                       placeholder="Cari kode/nama satuan kerja..."
                                       style="border-radius: 8px; font-size: 0.8rem;"
                                       autocomplete="off">
                            </li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <div id="satkerList">
                                @if($satker->count() > 0)
                                    @foreach($satker as $uk)
                                        <li class="unitkerja-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="satker[]" 
                                                       value="{{ $uk->id }}" 
                                                       id="unit_kerja_{{ $uk->id }}"
                                                       {{ in_array($uk->id, $filterSatker ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="unit_kerja_{{ $uk->id }}" style="cursor: pointer;">
                                                    {{ $uk->kode }} - {{ $uk->nama }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li><span class="text-muted">Tidak ada satuan kerja tersedia</span></li>
                                @endif
                            </div>
                        </ul>
                    </div>

                    <button type="submit" class="btn-filter-apply">
                        <i class="ri-filter-line"></i> Terapkan
                    </button>
                    @if(request()->has('role') || request()->has('satker'))
                        <a href="{{ request()->url() }}?{{ http_build_query(request()->except(['role', 'satker', 'page'])) }}" class="btn-filter-reset">
                            <i class="ri-close-line"></i> Reset
                        </a>
                    @endif
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                </form>
                <!-- Tombol Data Tidak Aktif (icon saja, di paling kanan filter section) -->
                <a href="{{ request()->url() }}?show_inactive={{ $showInactive ? '0' : '1' }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('per_page') ? '&per_page=' . request('per_page') : '' }}{{ request('role') ? '&' . http_build_query(['role' => request('role')]) : '' }}{{ request('satker') ? '&' . http_build_query(['satker' => request('satker')]) : '' }}" 
                   class="btn btn-sm" 
                   style="font-weight: 700; padding: 0.5rem; border-radius: 8px; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background-color: #dc2626 !important; border-color: #dc2626 !important; color: white !important;"
                   title="{{ $showInactive ? 'Kembali ke Data Aktif' : 'Lihat Data Tidak Aktif' }}">
                    <i class="ri-delete-bin-line" style="font-size: 1.2rem; color: white !important;"></i>
                </a>
            </div>

            <div class="px-4 py-3 d-flex align-items-center justify-content-between" style="background: rgba(248, 250, 252, 0.5);">
                <div class="text-muted fw-700" style="font-size: 0.85rem;">
                    Total Entri: <span class="text-dark">{{ $paginator ? $paginator->total() : (is_countable($rows) ? count($rows) : 0) }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2">
                        <span class="fw-700 text-muted" style="font-size: 0.8rem;">Baris:</span>
                        <select name="per_page" class="form-select form-select-sm border-0 fw-800 shadow-sm"
                                style="border-radius: 8px; width: 80px; cursor: pointer;"
                                onchange="this.form.submit()">
                            @foreach([10, 15, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="show_inactive" value="{{ $showInactive ? '1' : '0' }}">
                        @if(request('role'))
                            @foreach(request('role') as $roleId)
                                <input type="hidden" name="role[]" value="{{ $roleId }}">
                            @endforeach
                        @endif
                        @if(request('satker'))
                            @foreach(request('satker') as $ukId)
                                <input type="hidden" name="satker[]" value="{{ $ukId }}">
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>

            <div class="table-container table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Satuan Kerja</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            @php
                                $row = is_array($row) ? (object) $row : $row;
                                $no = ($paginator ? (($paginator->currentPage() - 1) * $paginator->perPage()) : 0) + $index + 1;
                                $statusText = ((int)($row->status ?? 0) === 1) ? 'Aktif' : 'Tidak Aktif';
                                $statusClass = ((int)($row->status ?? 0) === 1) ? 'status-active' : 'status-inactive';
                                $unitKode = $row->satker->nama_satker ?? ($row->satker->nama ?? ($row->id_satker ?? '-'));
                                $rowHasSuperadmin = $row->rolesInternal && $row->rolesInternal->contains(function($r) {
                                    return strtolower(trim((string) ($r->nama ?? ''))) === 'superadmin';
                                });
                                // 'rootadmin' = akses tertinggi tersembunyi: badge disembunyikan &
                                // baris tidak dapat dikelola dari UI (hanya dari database).
                                $rowHasRootadmin = $row->rolesInternal && $row->rolesInternal->contains(function($r) {
                                    return preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) ($r->nama ?? '')))) === 'rootadmin';
                                });
                                $visibleRoles = $row->rolesInternal ? $row->rolesInternal->filter(function($r) {
                                    return preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) ($r->nama ?? '')))) !== 'rootadmin';
                                }) : collect();
                                $canManageThisRow = !$rowHasRootadmin && ((!$rowHasSuperadmin) || ($canAssignSuperadmin ?? false));
                            @endphp
                            <tr>
                                <td>{{ $no }}</td>
                                <td class="fw-semibold">{{ $row->nip ?? '-' }}</td>
                                <td>{{ $row->nama ?? '-' }}</td>
                                <td>
                                    @if($visibleRoles->count() > 0)
                                        @foreach($visibleRoles as $role)
                                            <span class="badge bg-warning text-dark pegawai-badge me-1">{{ $role->nama }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $unitKode }}</td>
                                <td>
                                    <span class="badge pegawai-badge {{ $statusText == 'Aktif' ? 'bg-success' : 'bg-danger' }}">{{ $statusText }}</span>
                                </td>
                                <td class="text-center">
                                    @if($showInactive)
                                        {{-- Untuk data tidak aktif: tombol aktifkan kembali dan delete permanen --}}
                                        @if($canManageThisRow)
                                            <button type="button"
                                                    class="btn-action"
                                                    title="Aktifkan Kembali"
                                                    style="background-color: #10b981; border-color: #10b981; color: white;"
                                                    onclick="activateRole({{ (int)($row->id ?? 0) }}, '{{ $row->nip ?? '' }}', '{{ $row->nama ?? '' }}')">
                                                <i class="ri-checkbox-circle-line" style="color: white;"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn-action"
                                                    title="Hapus Permanen"
                                                    style="background-color: #dc2626 !important; border-color: #dc2626 !important; color: white !important;"
                                                    onclick="deletePermanent({{ (int)($row->id ?? 0) }}, '{{ $row->nip ?? '' }}', '{{ $row->nama ?? '' }}')">
                                                <i class="ri-delete-bin-7-line" style="color: white !important;"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="btn-action"
                                                    title="Role superadmin hanya dapat dikelola oleh admin by-email atau superadmin"
                                                    style="background-color: #cbd5e1; border-color: #cbd5e1; color: #475569; cursor: not-allowed;"
                                                    disabled>
                                                <i class="ri-lock-line"></i>
                                            </button>
                                        @endif
                                    @else
                                        {{-- Untuk data aktif: tombol detail (yang bisa edit juga) --}}
                                        @if($canManageThisRow)
                                            <button type="button"
                                                    class="btn-action"
                                                    title="Detail & Edit Role"
                                                    onclick="viewRoleDetails({{ (int)($row->id ?? 0) }}, '{{ $row->nip ?? '' }}', '{{ $row->nama ?? '' }}', true)">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="btn-action"
                                                    title="Role superadmin hanya dapat dikelola oleh admin by-email atau superadmin"
                                                    style="background-color: #cbd5e1; border-color: #cbd5e1; color: #475569; cursor: not-allowed;"
                                                    disabled>
                                                <i class="ri-lock-line"></i>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="text-muted mt-3 fw-700">Tidak ada data yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($paginator && $paginator->hasPages())
                <div class="footer-container">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                        <div class="text-muted fw-700" style="font-size: 0.85rem;">
                            Menampilkan <span class="text-dark">{{ $paginator->firstItem() ?? 0 }}</span> - <span class="text-dark">{{ $paginator->lastItem() ?? 0 }}</span>
                            dari <span class="text-dark">{{ $paginator->total() ?? 0 }}</span> Data
                        </div>
                        <div class="pagination-wrapper">
                            {{ $paginator->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal untuk Detail & Edit Role -->
<div class="modal fade" id="roleDetailModal" tabindex="-1" aria-labelledby="roleDetailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div class="modal-header" style="background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%); color: white; border: none; padding: 1.5rem;">
                <h5 class="modal-title fw-800" id="roleDetailModalLabel" style="font-size: 1.25rem;">
                    <i class="ri-user-settings-line me-2"></i>Detail & Edit Role Pegawai
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1;"></button>
            </div>
            <div class="modal-body" id="roleDetailContent" style="padding: 2rem;">
                <!-- Content akan diisi via JavaScript -->
            </div>
            <div class="modal-footer" id="roleDetailFooter" style="display: none; border-top: 1px solid #e2e8f0; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" id="btn-delete-role-modal" style="display: none;">
                    <i class="ri-delete-bin-line me-1"></i>Hapus Role
                </button>
                <button type="button" class="btn btn-primary" id="btn-save-role-modal">
                    <i class="ri-save-line me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const PAGE_CAN_ASSIGN_SUPERADMIN = @json((bool)($canAssignSuperadmin ?? false));
    const SUPERADMIN_ALLOWED_SATKER_IDS = @json(\Modules\Magang\App\Models\Smart\Satker::SUPERADMIN_ALLOWED_SATKER_IDS);
    const SUPERADMIN_MAX_LIMIT = @json(\Modules\Magang\App\Models\Smart\Satker::SUPERADMIN_MAX_ASSIGNMENTS);
    let assignRoleMeta = {
        can_assign_superadmin: false,
        superadmin_assigned_count: 0,
        superadmin_limit: SUPERADMIN_MAX_LIMIT,
        superadmin_limit_reached: false
    };

    function cleanupModalArtifacts() {
        setTimeout(() => {
            const hasOpenModal = document.querySelectorAll('.modal.show').length > 0;
            if (!hasOpenModal) {
                document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
                document.body.style.removeProperty('overflow');
            }
        }, 80);
    }

    function normalizeRoleName(name) {
        return String(name || '')
            .toLowerCase()
            .replace(/\s+/g, '')
            .trim();
    }

    function applySuperadminUnitRule(form, options = {}) {
        if (!form) return;
        const unitSelect = form.querySelector('select[name="id_satker"]');
        const selectedUnitId = parseInt(unitSelect?.value || '0', 10);
        const allowByUnit = SUPERADMIN_ALLOWED_SATKER_IDS.includes(selectedUnitId);
        const keepExistingSuperadmin = !!options.keepExistingSuperadmin;
        const canAssignSuperadmin = !!assignRoleMeta.can_assign_superadmin;

        const checkboxes = Array.from(form.querySelectorAll('input[name="roles_internal_ids[]"]'));
        const superadminCb = checkboxes.find(cb => normalizeRoleName(cb.dataset.roleName) === 'superadmin');
        if (!superadminCb) return;

        const superadminWrap = superadminCb.closest('.form-check');
        const hintId = 'superadmin-role-hint';
        let hintEl = form.querySelector('#' + hintId);
        if (!hintEl) {
            hintEl = document.createElement('small');
            hintEl.id = hintId;
            hintEl.className = 'd-block mt-2 text-muted';
            const roleBox = form.querySelector('.role-detail-roles-box') || form.querySelector('div[style*="max-height: 200px"]');
            if (roleBox) {
                roleBox.appendChild(hintEl);
            }
        }

        let shouldShow = canAssignSuperadmin && allowByUnit;
        if (assignRoleMeta.superadmin_limit_reached && !keepExistingSuperadmin && !superadminCb.checked) {
            shouldShow = false;
        }

        if (superadminWrap) {
            superadminWrap.style.display = shouldShow ? '' : 'none';
        }
        if (!shouldShow) {
            superadminCb.checked = false;
        }

        if (!allowByUnit) {
            hintEl.textContent = 'Role superadmin hanya tersedia untuk satuan kerja ID ' + SUPERADMIN_ALLOWED_SATKER_IDS.join(', ') + '.';
        } else if (assignRoleMeta.superadmin_limit_reached && !keepExistingSuperadmin) {
            hintEl.textContent = 'Role superadmin sudah mencapai batas maksimal (' + SUPERADMIN_MAX_LIMIT + ' pegawai).';
        } else {
            hintEl.textContent = '';
        }
    }

    function applyAdminSuperadminExclusiveRule(container) {
        if (!container) return;
        const checkboxes = Array.from(container.querySelectorAll('input[name="roles_internal_ids[]"]'));
        const adminCb = checkboxes.find(cb => normalizeRoleName(cb.dataset.roleName) === 'admin');
        const superadminCb = checkboxes.find(cb => normalizeRoleName(cb.dataset.roleName) === 'superadmin');
        if (!adminCb || !superadminCb) return;

        const onChange = (changed) => {
            if (!changed.checked) return;
            if (changed === adminCb) {
                superadminCb.checked = false;
            } else if (changed === superadminCb) {
                adminCb.checked = false;
            }
        };

        adminCb.addEventListener('change', () => onChange(adminCb));
        superadminCb.addEventListener('change', () => onChange(superadminCb));

        // Normalisasi awal bila data lama sempat menyimpan keduanya terpilih.
        if (adminCb.checked && superadminCb.checked) {
            superadminCb.checked = false;
        }
    }

    // Search functionality untuk filter dropdown (Server-side)
    let searchRoleTimeout;
    let searchSatkerTimeout;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Fix for Bootstrap Modals Z-Index (sama seperti admin-absensi dan external)
        document.addEventListener('show.bs.modal', function(event) {
            const modalEl = event.target;
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        });
        const roleDetailModalEl = document.getElementById('roleDetailModal');
        if (roleDetailModalEl) {
            roleDetailModalEl.addEventListener('hidden.bs.modal', function () {
                const ukSel = document.querySelector('#editRoleFormModal select[name="id_satker"]');
                if (ukSel && window.destroySelect2SatkerIfAny) {
                    destroySelect2SatkerIfAny(ukSel);
                }
                cleanupModalArtifacts();
            });
        }
        
        // Pastikan form mengirim semua checkbox yang tercentang saat submit
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                // Ambil semua checkbox yang tercentang (termasuk yang ada di dropdown yang tertutup)
                const checkedRoles = Array.from(document.querySelectorAll('input[name="role[]"]:checked')).map(cb => cb.value);
                const checkedUnitKerja = Array.from(document.querySelectorAll('input[name="satker[]"]:checked')).map(cb => cb.value);
                
                // Juga ambil dari sessionStorage sebagai backup
                const savedRoles = sessionStorage.getItem('selectedRoles');
                const savedUnitKerja = sessionStorage.getItem('selectedUnitKerja');
                const rolesFromStorage = savedRoles ? JSON.parse(savedRoles) : [];
                const unitKerjaFromStorage = savedUnitKerja ? JSON.parse(savedUnitKerja) : [];
                
                // Gabungkan dan hapus duplikat
                const allCheckedRoles = [...new Set([...checkedRoles, ...rolesFromStorage])];
                const allCheckedUnitKerja = [...new Set([...checkedUnitKerja, ...unitKerjaFromStorage])];
                
                // Simpan ke sessionStorage
                if (allCheckedRoles.length > 0) {
                    sessionStorage.setItem('selectedRoles', JSON.stringify(allCheckedRoles));
                } else {
                    sessionStorage.removeItem('selectedRoles');
                }
                if (allCheckedUnitKerja.length > 0) {
                    sessionStorage.setItem('selectedUnitKerja', JSON.stringify(allCheckedUnitKerja));
                } else {
                    sessionStorage.removeItem('selectedUnitKerja');
                }
                
                // Ambil search dan per_page dari form
                const search = document.querySelector('input[name="search"]')?.value || '';
                const perPage = document.querySelector('input[name="per_page"]')?.value || '15';
                
                console.log('Submitting filter:', {
                    roles: allCheckedRoles,
                    satker: allCheckedUnitKerja,
                    search: search,
                    per_page: perPage
                });
                
                // Build query string
                const params = new URLSearchParams();
                
                if (search) {
                    params.append('search', search);
                }
                params.append('per_page', perPage);
                
                allCheckedRoles.forEach(roleId => {
                    params.append('role[]', roleId);
                });
                
                allCheckedUnitKerja.forEach(ukId => {
                    params.append('satker[]', ukId);
                });
                
                // Redirect dengan query string
                const url = filterForm.action + (params.toString() ? '?' + params.toString() : '');
                window.location.href = url;
            });
        }
        // Search Role - Server-side
        const searchRoleInput = document.getElementById('searchRole');
        if (searchRoleInput) {
            searchRoleInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.trim();
                const roleList = document.getElementById('roleList');
                
                // Clear previous timeout
                clearTimeout(searchRoleTimeout);
                
                // Debounce: wait 300ms after user stops typing
                searchRoleTimeout = setTimeout(function() {
                    if (searchTerm.length === 0) {
                        // Load all roles if search is empty
                        loadRoles('');
                    } else {
                        loadRoles(searchTerm);
                    }
                }, 300);
            });
        }

        // Search Satuan Kerja - Server-side
        const searchSatkerInput = document.getElementById('searchSatker');
        if (searchSatkerInput) {
            searchSatkerInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.trim();
                const satkerList = document.getElementById('satkerList');
                
                // Clear previous timeout
                clearTimeout(searchSatkerTimeout);
                
                // Debounce: wait 300ms after user stops typing
                searchSatkerTimeout = setTimeout(function() {
                    if (searchTerm.length === 0) {
                        // Load all satuan kerja if search is empty
                        loadUnitKerja('');
                    } else {
                        loadUnitKerja(searchTerm);
                    }
                }, 300);
            });
        }

        // Pastikan label bisa diklik untuk toggle checkbox
        // Gunakan event delegation untuk menangani label yang di-render via AJAX
        document.addEventListener('click', function(e) {
            // Jika klik pada label atau teks di dalam label, toggle checkbox yang terkait
            const label = e.target.closest('label[for^="role_"], label[for^="unit_kerja_"]');
            if (label && !e.target.matches('input[type="checkbox"]')) {
                e.preventDefault();
                e.stopPropagation();
                const checkboxId = label.getAttribute('for');
                const checkbox = document.getElementById(checkboxId);
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    // Trigger change event untuk update sessionStorage
                    const changeEvent = new Event('change', { bubbles: true, cancelable: true });
                    checkbox.dispatchEvent(changeEvent);
                }
            }
        });
        
        // Simpan state checkbox ke sessionStorage setiap kali checkbox diubah
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[name="role[]"]')) {
                const checkedRoles = Array.from(document.querySelectorAll('input[name="role[]"]:checked')).map(cb => cb.value);
                if (checkedRoles.length > 0) {
                    sessionStorage.setItem('selectedRoles', JSON.stringify(checkedRoles));
                } else {
                    sessionStorage.removeItem('selectedRoles');
                }
            }
            if (e.target.matches('input[name="satker[]"]')) {
                const checkedUnitKerja = Array.from(document.querySelectorAll('input[name="satker[]"]:checked')).map(cb => cb.value);
                if (checkedUnitKerja.length > 0) {
                    sessionStorage.setItem('selectedUnitKerja', JSON.stringify(checkedUnitKerja));
                } else {
                    sessionStorage.removeItem('selectedUnitKerja');
                }
            }
        });
        
        // Simpan state checkbox yang tercentang sebelum dropdown ditutup
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            dropdown.addEventListener('hide.bs.dropdown', function() {
                // Simpan state checkbox yang tercentang ke sessionStorage sebelum dropdown ditutup
                const checkedRoles = Array.from(document.querySelectorAll('input[name="role[]"]:checked')).map(cb => cb.value);
                const checkedUnitKerja = Array.from(document.querySelectorAll('input[name="satker[]"]:checked')).map(cb => cb.value);
                
                if (checkedRoles.length > 0) {
                    sessionStorage.setItem('selectedRoles', JSON.stringify(checkedRoles));
                }
                if (checkedUnitKerja.length > 0) {
                    sessionStorage.setItem('selectedUnitKerja', JSON.stringify(checkedUnitKerja));
                }
            });
            
            dropdown.addEventListener('hidden.bs.dropdown', function() {
                const searchInputs = this.querySelectorAll('input[type="text"]');
                searchInputs.forEach(input => {
                    input.value = '';
                    // Reload all items dengan state yang tersimpan
                    if (input.id === 'searchRole') {
                        loadRoles('');
                    } else if (input.id === 'searchSatker') {
                        loadUnitKerja('');
                    }
                });
            });
        });
        
        // Load initial state dari URL params atau sessionStorage saat halaman dimuat
        const urlParams = new URLSearchParams(window.location.search);
        const rolesFromUrl = urlParams.getAll('role[]');
        const unitKerjaFromUrl = urlParams.getAll('satker[]');
        
        if (rolesFromUrl.length > 0) {
            sessionStorage.setItem('selectedRoles', JSON.stringify(rolesFromUrl));
        }
        if (unitKerjaFromUrl.length > 0) {
            sessionStorage.setItem('selectedUnitKerja', JSON.stringify(unitKerjaFromUrl));
        }
        
        // Set checkbox yang tercentang berdasarkan URL params atau sessionStorage
        const savedRoles = sessionStorage.getItem('selectedRoles');
        const savedUnitKerja = sessionStorage.getItem('selectedUnitKerja');
        const rolesToCheck = rolesFromUrl.length > 0 ? rolesFromUrl : (savedRoles ? JSON.parse(savedRoles) : []);
        const unitKerjaToCheck = unitKerjaFromUrl.length > 0 ? unitKerjaFromUrl : (savedUnitKerja ? JSON.parse(savedUnitKerja) : []);
        
        rolesToCheck.forEach(roleId => {
            const checkbox = document.querySelector(`input[name="role[]"][value="${roleId}"]`);
            if (checkbox) checkbox.checked = true;
        });
        
        unitKerjaToCheck.forEach(ukId => {
            const checkbox = document.querySelector(`input[name="satker[]"][value="${ukId}"]`);
            if (checkbox) checkbox.checked = true;
        });
    });

    // Function to load roles from server
    async function loadRoles(searchTerm) {
        const roleList = document.getElementById('roleList');
        // Ambil selected roles dari URL params (prioritas utama)
        const urlParams = new URLSearchParams(window.location.search);
        const selectedRolesFromUrl = urlParams.getAll('role[]');
        // Juga ambil dari checkbox yang tercentang (untuk backup)
        const selectedRolesFromCheckbox = Array.from(document.querySelectorAll('input[name="role[]"]:checked')).map(cb => cb.value);
        // Juga ambil dari sessionStorage
        const savedRoles = sessionStorage.getItem('selectedRoles');
        const selectedRolesFromStorage = savedRoles ? JSON.parse(savedRoles) : [];
        // Gabungkan semua sumber dan hapus duplikat
        const selectedRoles = [...new Set([...selectedRolesFromUrl, ...selectedRolesFromCheckbox, ...selectedRolesFromStorage])];
        
        try {
            roleList.innerHTML = '<li><span class="text-muted">Memuat...</span></li>';
            
            const url = new URL('{{ route("pegawai.search-roles") }}', window.location.origin);
            if (searchTerm) {
                url.searchParams.append('search', searchTerm);
            }
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                let html = '';
                data.data.forEach(role => {
                    const isChecked = selectedRoles.includes(role.id.toString()) ? 'checked' : '';
                    html += `
                        <li class="role-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="role[]" 
                                       value="${role.id}" 
                                       id="role_${role.id}"
                                       ${isChecked}>
                                <label class="form-check-label w-100" for="role_${role.id}" style="cursor: pointer;">
                                    ${role.nama}
                                </label>
                            </div>
                        </li>
                    `;
                });
                roleList.innerHTML = html;
            } else {
                roleList.innerHTML = '<li><span class="text-muted">Tidak ada role ditemukan</span></li>';
            }
        } catch (error) {
            console.error('Error loading roles:', error);
            roleList.innerHTML = '<li><span class="text-danger">Error memuat data</span></li>';
        }
    }

    // Function to load satuan kerja from server
    async function loadUnitKerja(searchTerm) {
        const satkerList = document.getElementById('satkerList');
        // Ambil selected satuan kerja dari URL params (prioritas utama)
        const urlParams = new URLSearchParams(window.location.search);
        const selectedUnitKerjaFromUrl = urlParams.getAll('satker[]');
        // Juga ambil dari checkbox yang tercentang (untuk backup)
        const selectedUnitKerjaFromCheckbox = Array.from(document.querySelectorAll('input[name="satker[]"]:checked')).map(cb => cb.value);
        // Juga ambil dari sessionStorage
        const savedUnitKerja = sessionStorage.getItem('selectedUnitKerja');
        const selectedUnitKerjaFromStorage = savedUnitKerja ? JSON.parse(savedUnitKerja) : [];
        // Gabungkan semua sumber dan hapus duplikat
        const selectedUnitKerja = [...new Set([...selectedUnitKerjaFromUrl, ...selectedUnitKerjaFromCheckbox, ...selectedUnitKerjaFromStorage])];
        
        try {
            satkerList.innerHTML = '<li><span class="text-muted">Memuat...</span></li>';
            
            const url = new URL('{{ route("pegawai.search-satker") }}', window.location.origin);
            if (searchTerm) {
                url.searchParams.append('search', searchTerm);
            }
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                let html = '';
                data.data.forEach(uk => {
                    const isChecked = selectedUnitKerja.includes(uk.id.toString()) ? 'checked' : '';
                    html += `
                        <li class="unitkerja-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="satker[]" 
                                       value="${uk.id}" 
                                       id="unit_kerja_${uk.id}"
                                       ${isChecked}>
                                <label class="form-check-label w-100" for="unit_kerja_${uk.id}" style="cursor: pointer;">
                                    ${(uk.kode_satker || uk.kode || '-')} - ${(uk.nama_satker || uk.nama || '-')}
                                </label>
                            </div>
                        </li>
                    `;
                });
                satkerList.innerHTML = html;
            } else {
                satkerList.innerHTML = '<li><span class="text-muted">Tidak ada satuan kerja ditemukan</span></li>';
            }
        } catch (error) {
            console.error('Error loading satuan kerja:', error);
            satkerList.innerHTML = '<li><span class="text-danger">Error memuat data</span></li>';
        }
    }

    async function viewRoleDetails(roleAsId, nip, nama, enableEdit = false) {
        if (!roleAsId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Data tidak valid'
            });
            return;
        }

        try {
            // Ambil data role_as
            const res = await fetch(`{{ url('admin/pegawai-admin/role') }}/${nip}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            
            if (!json || !json.success || !json.data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengambil data role'
                });
                return;
            }

            const data = json.data;
            
            // Ambil data untuk form (roles dan satuan kerja)
            const assignDataRes = await fetch('{{ route("pegawai.assign-role-data") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const assignData = await assignDataRes.json();
            
            if (!assignData.success || !assignData.data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengambil data role dan satuan kerja'
                });
                return;
            }
            assignRoleMeta = assignData.data.meta || assignRoleMeta;
            assignRoleMeta.can_assign_superadmin = !!(assignRoleMeta.can_assign_superadmin && PAGE_CAN_ASSIGN_SUPERADMIN);

            const roles = assignData.data.roles_internal || [];
            const unitKerja = assignData.data.satker || [];
            
            const currentUnitKerjaId = data.id_satker;
            const currentRoleIds = data.roles_internal ? data.roles_internal.map(r => r.id) : [];

            // Buat form HTML untuk edit
            let rolesHtml = roles.map(role => {
                if (normalizeRoleName(role.nama) === 'superadmin' && !assignRoleMeta.can_assign_superadmin) {
                    return '';
                }
                const checked = currentRoleIds.includes(role.id) ? 'checked' : '';
                return `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="roles_internal_ids[]" value="${role.id}" id="edit_role_${role.id}" data-role-name="${role.nama}" ${checked}>
                        <label class="form-check-label" for="edit_role_${role.id}" style="cursor: pointer;">${role.nama}</label>
                    </div>
                `;
            }).join('');

            let unitKerjaHtml = unitKerja.map(uk => {
                const selected = currentUnitKerjaId == uk.id ? 'selected' : '';
                return `<option value="${uk.id}" ${selected}>${typeof window.satkerLabel === 'function' ? window.satkerLabel(uk) : ((uk.nama_satker || uk.nama || '-') + ' (' + (uk.kode_satker || uk.kode || '-') + ')')}</option>`;
            }).join('');

            let html = `
                <form id="editRoleFormModal">
                    <div class="mb-4">
                        <h6 class="fw-700 text-dark mb-3" style="font-size: 1.1rem; color: #1e293b;">
                            <i class="ri-user-line me-2"></i>Informasi Pegawai
                        </h6>
                        <div class="p-3 rounded role-detail-info-box">
                            <p class="mb-2"><strong class="role-detail-label">NIP:</strong> <span class="text-dark">${nip}</span></p>
                            <p class="mb-0"><strong class="role-detail-label">Nama:</strong> <span class="text-dark fw-semibold">${nama}</span></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username: <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" value="${data.username || ''}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama: <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="${data.nama || nama}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Satuan Kerja: <span class="text-danger">*</span></label>
                        <select name="id_satker" class="form-select" required>
                            <option value="">-- Pilih Satuan Kerja --</option>
                            ${unitKerjaHtml}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Roles Internal: <span class="text-danger">*</span></label>
                        <div class="role-detail-roles-box">
                            ${rolesHtml}
                        </div>
                    </div>
                </form>
            `;

            const prevUkSel = document.querySelector('#editRoleFormModal select[name="id_satker"]');
            if (prevUkSel && window.destroySelect2SatkerIfAny) {
                destroySelect2SatkerIfAny(prevUkSel);
            }

            document.getElementById('roleDetailContent').innerHTML = html;
            const editRoleFormModal = document.getElementById('editRoleFormModal');
            applyAdminSuperadminExclusiveRule(editRoleFormModal);
            const existingHasSuperadmin = !!(data.roles_internal && data.roles_internal.some(r => normalizeRoleName(r.nama) === 'superadmin'));
            applySuperadminUnitRule(editRoleFormModal, { keepExistingSuperadmin: existingHasSuperadmin });
            const editUnitSelect = editRoleFormModal?.querySelector('select[name="id_satker"]');
            if (editUnitSelect) {
                editUnitSelect.addEventListener('change', () => {
                    applySuperadminUnitRule(editRoleFormModal, { keepExistingSuperadmin: existingHasSuperadmin });
                });
            }
            
            // Tampilkan footer dengan tombol simpan
            document.getElementById('roleDetailFooter').style.display = 'flex';
            document.getElementById('btn-delete-role-modal').style.display = 'inline-block';
            document.getElementById('btn-delete-role-modal').setAttribute('data-role-as-id', data.id);
            
            // Show modal
            const modalEl = document.getElementById('roleDetailModal');
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
            cleanupModalArtifacts();
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            setTimeout(function () {
                if (window.initSelect2Satker && window.jQuery) {
                    initSelect2Satker('#editRoleFormModal select[name="id_satker"]', {
                        dropdownParent: $('#roleDetailModal'),
                        placeholder: '-- Pilih Satuan Kerja --',
                        allowClear: true
                    });
                }
            }, 0);
            
            // Setup event listeners
            setupEditRoleListeners(roleAsId, data.id);
        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengambil data role'
            });
        }
    }

    function setupEditRoleListeners(roleAsId, roleAsDbId) {
        // Remove existing listeners
        const btnSave = document.getElementById('btn-save-role-modal');
        const btnDelete = document.getElementById('btn-delete-role-modal');
        
        // Clone elements to remove old listeners
        const newBtnSave = btnSave.cloneNode(true);
        const newBtnDelete = btnDelete.cloneNode(true);
        btnSave.parentNode.replaceChild(newBtnSave, btnSave);
        btnDelete.parentNode.replaceChild(newBtnDelete, btnDelete);
        
        // Save button
        newBtnSave.addEventListener('click', async function() {
            const form = document.getElementById('editRoleFormModal');
            if (!form) return;
            
            const selectedRoles = Array.from(form.querySelectorAll('input[name="roles_internal_ids[]"]:checked')).map(cb => parseInt(cb.value));
            const unitKerjaId = form.querySelector('select[name="id_satker"]').value;
            const username = form.querySelector('input[name="username"]').value.trim();
            const nama = form.querySelector('input[name="nama"]').value.trim();
            
            if (!username) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Username wajib diisi'
                });
                return;
            }
            if (!nama) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Nama wajib diisi'
                });
                return;
            }
            if (selectedRoles.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Pilih minimal 1 role'
                });
                return;
            }
            if (!unitKerjaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Pilih satuan kerja'
                });
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            
            try {
                const updateRes = await fetch(`{{ url('admin/pegawai-admin/role') }}/${roleAsDbId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        roles_internal_ids: selectedRoles,
                        id_satker: parseInt(unitKerjaId),
                        username: username,
                        nama: nama
                    })
                });

                const updateData = await updateRes.json();
                if (updateData.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('roleDetailModal'));
                    if (modal) modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: updateData.message || 'Role pegawai berhasil diperbarui.',
                        confirmButtonColor: '#10b981'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: updateData.message || 'Gagal memperbarui role pegawai.'
                    });
                    this.disabled = false;
                    this.innerHTML = '<i class="ri-save-line me-1"></i>Simpan';
                }
            } catch (e) {
                console.error(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan data'
                });
                this.disabled = false;
                this.innerHTML = '<i class="ri-save-line me-1"></i>Simpan';
            }
        });
        
        // Delete button
        newBtnDelete.addEventListener('click', async function() {
            const roleAsId = this.getAttribute('data-role-as-id');
            if (!roleAsId) return;
            
            const { isConfirmed } = await Swal.fire({
                title: 'Hapus Role?',
                html: 'Semua role internal akan dihapus dan status pegawai akan diubah menjadi tidak aktif.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Role',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f59e0b'
            });
            
            if (!isConfirmed) return;
            
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...';
            
            try {
                const deleteRes = await fetch(`{{ url('admin/pegawai-admin/role') }}/${roleAsId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const deleteData = await deleteRes.json();
                if (deleteData.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('roleDetailModal'));
                    if (modal) modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: deleteData.message || 'Role pegawai berhasil dihapus.',
                        confirmButtonColor: '#10b981'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: deleteData.message || 'Gagal menghapus role pegawai.'
                    });
                    this.disabled = false;
                    this.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Hapus Role';
                }
            } catch (e) {
                console.error(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus data'
                });
                this.disabled = false;
                this.innerHTML = '<i class="ri-delete-bin-line me-1"></i>Hapus Role';
            }
        });
    }

    async function detachRoleInternal(roleAsId, rolesInternalId, nip) {
        if (!roleAsId || !rolesInternalId) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Data tidak valid'
            });
            return;
        }

        const ok = confirm(`Cabut role ini dari pegawai (NIP: ${nip})?\nJika ini role terakhir, status pegawai akan diubah menjadi tidak aktif.`);
        if (!ok) return;

        try {
            const res = await fetch(`{{ url('admin/pegawai-admin/role') }}/${roleAsId}/detach/${rolesInternalId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            if (json && json.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: json.message || 'Berhasil mencabut role',
                    confirmButtonColor: '#10b981'
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: (json && json.message) ? json.message : 'Gagal mencabut role'
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mencabut role'
            });
        }
    }

    // Aktifkan kembali pegawai dengan menambah role
    async function activateRole(roleAsId, nip, nama) {
        if (!roleAsId || !nip) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Data tidak valid'
            });
            return;
        }

        // Ambil data untuk form assign role
        try {
            const assignDataRes = await fetch('{{ route("pegawai.assign-role-data") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const assignData = await assignDataRes.json();
            
            if (!assignData.success || !assignData.data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengambil data role dan satuan kerja'
                });
                return;
            }
            assignRoleMeta = assignData.data.meta || assignRoleMeta;
            assignRoleMeta.can_assign_superadmin = !!(assignRoleMeta.can_assign_superadmin && PAGE_CAN_ASSIGN_SUPERADMIN);

            const roles = assignData.data.roles_internal || [];
            const unitKerja = assignData.data.satker || [];

            // Ambil data role_as yang ada
            const roleAsRes = await fetch(`{{ url('admin/pegawai-admin/role') }}/${nip}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const roleAsData = await roleAsRes.json();
            
            let currentUnitKerjaId = null;
            let currentRoleIds = [];
            
            if (roleAsData.success && roleAsData.data) {
                currentUnitKerjaId = roleAsData.data.id_satker;
                currentRoleIds = roleAsData.data.roles_internal ? roleAsData.data.roles_internal.map(r => r.id) : [];
            }

            // Buat form HTML untuk memilih role
            let rolesHtml = roles.map(role => {
                if (normalizeRoleName(role.nama) === 'superadmin' && !assignRoleMeta.can_assign_superadmin) {
                    return '';
                }
                const checked = currentRoleIds.includes(role.id) ? 'checked' : '';
                return `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="roles_internal_ids[]" value="${role.id}" id="role_${role.id}" data-role-name="${role.nama}" ${checked}>
                        <label class="form-check-label" for="role_${role.id}">${role.nama}</label>
                    </div>
                `;
            }).join('');

            let unitKerjaHtml = unitKerja.map(uk => {
                const selected = currentUnitKerjaId == uk.id ? 'selected' : '';
                return `<option value="${uk.id}" ${selected}>${typeof window.satkerLabel === 'function' ? window.satkerLabel(uk) : ((uk.nama_satker || uk.nama || '-') + ' (' + (uk.kode_satker || uk.kode || '-') + ')')}</option>`;
            }).join('');

            const formHtml = `
                <form id="activateRoleForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Satuan Kerja:</label>
                        <select name="id_satker" class="form-select" required>
                            <option value="">-- Pilih Satuan Kerja --</option>
                            ${unitKerjaHtml}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Role:</label>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                            ${rolesHtml}
                        </div>
                    </div>
                </form>
            `;

            const { value: formValues } = await Swal.fire({
                title: 'Aktifkan Kembali Pegawai',
                html: formHtml,
                showCancelButton: true,
                confirmButtonText: 'Aktifkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                willClose: () => {
                    const sel = document.querySelector('#activateRoleForm select[name="id_satker"]');
                    if (sel && window.destroySelect2SatkerIfAny) {
                        destroySelect2SatkerIfAny(sel);
                    }
                },
                didOpen: () => {
                    // Pastikan form bisa diakses
                    const form = document.getElementById('activateRoleForm');
                    if (form) {
                        applyAdminSuperadminExclusiveRule(form);
                        const existingHasSuperadmin = currentRoleIds.some((id) => {
                            const role = roles.find((r) => r.id === id);
                            return normalizeRoleName(role?.nama) === 'superadmin';
                        });
                        applySuperadminUnitRule(form, { keepExistingSuperadmin: existingHasSuperadmin });
                        const unitSelect = form.querySelector('select[name="id_satker"]');
                        if (unitSelect) {
                            unitSelect.addEventListener('change', () => {
                                applySuperadminUnitRule(form, { keepExistingSuperadmin: existingHasSuperadmin });
                            });
                        }
                        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
                        if (checkboxes.length === 0) {
                            Swal.showValidationMessage('Tidak ada role yang tersedia');
                        }
                        if (window.initSelect2Satker && window.jQuery) {
                            initSelect2Satker('#activateRoleForm select[name="id_satker"]', {
                                dropdownParent: $(Swal.getPopup()),
                                placeholder: '-- Pilih Satuan Kerja --',
                                allowClear: true
                            });
                        }
                    }
                },
                preConfirm: () => {
                    const form = document.getElementById('activateRoleForm');
                    if (!form) return false;
                    
                    const selectedRoles = Array.from(form.querySelectorAll('input[name="roles_internal_ids[]"]:checked')).map(cb => parseInt(cb.value));
                    const unitKerjaId = form.querySelector('select[name="id_satker"]').value;
                    
                    if (selectedRoles.length === 0) {
                        Swal.showValidationMessage('Pilih minimal 1 role');
                        return false;
                    }
                    if (!unitKerjaId) {
                        Swal.showValidationMessage('Pilih satuan kerja');
                        return false;
                    }
                    
                    return {
                        roles_internal_ids: selectedRoles,
                        id_satker: parseInt(unitKerjaId)
                    };
                }
            });

            if (formValues) {
                // Ambil data role_as untuk update
                const updateRes = await fetch(`{{ url('admin/pegawai-admin/role') }}/${roleAsId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        roles_internal_ids: formValues.roles_internal_ids,
                        id_satker: formValues.id_satker,
                        username: roleAsData.data.username || '',
                        nama: roleAsData.data.nama || nama
                    })
                });

                const updateData = await updateRes.json();
                if (updateData.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Pegawai berhasil diaktifkan kembali.',
                        confirmButtonColor: '#10b981'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: updateData.message || 'Gagal mengaktifkan pegawai.'
                    });
                }
            }
        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengaktifkan pegawai'
            });
        }
    }

    // Delete permanen pegawai
    async function deletePermanent(roleAsId, nip, nama) {
        if (!roleAsId || !nip) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Data tidak valid'
            });
            return;
        }

        const { isConfirmed } = await Swal.fire({
            title: 'Hapus Permanen?',
            html: `Pegawai <strong>${nama}</strong> (NIP: ${nip}) akan dihapus permanen dari sistem.<br><br>Apakah Anda yakin?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626'
        });

        if (!isConfirmed) return;

        try {
            const res = await fetch(`{{ url('admin/pegawai-admin/role') }}/${roleAsId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const json = await res.json();
            if (json && json.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pegawai berhasil dihapus permanen.',
                    confirmButtonColor: '#10b981'
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: (json && json.message) ? json.message : 'Gagal menghapus pegawai.'
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menghapus pegawai'
            });
        }
    }

    // ========== Sinkron Data Pegawai ==========
    document.getElementById('btnSyncPegawai')?.addEventListener('click', async function() {
        const btn = this;
        const { isConfirmed } = await Swal.fire({
            icon: 'question',
            title: 'Sinkronisasi Data Pegawai',
            html: 'Mengambil data terbaru dari API pegawai dan memperbarui data lokal (satuan kerja, nama, eselon).<br><br>Lanjutkan?',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-refresh-line"></i> Ya, Sinkronkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0f172a',
        });

        if (!isConfirmed) return;

        // Show loading
        Swal.fire({
            title: 'Menyinkronkan data...',
            html: 'Mohon tunggu, sedang mengambil data dari API pegawai.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            btn.disabled = true;
            const response = await fetch('{{ route('pegawai.sync-data') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const json = await response.json();

            if (json.success) {
                const d = json.data || {};
                let detailHtml = '<div style="text-align:left; font-size:0.9rem; margin-top:1rem;">';
                detailHtml += `<p><strong>Total lokal:</strong> ${d.total_local ?? '-'} pegawai</p>`;
                detailHtml += `<p><strong>Total API:</strong> ${d.total_api ?? '-'} pegawai</p>`;
                detailHtml += `<p style="color:#059669;"><strong>Diperbarui:</strong> ${d.updated ?? 0}</p>`;
                detailHtml += `<p style="color:#64748b;"><strong>Tidak berubah:</strong> ${d.unchanged ?? 0}</p>`;
                detailHtml += `<p style="color:#b45309;"><strong>Tidak ditemukan di API:</strong> ${d.not_found ?? 0}</p>`;

                if (d.details && d.details.length > 0) {
                    detailHtml += '<hr><p><strong>Perubahan:</strong></p><ul style="padding-left:1.2rem;">';
                    d.details.forEach(function(item) {
                        let changeParts = [];
                        if (item.changes?.id_satker) {
                            changeParts.push(`satker: ${item.changes.id_satker.old ?? '-'} → ${item.changes.id_satker.new}`);
                        }
                        if (item.changes?.nama) {
                            changeParts.push(`nama: "${item.changes.nama.old}" → "${item.changes.nama.new}"`);
                        }
                        if (item.changes?.eselon) {
                            changeParts.push(`eselon: ${item.changes.eselon.old ?? '-'} → ${item.changes.eselon.new}`);
                        }
                        detailHtml += `<li><strong>${item.nip}</strong> (${item.nama ?? '-'}) — ${changeParts.join(', ')}</li>`;
                    });
                    detailHtml += '</ul>';
                }
                detailHtml += '</div>';

                Swal.fire({
                    icon: d.updated > 0 ? 'success' : 'info',
                    title: d.updated > 0 ? 'Sinkronisasi Berhasil!' : 'Data Sudah Terkini',
                    html: json.message + detailHtml,
                    confirmButtonText: 'OK',
                    width: 500,
                }).then(() => {
                    if (d.updated > 0) {
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Sinkronisasi Gagal',
                    text: json.message || 'Gagal menyinkronkan data.'
                });
            }
        } catch (e) {
            console.error(e);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyinkronkan data.'
            });
        } finally {
            btn.disabled = false;
        }
    });

</script>
@endsection
