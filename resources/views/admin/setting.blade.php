@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">Manajemen Pengguna</h3>

        @if(session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    
    <!-- Table User -->
    <div class="card shadow-sm rounded">
      <div class="card-body">
        <div class="d-flex mb-3 justify-content-between">
        <!-- Tombol Tambah -->
        <button class="btn btn-success" data-toggle="modal" data-target="#createUserModal"> + Tambah User</button>

          <!-- Input Search Bar -->
        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama atau email..." style="max-width: 300px;">
        </div>

            <!-- Tabel Pengguna -->
            <table class="table table-bordered" id="userTable">
            <thead class="table-primary">
            <tr>
                <th scope="col" width="5%">No</th>
                <th scope="col" width="20%">Nama</th>
                <th scope="col">Email</th>
                <th scope="col" style="text-align: center;">Role</th>
                <th scope="col" style="text-align: center;">Status</th>
                <th scope="col" style="width:20%; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td style="text-align: center;">{{ ucfirst($user->role) }}</td>
                <td style="text-align: center;">{{ ucfirst($user->status) }}</td>
                <td class="text-center">
                   
                    <!-- Tombol Edit -->
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editUserModal{{ $user->id }}">Edit</button>

                     <!-- Tombol Detail -->
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailUserModal{{ $user->id }}">Detail</button>
                    
                    <!-- Tombol Hapus -->
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">Hapus</button>
                    </form>
                </td>
             </tr> 
             <!-- Modal Detail -->
            <div class="modal fade" id="detailUserModal{{ $user->id }}" tabindex="-1">
             <div class="modal-dialog">
                <div class="modal-content">
                 <div class="modal-header">
                    <h5 class="modal-title">Detail Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                    <p><strong>Di-input:</strong> {{ $user->created_at->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}</p>
                    <p><strong>Diubah:</strong> {{ $user->updated_at->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
                </div>
            </div>
            </div>

            <!-- Modal Edit -->
            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form action="{{ route('users.update', $user) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Pengguna</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                      </div>
                      <div class="form-group">
                        <label>Password (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                          <option value="admin" {{ $user->role=='admin' ? 'selected' : '' }}>Admin</option>
                          <option value="karyawan" {{ $user->role=='karyawan' ? 'selected' : '' }}>Karyawan</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                          <option value="aktif" {{ $user->status=='aktif' ? 'selected' : '' }}>Aktif</option>
                          <option value="nonaktif" {{ $user->status=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Update</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
      <div class="modal-dialog">
        <form action="{{ route('users.store') }}" method="POST">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Pengguna</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                  <option value="admin">Admin</option>
                  <option value="karyawan">Karyawan</option>
                </select>
              </div>
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Nonaktif</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Simpan</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>
</div>
</div>
@endsection

@section('js')

<!-- Alert -->
<script>
$(document).ready(function() {
    $("#success-alert").delay(3000).slideUp(500, function(){
        $(this).alert('close');
    });
});
</script>

<!-- Search -->
<script>
$(document).ready(function() {
    $("#success-alert").delay(3000).slideUp(500, function(){
        $(this).alert('close');
    });

    // Live search
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#userTable tbody tr").filter(function() {
            $(this).toggle(
                $(this).text().toLowerCase().indexOf(value) > -1
            );
        });
    });
});
</script>
@endsection