@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container">
    <h2>Manajemen User</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah User</button>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tim Kerja</th> {{-- Tambahan --}}
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->timKerja->nama_tim_kerja ?? '-' }}</td> {{-- Tambahan --}}
                <td>
                    <button class="btn btn-warning editUserBtn" 
                            data-id="{{ $user->id }}" 
                            data-name="{{ $user->name }}" 
                            data-username="{{ $user->username }}" 
                            data-email="{{ $user->email }}" 
                            data-role="{{ $user->role }}"
                            data-tim_kerja_id="{{ $user->tim_kerja_id }}" {{-- Tambahan untuk modal edit --}}
                            data-bs-toggle="modal" 
                            data-bs-target="#editUserModal">
                        Edit
                    </button>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="username">NIP (Username)</label>
                        <input type="text" name="username" class="form-control" required pattern="[0-9]{18}" title="Masukkan 18 digit angka NIP" maxlength="18">
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="role">Role</label>
                        <select name="role" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tim_kerja_id">Tim Kerja</label>
                        <select name="tim_kerja_id" class="form-control">
                            <option value="">-- Pilih Tim Kerja --</option>
                            @foreach ($timkerja as $tk)
                                <option value="{{ $tk->id }}">{{ $tk->nama_tim_kerja }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="id">

                    <div class="mb-3">
                        <label for="editName">Nama</label>
                        <input type="text" id="editName" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="editUsername">NIP (Username)</label>
                        <input type="text" id="editUsername" name="username" class="form-control" required pattern="[0-9]{18}" maxlength="18" title="Masukkan 18 digit angka NIP">
                    </div>

                    <div class="mb-3">
                        <label for="editEmail">Email</label>
                        <input type="email" id="editEmail" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="editRole">Role</label>
                        <select id="editRole" name="role" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editTimKerja">Tim Kerja</label>
                        <select id="editTimKerja" name="tim_kerja_id" class="form-control">
                            <option value="">-- Pilih Tim Kerja --</option>
                            @foreach ($timkerja as $tk)
                                <option value="{{ $tk->id }}">{{ $tk->nama_tim_kerja }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editPassword">Password Baru (Opsional)</label>
                        <input type="password" id="editPassword" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="editPasswordConfirmation">Konfirmasi Password</label>
                        <input type="password" id="editPasswordConfirmation" name="password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.editUserBtn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('editUserId').value = this.dataset.id;
            document.getElementById('editName').value = this.dataset.name;
            document.getElementById('editUsername').value = this.dataset.username;
            document.getElementById('editTimKerja').value = this.dataset.tim_kerja_id;
            document.getElementById('editEmail').value = this.dataset.email;
            document.getElementById('editRole').value = this.dataset.role;

            let form = document.getElementById('editUserForm');
            form.action = '/users/' + this.dataset.id;
        });
    });
</script>
@endsection
