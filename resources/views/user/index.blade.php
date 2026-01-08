@extends('layouts.app')
@section('title', 'Data User')
@section('content')

<style>
.btn:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}
.btn .spinner-border {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
}
</style>

<!-- All Modals Section -->
@include('components.notification-modal')

<!-- Detail Modal -->
<div class="modal modal-blur fade" id="showUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-user-circle text-primary"></i>
                    Detail User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 bg-light">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary p-3 text-white">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1" id="detailName"></h6>
                                <span class="text-muted small" id="detailEmail"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Nama Lengkap</label>
                            <div class="detail-value">
                                <i class="fas fa-user text-primary me-1"></i>
                                <span id="detailNameCopy"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Role</label>
                            <div class="detail-value">
                                <span id="detailRoleBadge" class="status-badge"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="text-muted small mb-1">Email</label>
                            <div class="detail-value">
                                <i class="fas fa-envelope text-secondary me-1"></i>
                                <span id="detailEmailCopy"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .detail-item {
                        background: #fff;
                        border: 1px solid rgba(0,0,0,.125);
                        border-radius: .25rem;
                        padding: 1rem;
                    }
                    .detail-value {
                        font-weight: 500;
                        margin-top: .25rem;
                    }
                    .status-badge {
                        padding: 0.35rem 0.75rem;
                        border-radius: 50rem;
                        font-size: 0.875rem;
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                    }
                </style>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal modal-blur fade" id="editUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-user-edit text-warning"></i>
                    Edit User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <div class="alert alert-info d-flex align-items-center mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <small>Kosongkan password jika tidak ingin mengubahnya</small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_name" class="form-label">
                                    <i class="fas fa-user text-primary me-1"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_email" class="form-label">
                                    <i class="fas fa-envelope text-primary me-1"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_role" class="form-label">
                                    <i class="fas fa-user-tag text-primary me-1"></i>
                                    Role
                                </label>
                                <select class="form-select" id="edit_role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_password" class="form-label">
                                    <i class="fas fa-lock text-primary me-1"></i>
                                    Password Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_password" name="password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal modal-blur fade" id="deleteUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <span id="deleteUserName"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteUserForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dynamically Generated Modals -->
@foreach($users as $user)
    <!-- Show Modal -->
    <div class="modal modal-blur fade" id="showUserModal{{ $user->id }}" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Role</dt>
                        <dd class="col-sm-8">{{ $user->role }}</dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal modal-blur fade" id="editUserModal{{ $user->id }}" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name{{ $user->id }}" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email{{ $user->id }}" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="role{{ $user->id }}" class="form-label">Role</label>
                            <select class="form-select" id="role{{ $user->id }}" name="role" required>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password{{ $user->id }}" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password{{ $user->id }}" name="password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password{{ $user->id }}', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal modal-blur fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user "{{ $user->name }}"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Create Modal -->
<div class="modal modal-blur fade" id="createUserModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="createName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="createName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="createEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="createEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="createRole" class="form-label">Role</label>
                        <select class="form-select" id="createRole" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="createPassword" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="createPassword" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('createPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid">
    <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data User</h5>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fa fa-plus me-2"></i>Tambah User
            </button>
            @endif
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <form class="d-flex" method="GET" action="{{ route('user.index') }}">
                    <input name="q" class="form-control form-control-sm me-2" type="search" placeholder="Cari nama / email / role" value="{{ $q ?? '' }}">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Cari</button>
                </form>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $i + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Lihat Detail" onclick="showUserDetails('{{ $user->id }}', {{ json_encode($user) }})">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit User" onclick="editUser('{{ $user->id }}', {{ json_encode($user) }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus User" onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data user</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<!-- duplicate create modal removed (single create modal exists earlier) -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation to all form submits
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                // Save original button content
                const originalContent = submitBtn.innerHTML;
                
                // Add loading spinner and disable button
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`;
                
                // Reset button after 5 seconds if form hasn't submitted (error case)
                setTimeout(() => {
                    if (!form.classList.contains('submitted')) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                    }
                }, 5000);
            }
            form.classList.add('submitted');
        });
    });
    // Function to toggle password visibility
    window.togglePassword = function(inputId, btn) {
        const input = document.getElementById(inputId);
        if(!input) return;
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;

        // If button is provided, toggle its icon
        if(btn && btn.querySelector) {
            const icon = btn.querySelector('i');
            if(icon) icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }
    };
    // Initialize Modals
    const showModal = new bootstrap.Modal(document.getElementById('showUserModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'), {
        backdrop: 'static',
        keyboard: false
    });

    // Show User Details
    window.showUserDetails = function(id, data) {
        // Set header card content
        document.getElementById('detailName').textContent = data.name;
        document.getElementById('detailEmail').textContent = data.email;

        // Set detail cards content
        document.getElementById('detailNameCopy').textContent = data.name;
        document.getElementById('detailEmailCopy').textContent = data.email;
        
        // Set role badge
        const roleBadge = document.getElementById('detailRoleBadge');
        const roleClasses = {
            'admin': 'bg-primary text-white',
            'user': 'bg-success text-white'
        };
        roleBadge.className = 'status-badge ' + (roleClasses[data.role] || 'bg-secondary text-white');
        roleBadge.innerHTML = `<i class="fas fa-user-tag"></i> ${data.role}`;

        showModal.show();
    };

    // Edit User
    window.editUser = function(id, data) {
        const form = document.getElementById('editUserForm');
        form.action = `{{ route('user.index') }}/${id}`;
        
        form.querySelector('#edit_name').value = data.name;
        form.querySelector('#edit_email').value = data.email;
        form.querySelector('#edit_role').value = data.role;
        form.querySelector('#edit_password').value = '';
        
        editModal.show();
    };

    // Delete User
    window.deleteUser = function(id, name) {
        const form = document.getElementById('deleteUserForm');
        form.action = `{{ route('user.index') }}/${id}`;
        document.getElementById('deleteUserName').textContent = `"${name}"`;
        deleteModal.show();
    };

    // Handle form submissions (disable submit button)
    document.querySelectorAll('.modal form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;
        });
    });

    // Reset forms and buttons on modal hide
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
                form.classList.remove('submitted');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    // Reset button content based on form type
                    if (form.id === 'deleteUserForm') {
                        submitBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
                    } else if (form.id === 'editUserForm') {
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan Perubahan';
                    } else {
                        submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Simpan';
                    }
                }
            }
        });
    });
});
</script>
@endpush
@endsection
