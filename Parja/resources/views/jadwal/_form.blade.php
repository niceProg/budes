@php($record = $record ?? null)

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="urutan" class="form-label fw-bold">Urutan</label>
        <input type="number" min="0" class="form-control" id="urutan" name="urutan"
            placeholder="1" value="{{ old('urutan', $record->urutan ?? '') }}">
        <small class="text-secondary">Menentukan posisi fase di timeline (kecil = lebih dulu).</small>
    </div>
    <div class="col-md-3 mb-3">
        <label for="fase" class="form-label fw-bold">Label Fase</label>
        <input type="text" class="form-control" id="fase" name="fase"
            placeholder="Fase 1" value="{{ old('fase', $record->fase ?? '') }}" maxlength="50">
    </div>
    <div class="col-md-6 mb-3">
        <label for="judul" class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
        <input required type="text" class="form-control" id="judul" name="judul"
            placeholder="Pendaftaran Online" value="{{ old('judul', $record->judul ?? '') }}" maxlength="255">
    </div>
</div>

<div class="mb-3">
    <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
        placeholder="Penjelasan singkat fase ini">{{ old('deskripsi', $record->deskripsi ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="icon" class="form-label fw-bold">Ikon (Bootstrap Icons)</label>
        <input type="text" class="form-control" id="icon" name="icon" list="icon-options"
            placeholder="bi-calendar-event" value="{{ old('icon', $record->icon ?? 'bi-calendar-event') }}" maxlength="50">
        <datalist id="icon-options">
            <option value="bi-pencil-square">
            <option value="bi-file-earmark-check-fill">
            <option value="bi-megaphone-fill">
            <option value="bi-star-fill">
            <option value="bi-calendar-event">
            <option value="bi-people-fill">
            <option value="bi-trophy-fill">
        </datalist>
        <small class="text-secondary">Nama kelas dari <a href="https://icons.getbootstrap.com" target="_blank" rel="noopener">Bootstrap Icons</a>, mis. <code>bi-star-fill</code>.</small>
    </div>
    <div class="col-md-6 mb-3">
        <label for="tanggal_label" class="form-label fw-bold">Teks Tanggal</label>
        <input type="text" class="form-control" id="tanggal_label" name="tanggal_label"
            placeholder="Coming Soon! / 1–30 Juni 2026" value="{{ old('tanggal_label', $record->tanggal_label ?? '') }}" maxlength="100">
        <small class="text-secondary">Bebas teks — bisa "Coming Soon!" atau rentang tanggal.</small>
    </div>
</div>
