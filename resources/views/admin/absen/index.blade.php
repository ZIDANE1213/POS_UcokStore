@extends('layouts.admin')
@section('content')
<div class="container">
    <button class="btn btn-primary mb-3" onclick="tambahData()">Tambah Absensi</button>
    <table id="absen-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
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
          <h5 class="modal-title">Form Absensi</h5>
        </div>
        <div class="modal-body">
          <select name="status_masuk" id="status_masuk" class="form-control">
            <option value="masuk">Masuk</option>
            <option value="sakit">Sakit</option>
            <option value="cuti">Cuti</option>
          </select>
        </div>
        <div class="modal-footer">
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
    $('#status_masuk').val('masuk');
    $('#absenModal').modal('show');
}

function editData(id) {
    $.get('/api/absen/' + id, function (data) {
        $('#absen_id').val(data.id);
        $('#status_masuk').val(data.status_masuk);
        $('#absenModal').modal('show');
    });
}

function hapusData(id) {
    if (confirm("Yakin ingin menghapus data ini?")) {
        $.post('{{ route("absen.delete") }}', {
            _token: '{{ csrf_token() }}',
            id
        }, function () {
            $('#absen-table').DataTable().ajax.reload();
        });
    }
}

$(document).ready(function () {
    const table = $('#absen-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("absen.index") }}',
        columns: [
            { data: 'user.name' },
            { 
                data: 'status_masuk',
                render: function (data, type, row) {
                    return `<select class="form-control status-select" data-id="${row.id}">
                        <option value="masuk" ${data === 'masuk' ? 'selected' : ''}>Masuk</option>
                        <option value="sakit" ${data === 'sakit' ? 'selected' : ''}>Sakit</option>
                        <option value="cuti" ${data === 'cuti' ? 'selected' : ''}>Cuti</option>
                    </select>`;
                }
            },
            { data: 'waktu_mulai_kerja' },
            { 
                data: 'waktu_akhir_kerja',
                render: function (data, type, row) {
                    return data + ` <button class="btn btn-success btn-sm selesai-btn" data-id="${row.id}">Selesai</button>`;
                }
            },
            { data: 'aksi', name: 'aksi', orderable: false }
        ]
    });

    $(document).on('change', '.status-select', function () {
        const id = $(this).data('id');
        const status = $(this).val();
        $.post('{{ route("absen.ubahStatus") }}', {
            _token: '{{ csrf_token() }}',
            id, status
        });
    });

    $(document).on('click', '.selesai-btn', function () {
        const id = $(this).data('id');
        $.post('{{ route("absen.selesaiKerja") }}', {
            _token: '{{ csrf_token() }}',
            id
        }, function () {
            table.ajax.reload();
        });
    });

    $('#absenForm').submit(function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const url = $('#absen_id').val() ? '{{ route("absen.update") }}' : '{{ route("absen.store") }}';
        $.post(url, formData, function () {
            $('#absenModal').modal('hide');
            table.ajax.reload();
        });
    });
});
</script>
@endpush
