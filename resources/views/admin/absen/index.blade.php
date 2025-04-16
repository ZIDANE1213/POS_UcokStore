@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn btn-primary" onclick="tambahData()">Tambah Absensi</button>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('absen.exportExcel') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('absen.exportPdf') }}" class="btn btn-danger">Export PDF</a>
        </div>
    </div>
    <div class="mb-4">
       <form action="{{ route('absen.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Import</button>
</form>
        </form>
    </div>
    <table id="absen-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="absenModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="absenForm">
      @csrf
      
      <input type="hidden" name="id" id="absen_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Form Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="user_id">Pegawai</label>
            <select name="user_id" id="user_id" class="form-control" required>
              <option value="">Pilih Pegawai</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="status_masuk">Status</label>
            <select name="status_masuk" id="status_masuk" class="form-control" required>
              <option value="masuk">Masuk</option>
              <option value="sakit">Sakit</option>
              <option value="cuti">Cuti</option>
            </select>
          </div>
          <div class="form-group">
            <label for="tanggal_masuk">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function tambahData() {
    $('#absen_id').val('');
    $('#user_id').val('');
    $('#status_masuk').val('masuk');
    $('#tanggal_masuk').val('');
    $('#modalTitle').text('Tambah Absensi');
    $('#absenModal').modal('show');
}

function editData(id) {
    $.get('/api/absen/' + id, function (data) {
        $('#absen_id').val(data.id);
        $('#user_id').val(data.user_id);
        $('#status_masuk').val(data.status_masuk);
        $('#tanggal_masuk').val(data.tanggal_masuk);
        $('#modalTitle').text('Edit Absensi');
        $('#absenModal').modal('show');
    });
}

function hapusData(id) {
    if (confirm("Yakin ingin menghapus data ini?")) {
        $.ajax({
            url: '{{ route("absen.delete") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id
            },
            success: function() {
                $('#absen-table').DataTable().ajax.reload();
            }
        });
    }
}

$(document).ready(function () {
    const table = $('#absen-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("absen.index") }}',
        columns: [
            { data: 'user.name', name: 'user.name' },
            { data: 'tanggal_masuk', name: 'tanggal_masuk' },
            { 
                data: 'status_masuk',
                name: 'status_masuk',
                render: function (data, type, row) {
                    return `<select class="form-control status-select" data-id="${row.id}">
                        <option value="masuk" ${data === 'masuk' ? 'selected' : ''}>Masuk</option>
                        <option value="sakit" ${data === 'sakit' ? 'selected' : ''}>Sakit</option>
                        <option value="cuti" ${data === 'cuti' ? 'selected' : ''}>Cuti</option>
                    </select>`;
                }
            },
            { data: 'waktu_mulai_kerja', name: 'waktu_mulai_kerja' },
            { 
                data: 'waktu_akhir_kerja',
                name: 'waktu_akhir_kerja',
                render: function (data, type, row) {
                    if (row.status_masuk === 'masuk' && !data) {
                        return `<button class="btn btn-success btn-sm selesai-btn" data-id="${row.id}">Selesai</button>`;
                    }
                    return data || '-';
                }
            },
            { 
                data: 'id',
                name: 'aksi',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `<button class="btn btn-warning btn-sm edit-btn" onclick="editData(${data})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="hapusData(${data})">Hapus</button>`;
                }
            }
        ]
    });

    $(document).on('change', '.status-select', function () {
        const id = $(this).data('id');
        const status = $(this).val();
        
        $.ajax({
            url: '{{ route("absen.ubahStatus") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                status: status
            },
            success: function() {
                table.ajax.reload();
            }
        });
    });

    $(document).on('click', '.selesai-btn', function () {
        const id = $(this).data('id');
        
        $.ajax({
            url: '{{ route("absen.selesaiKerja") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id
            },
            success: function() {
                table.ajax.reload();
            }
        });
    });

    $('#absenForm').submit(function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const url = $('#absen_id').val() ? '{{ route("absen.update") }}' : '{{ route("absen.store") }}';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function() {
                $('#absenModal').modal('hide');
                table.ajax.reload();
            }
        });
    });
});
</script>
@endpush
