<div class="modal fade" id="tableSizeModal" tabindex="-1" aria-labelledby="tableSizeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-800" id="tableSizeModalLabel">
                    <i class="ri-table-2 me-2 text-warning"></i>Ukuran Tabel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-600">Ke kanan (kolom)</label>
                    <input type="number" min="1" max="50" class="form-control shadow-none" id="table_cols_input" value="3">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-600">Ke bawah (baris)</label>
                    <input type="number" min="1" max="50" class="form-control shadow-none" id="table_rows_input" value="3">
                </div>
                <div class="small text-muted">Contoh: 4 kolom ke kanan x 6 baris ke bawah.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="table_modal_confirm_btn">Buat Tabel</button>
            </div>
        </div>
    </div>
</div>
