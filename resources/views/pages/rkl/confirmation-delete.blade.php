{{-- 
    File ini berisi modal Bootstrap yang akan muncul saat tombol hapus diklik.
    ID modal dibuat dinamis menggunakan ID dari $rkl agar setiap baris data
    memiliki modal konfirmasinya sendiri yang unik.
--}}
<div class="modal fade" id="confirmationDelete-{{ $rkl->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $rkl->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel-{{ $rkl->id }}">Konfirmasi Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data RKL:</p>
                <p><strong>{{ $rkl->name }} ({{ $rkl->code }})</strong></p>
                <p>Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                
                {{-- Form ini akan dieksekusi saat tombol "Hapus" di dalam modal diklik --}}
                <form action="{{ route('lengkongsari.rkl.destroy', $rkl->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

