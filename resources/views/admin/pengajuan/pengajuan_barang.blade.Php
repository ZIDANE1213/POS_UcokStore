@foreach($pengajuan_barang as $pengajuan)
<tr>
    <td>{{ $pengajuan->nama_barang }}</td>
    <td>{{ $pengajuan->deskripsi }}</td>
    <td>
        @if($pengajuan->status == 'pending')
            <form action="{{ route('pengajuan.setuju', $pengajuan->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">Setuju</button>
            </form>

            <form action="{{ route('pengajuan.tolak', $pengajuan->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
            </form>
        @else
            <span class="badge {{ $pengajuan->status == 'disetujui' ? 'bg-success' : 'bg-danger' }}">
                {{ ucfirst($pengajuan->status) }}
            </span>
        @endif
    </td>
</tr>
@endforeach
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function (event) {
                event.preventDefault();
                let formAction = form.action;
                let buttonText = form.querySelector("button").innerText;
                let confirmText = buttonText === "Setuju" ? "Menyetujui" : "Menolak";

                Swal.fire({
                    title: `Apakah Anda yakin ingin ${confirmText} pengajuan ini?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: buttonText === "Setuju" ? "#28a745" : "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: `Ya, ${buttonText}`,
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
