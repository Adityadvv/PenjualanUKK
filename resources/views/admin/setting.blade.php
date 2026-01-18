@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid pt-3">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="d-flex mb-3 justify-content-between align-items-center">
                <h5 class="mb-0">📋 Manajemen Pengguna</h5>
            </div>

            <!-- Tabel Pengguna --> 
            <div class="card shadow-sm rounded">
            <div class="card-body">
              <div class="mb-3 d-flex justify-content-between align-items-end">
              <button class="btn btn-success" data-toggle="modal" data-target="#createUserModal"> + Tambah User</button>

              <div class="d-flex justify-content-end align-items-center" style="gap: 10px;">
              <label for="searchInput" class="mb-0" style="white-space: nowrap;">Cari User:</label>
              <input type="text" id="searchInput" class="form-control" placeholder="Masukkan nama user..." style="max-width: 300px;">
            </div>
            </div>

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
                    <button class="btn btn-sm btn-warning" data-toggle="modal" style="color: white;"data-target="#editUserModal{{ $user->id }}">Edit</button>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailUserModal{{ $user->id }}">Detail</button>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger deleteBtn">Hapus</button>
                    </form>
                </td>
             </tr> 

             <!-- Modal Detail -->
            <div class="modal fade" id="detailUserModal{{ $user->id }}" tabindex="-1">
             <div class="modal-dialog">
                <div class="modal-content">
                 <div class="modal-header bg-info text-white">
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
                <form id="editUserForm{{ $user->id }}" action="{{ route('users.update', $user) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-content">
                    <div class="modal-header bg-warning">
                      <h5 class="modal-title text-white">Edit Pengguna</h5>
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
                      <button type="button" class="btn btn-info updateUserBtn" data-id="{{ $user->id }}">Update</button>
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
        <form id="userForm" action="{{ route('users.store') }}" method="POST">
          @csrf
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title">Tambah Pengguna</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Nama<span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Email<span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Password<span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Role<span class="text-danger">*</span></label>
                <select name="role" class="form-control" required>
                  <option value="admin">Admin</option>
                  <option value="karyawan">Karyawan</option>
                </select>
              </div>
              <div class="form-group">
                <label>Status<span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Nonaktif</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="submitUserBtn" class="btn btn-success">Simpan</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </form>
      </div>
    </div>
</div>
</div>
</div>
</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
//add user
document.getElementById('submitUserBtn').addEventListener('click', function () {
    var form = document.getElementById('userForm');

    // cek validasi
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // tutup modal 
        $(this).closest('.modal').modal('hide');

    // konfirmasi alert
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menyimpan user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (!result.isConfirmed) return;

        // success alert
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'User berhasil ditambahkan',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            form.submit(); // submit form setelah sweatalert
        });
    });
});

// Edit
$(document).on('click', '.updateUserBtn', function(){
    let id = $(this).data('id');
    let form = document.getElementById('editUserForm' + id);

    if(!form.checkValidity()){ 
        form.reportValidity(); 
        return; 
    }
        $(form).closest('.modal').modal('hide');

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah data user ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Batal'
    }).then((result)=>{
        if(!result.isConfirmed) return;

        // Tampilkan alert sukses
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil diupdate',
            timer: 1200,
            showConfirmButton: false
        }).then(()=>{ 
            form.submit(); 
        });
    });
});

// Hapus
$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault(); 
    let form = $(this).closest('form');

    Swal.fire({
        title: 'Hapus Data User?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'

    }).then((result) => {

        // batal - stop
        if (!result.isConfirmed) return;

        // ya - success
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data berhasil dihapus',
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            form.submit(); //submit after success
        });
    });
 });

//alert
$(document).ready(function() {
    $("#success-alert").delay(3000).slideUp(500, function(){
        $(this).alert('close');
    });
});

//search
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