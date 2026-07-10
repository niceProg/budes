@php
    $isEdit = isset($data);
@endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-dark: #0f172a;
        --accent-gold: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        --gold-solid: #b08d48;
        --gold-light: #fdfaf3;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-slate: #f8fafc;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(226, 232, 240, 0.9);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: var(--accent-gold);
    }

    .text-gold-solid {
        color: var(--gold-solid) !important;
    }

    .form-label {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.4rem;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 0.7rem 0.9rem;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--gold-solid);
        box-shadow: 0 0 0 0.2rem rgba(176, 141, 72, 0.18);
    }

    .btn.btn-primary {
        background: var(--accent-gold);
        border: none;
        border-radius: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0.65rem 1.4rem;
        transition: var(--transition);
    }

    .btn.btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(176, 141, 72, 0.35);
        color: #ffffff;
    }

    .btn.btn-light {
        border-radius: 12px;
        font-weight: 600;
    }

    /* Tiptap wrapper mengikuti gaya Quill di halaman lowongan */
    .tiptap-shell {
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .tiptap-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        padding: 0.4rem 0.6rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f9fafb, #fefce8);
    }

    .tiptap-editor {
        background-color: #ffffff;
    }

    /* Table inside editor: enable visible resize handle */
    #materi-editor table {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
    }
    #materi-editor th,
    #materi-editor td {
        border: 1px solid #e2e8f0;
        padding: 0.6rem 0.8rem;
        vertical-align: top;
        word-wrap: break-word;
    }
    #materi-editor .column-resize-handle {
        position: absolute;
        right: -2px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: rgba(124, 58, 237, 0.85);
        pointer-events: auto;
    }
    #materi-editor .resize-cursor {
        cursor: col-resize !important;
    }

    /* Image align */
    #materi-editor img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    #materi-editor img.tiptap-image-align-left {
        margin: 0.5rem auto 0.75rem 0;
    }
    #materi-editor img.tiptap-image-align-center {
        margin: 0.75rem auto;
    }
    #materi-editor img.tiptap-image-align-right {
        margin: 0.5rem 0 0.75rem auto;
    }

    #materi-editor {
        min-height: 340px;
        padding: 1rem 1.1rem;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--text-main);
    }

    /* Code block (Tiptap StarterKit) */
    #materi-editor pre {
        background: #0b1220;
        color: #e5e7eb;
        border: 1px solid #1f2937;
        border-radius: 14px;
        padding: 0.9rem 1rem;
        overflow-x: auto;
        white-space: pre;
        line-height: 1.5;
        margin: 0.85rem 0;
    }
    #materi-editor pre code {
        background: transparent;
        color: inherit;
        padding: 0;
        border-radius: 0;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.9rem;
    }
    /* Inline code */
    #materi-editor :not(pre) > code {
        background: rgba(148, 163, 184, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.28);
        padding: 0.12rem 0.35rem;
        border-radius: 8px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.92em;
    }

    /* YouTube embed (center + responsive) */
    #materi-editor [data-youtube-video] {
        display: flex;
        justify-content: center;
        margin: 0.85rem 0;
    }
    #materi-editor [data-youtube-video] iframe {
        width: min(100%, 860px);
        aspect-ratio: 16 / 9;
        height: auto;
        border: 0;
        border-radius: 14px;
    }

    .tiptap-btn {
        border-radius: 8px;
        border: 1px solid transparent;
        background: #ffffff;
        padding: 0.25rem 0.55rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: var(--transition);
    }

    .tiptap-btn:hover {
        background: #f1f5f9;
        color: var(--primary-dark);
        border-color: #e2e8f0;
    }

    .tiptap-btn.active {
        background: var(--gold-light);
        color: var(--gold-solid);
        border-color: rgba(176, 141, 72, 0.6);
    }

    .tiptap-dropdown-menu {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    }

    .tiptap-table-grid {
        border-radius: 12px;
        padding: 0.4rem;
        background: #f9fafb;
    }

    .tiptap-table-grid-cell {
        border-radius: 4px;
    }

    .icon-choice-group .icon-choice-btn {
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: var(--text-muted);
        font-weight: 600;
        padding: 0.3rem 0.7rem;
        font-size: 0.78rem;
        transition: var(--transition);
    }

    .icon-choice-group .icon-choice-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: var(--primary-dark);
    }

    .icon-choice-group .icon-choice-btn.active {
        background: var(--gold-light);
        border-color: var(--gold-solid);
        color: var(--gold-solid);
        box-shadow: 0 6px 12px rgba(176, 141, 72, 0.22);
    }

    @media (max-width: 992px) {
        .modern-card {
            padding: 1.5rem;
        }
    }

    /* === Dark mode: konsisten dengan tema admin === */
    html[data-skin="dark"] .modern-card {
        background: #0f172a !important;
        border-color: rgba(251, 191, 36, 0.35) !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.55);
    }
    html[data-skin="dark"] .modern-card.bg-white {
        background: #0f172a !important;
    }
    html[data-skin="dark"] .form-label {
        color: #ffffff !important;
    }
    html[data-skin="dark"] .form-control,
    html[data-skin="dark"] .form-select {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .form-control::placeholder {
        color: #9ca3af !important;
    }
    html[data-skin="dark"] .form-select option {
        background: #1e293b !important;
        color: #ffffff !important;
    }

    /* Tiptap */
    html[data-skin="dark"] .tiptap-shell {
        background: #0f172a !important;
        border-color: #374151 !important;
        box-shadow: 0 12px 26px rgba(0,0,0,0.45);
    }
    html[data-skin="dark"] .tiptap-toolbar {
        background: #0b1220 !important;
        border-bottom-color: #374151 !important;
    }
    html[data-skin="dark"] .tiptap-editor,
    html[data-skin="dark"] #materi-editor {
        background: #0f172a !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #materi-editor table {
        background: #0b1220 !important;
    }
    html[data-skin="dark"] #materi-editor th,
    html[data-skin="dark"] #materi-editor td {
        border-color:rgb(69, 81, 100) !important;
        color: #ffffff !important;
        background: rgba(15, 23, 42, 0.85) !important;
    }
    html[data-skin="dark"] #materi-editor th {
        background: rgba(48, 62, 84, 0.9) !important;
    }
    html[data-skin="dark"] .tiptap-btn {
        background: #1e293b !important;
        color: #e5e7eb !important;
        border-color: transparent !important;
    }
    html[data-skin="dark"] .tiptap-btn:hover {
        background: #334155 !important;
        color: #ffffff !important;
        border-color: #475569 !important;
    }
    html[data-skin="dark"] .tiptap-btn.active {
        background: rgba(251, 191, 36, 0.15) !important;
        color: #fbbf24 !important;
        border-color: rgba(251, 191, 36, 0.45) !important;
    }

    /* Code block dark mode */
    html[data-skin="dark"] #materi-editor pre {
        background: #0b1220;
        border-color: #1f2937;
        color: #e5e7eb;
    }
    html[data-skin="dark"] #materi-editor :not(pre) > code {
        background: rgba(148, 163, 184, 0.16);
        border-color: rgba(148, 163, 184, 0.22);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .tiptap-dropdown-menu {
        background: #0f172a !important;
        border-color: #374151 !important;
        box-shadow: 0 16px 38px rgba(0,0,0,0.55);
    }
    html[data-skin="dark"] .tiptap-table-grid {
        background: #0b1220 !important;
    }

    /* Modal ukuran tabel (dark mode) */
    html[data-skin="dark"] #tableSizeModal .modal-content {
        background: #0f172a !important;
        border: 1px solid #374151 !important;
        color: #ffffff !important;
        box-shadow: 0 18px 45px rgba(0,0,0,0.6);
    }
    html[data-skin="dark"] #tableSizeModal .modal-header,
    html[data-skin="dark"] #tableSizeModal .modal-body,
    html[data-skin="dark"] #tableSizeModal .modal-footer {
        background: transparent !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #tableSizeModal .modal-title {
        color: #ffffff !important;
    }
    html[data-skin="dark"] #tableSizeModal label,
    html[data-skin="dark"] #tableSizeModal .form-label,
    html[data-skin="dark"] #tableSizeModal .modal-body,
    html[data-skin="dark"] #tableSizeModal .modal-footer,
    html[data-skin="dark"] #tableSizeModal .modal-header {
        color: #ffffff !important;
    }
    html[data-skin="dark"] #tableSizeModal .text-muted {
        color: #cbd5e1 !important;
    }
    html[data-skin="dark"] #tableSizeModal .btn-close {
        filter: invert(1) grayscale(1);
        opacity: 0.85;
    }
    html[data-skin="dark"] #tableSizeModal .btn-close:hover {
        opacity: 1;
    }

    /* Icon navbar picker */
    html[data-skin="dark"] .icon-choice-group .icon-choice-btn {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #e5e7eb !important;
    }
    html[data-skin="dark"] .icon-choice-group .icon-choice-btn:hover {
        background: #334155 !important;
        border-color: #475569 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] .icon-choice-group .icon-choice-btn.active {
        background: rgba(251, 191, 36, 0.15) !important;
        border-color: rgba(251, 191, 36, 0.55) !important;
        color: #fbbf24 !important;
        box-shadow: 0 8px 18px rgba(0,0,0,0.35);
    }
    html[data-skin="dark"] .icon-choice-group .icon-choice-btn.active i {
        color: #fbbf24 !important;
    }

    /* Tombol "Sembunyikan Pengaturan" (di create/edit) */
    html[data-skin="dark"] #materi_settings_toggle.btn.btn-light {
        background: #1e293b !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    html[data-skin="dark"] #materi_settings_toggle.btn.btn-light:hover {
        background: #0f172a !important;
        border-color: rgba(251, 191, 36, 0.55) !important;
        color: #fbbf24 !important;
    }
    html[data-skin="dark"] #materi_settings_toggle i {
        color: inherit !important;
    }
</style>

<div class="row g-4 mt-2">
    <div class="col-lg-8" id="materi_content_col">
        <div class="modern-card p-4 bg-white">
            <div class="d-flex align-items-center gap-2 mb-3">
                <h4 class="m-0 fw-800 text-gold-solid">Konten Materi</h4>
            </div>

            <div class="mb-3">
                <label class="form-label fw-700">Judul Materi <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control shadow-none"
                       value="{{ old('judul', $data->judul ?? '') }}" placeholder="Judul materi...">
                @error('judul') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <label class="form-label fw-700">Isi Materi <span class="text-danger">*</span></label>
            <div class="tiptap-shell">
                <div class="tiptap-toolbar">
                    <button class="tiptap-btn" type="button" data-tt="undo" title="Undo">↶</button>
                    <button class="tiptap-btn" type="button" data-tt="redo" title="Redo">↷</button>
                    <div class="vr"></div>
                    <div class="tiptap-dropdown">
                        <button class="tiptap-btn tiptap-dropdown-toggle" type="button" title="Insert Table" data-tt="table-insert">
                            <i class="ri-table-2"></i>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="tiptap-dropdown-menu tiptap-table-menu">
                            <div class="tiptap-table-size-label">0 x 0 Table</div>
                            <div class="tiptap-table-grid">
                                @for($r = 1; $r <= 12; $r++)
                                    @for($c = 1; $c <= 8; $c++)
                                        <button
                                            type="button"
                                            class="tiptap-table-grid-cell"
                                            data-tt="table-grid"
                                            data-rows="{{ $r }}"
                                            data-cols="{{ $c }}"
                                        ></button>
                                    @endfor
                                @endfor
                            </div>
                            <button class="tiptap-btn w-100 mt-2" type="button" data-tt="table-custom">
                                Tabel custom...
                            </button>
                        </div>
                    </div>
                    <div class="tiptap-dropdown">
                        <button class="tiptap-btn tiptap-dropdown-toggle" type="button">
                            <span>H</span>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="tiptap-dropdown-menu">
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="h1" title="Heading 1">Heading 1</button>
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="h2" title="Heading 2">Heading 2</button>
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="h3" title="Heading 3">Heading 3</button>
                            <button class="tiptap-btn w-100" type="button" data-tt="h4" title="Heading 4">Heading 4</button>
                        </div>
                    </div>
                    <div class="vr"></div>
                    <button class="tiptap-btn" type="button" data-tt="bold" title="Bold"><b>B</b></button>
                    <button class="tiptap-btn" type="button" data-tt="italic" title="Italic"><i>I</i></button>
                    <button class="tiptap-btn" type="button" data-tt="strike" title="Strikethrough"><s>S</s></button>
                    <button class="tiptap-btn" type="button" data-tt="underline" title="Underline"><u>U</u></button>
                    <div class="tiptap-dropdown">
                        <button class="tiptap-btn tiptap-dropdown-toggle" type="button" title="Highlight">
                            <i class="ri-mark-pen-line"></i>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="tiptap-dropdown-menu tiptap-highlight-menu">
                            <button class="tiptap-highlight-dot tiptap-highlight-green" type="button" data-tt="highlight-green"></button>
                            <button class="tiptap-highlight-dot tiptap-highlight-blue" type="button" data-tt="highlight-blue"></button>
                            <button class="tiptap-highlight-dot tiptap-highlight-red" type="button" data-tt="highlight-red"></button>
                            <button class="tiptap-highlight-dot tiptap-highlight-purple" type="button" data-tt="highlight-purple"></button>
                            <button class="tiptap-highlight-dot tiptap-highlight-yellow" type="button" data-tt="highlight-yellow"></button>
                            <span class="tiptap-highlight-separator"></span>
                            <button class="tiptap-highlight-clear" type="button" data-tt="highlight-clear">
                                <i class="ri-forbid-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="vr"></div>
                    <div class="tiptap-dropdown">
                        <button class="tiptap-btn tiptap-dropdown-toggle" type="button">
                            <i class="ri-list-check-2"></i>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="tiptap-dropdown-menu">
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="bullet" title="Bullet List">
                                <i class="ri-list-unordered"></i> Bullet list
                            </button>
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="ordered" title="Ordered List">
                                <i class="ri-list-ordered"></i> Numbered list
                            </button>
                            <button class="tiptap-btn w-100" type="button" data-tt="task" title="Task List">
                                <i class="ri-checkbox-multiple-line"></i> Task list
                            </button>
                        </div>
                    </div>
                    <button class="tiptap-btn" type="button" data-tt="quote" title="Blockquote">
                        <i class="ri-text-wrap"></i>
                    </button>
                    <button class="tiptap-btn" type="button" data-tt="code" title="Code Block">
                        <i class="ri-code-box-line"></i>
                    </button>
                    <div class="vr"></div>
                    <div class="tiptap-dropdown">
                        <button class="tiptap-btn tiptap-dropdown-toggle" type="button">
                            <i class="ri-align-left"></i>
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="tiptap-dropdown-menu">
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="align-left" title="Align Left">
                                <i class="ri-align-left"></i> Left
                            </button>
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="align-center" title="Align Center">
                                <i class="ri-align-center"></i> Center
                            </button>
                            <button class="tiptap-btn w-100 mb-1" type="button" data-tt="align-right" title="Align Right">
                                <i class="ri-align-right"></i> Right
                            </button>
                            <button class="tiptap-btn w-100" type="button" data-tt="align-justify" title="Justify">
                                <i class="ri-align-justify"></i> Justify
                            </button>
                        </div>
                    </div>
                    <div class="vr"></div>
                    <button class="tiptap-btn" type="button" data-tt="link" title="Link">
                        <i class="ri-link"></i>
                    </button>
                    <button class="tiptap-btn" type="button" data-tt="superscript" title="Superscript">x<sup>2</sup></button>
                    <button class="tiptap-btn" type="button" data-tt="subscript" title="Subscript">x<sub>2</sub></button>
                    <button class="tiptap-btn" type="button" data-tt="image" title="Upload Gambar">
                        <i class="ri-image-line"></i> Gambar
                    </button>
                    <button class="tiptap-btn" type="button" data-tt="youtube" title="Embed YouTube">
                        <i class="ri-youtube-line"></i> YouTube
                    </button>
                    <div class="vr"></div>
                    <button class="tiptap-btn" type="button" data-tt="clear" title="Clear Formatting">
                        <i class="ri-format-clear"></i>
                    </button>
                </div>

                <input id="materi_image_picker" type="file" accept="image/*" hidden>

                <textarea id="materi_content" name="isi" hidden>{{ old('isi', $data->isi ?? '') }}</textarea>

                <div class="tiptap-editor">
                    <div id="materi-editor"
                         data-upload-url="{{ route('materi.upload-image') }}"
                         data-csrf="{{ csrf_token() }}"></div>
                </div>
            </div>

            @error('isi') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-lg-4" id="materi_settings_col">
        <div class="collapse collapse-horizontal show ms-auto" id="materi_settings_collapse">
            <div class="modern-card p-4 mb-4 bg-white" style="width: 100%; min-width: 260px;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <h4 class="m-0 fw-800 text-gold-solid">Pengaturan</h4>
                </div>

                <div class="mb-3">
                <label class="form-label fw-700">Nama Menu <span class="text-danger">*</span></label>
                <input type="text" name="navbar_nama" class="form-control shadow-none"
                       value="{{ old('navbar_nama', $data->navbar_nama ?? '') }}" placeholder="Contoh: Materi 1">
                @error('navbar_nama') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

                <div class="mb-3">
                <label class="form-label fw-700 d-flex justify-content-between align-items-center">
                    <span>Icon Menu <span class="text-danger">*</span></span>
                    <span class="small text-muted">Pilih salah satu</span>
                </label>
                @php
                    $iconOptions = [
                        'ri-book-line',
                        'ri-file-list-line',
                        'ri-file-text-line',
                        'ri-community-line',
                        'ri-book-open-line',
                        'ri-article-line',
                        'ri-folder-3-line',
                        'ri-lightbulb-line',
                        'ri-government-line',
                    ];
                    $currentIcon = old('navbar_icon', $data->navbar_icon ?? $iconOptions[0]);
                @endphp
                <input type="hidden" name="navbar_icon" id="navbar_icon_input" value="{{ $currentIcon }}">
                <div class="d-flex flex-wrap gap-2 icon-choice-group">
                    @foreach($iconOptions as $icon)
                        <button type="button"
                                class="btn btn-sm icon-choice-btn {{ $currentIcon === $icon ? 'active' : '' }}"
                                data-icon="{{ $icon }}">
                            <i class="{{ $icon }} me-1"></i>
                            {{ Str::of($icon)->after('ri-')->before('-line')->replace('-', ' ')->title() }}
                        </button>
                    @endforeach
                </div>
                @error('navbar_icon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

                <div class="mb-3">
                <label class="form-label fw-700">Narasumber</label>
                <input type="text" name="narasumber" class="form-control shadow-none"
                       value="{{ old('narasumber', $data->narasumber ?? '') }}" placeholder="Nama narasumber...">
                @error('narasumber') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

                <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_publish" name="is_publish" value="1"
                           {{ old('is_publish', $data->is_publish ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label fw-700" for="is_publish">Publish materi ini</label>
                </div>
                <div class="small text-muted mt-1">
                    Jika dicentang, materi akan muncul di sidebar peserta (sesuai Satuan Kerja jika dipilih).
                </div>
            </div>

                <div class="mb-3">
                <label class="form-label fw-700">File Materi</label>
                <input type="file" name="file_materi" class="form-control shadow-none" data-max-size-mb="10">
                <div class="small text-muted mt-1">
                    Maksimal ukuran file adalah 10&nbsp;&nbsp;MB.
                </div>
                @if($isEdit && $data->file_materi)
                    <div class="small mt-2">
                        File saat ini: <a href="{{ route('materi.admin.download', [$data->id, 'file']) }}">Download</a>
                    </div>
                @endif
                @error('file_materi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

                <div class="mb-3">
                <label class="form-label fw-700">PDF Materi</label>
                <input type="file" name="pdf_materi" class="form-control shadow-none" accept="application/pdf" data-max-size-mb="1">
                <div class="small text-muted mt-1">
                    Maksimal ukuran file PDF adalah 1&nbsp;&nbsp;MB.
                </div>
                @if($isEdit && $data->pdf_materi)
                    <div class="small mt-2">
                        PDF saat ini:
                        <a href="{{ route('materi.admin.pdf.view', $data->id) }}" target="_blank">Lihat</a>
                        -
                        <a href="{{ route('materi.admin.download', [$data->id, 'pdf']) }}">Download</a>
                    </div>
                @endif
                @error('pdf_materi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-700">Satuan Kerja</label>
                <select name="id_satker" id="materi_satker_select" class="form-select shadow-none">
                    <option value="">Semua Satuan Kerja</option>
                    @foreach($satker as $uk)
                        <option value="{{ $uk->id }}" {{ (string)old('id_satker', $data->id_satker ?? '') === (string)$uk->id ? 'selected' : '' }}>
                            {{ $uk->nama }} ({{ $uk->kode }})
                        </option>
                    @endforeach
                </select>
                <div class="small text-muted mt-1">
                    Jika diisi, materi hanya tampil untuk peserta dengan satuan kerja tersebut.
                </div>
                @error('id_satker') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('materi.index') }}" class="btn btn-light w-100" style="border-radius: 12px;">Kembali</a>
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px;">
                        <i class="ri-save-3-line"></i> {{ $isEdit ? 'Simpan' : 'Tambahkan' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Modal ukuran tabel custom -->
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

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix untuk z-index & positioning modal (sama seperti pegawai-internal & admin-absensi)
            document.addEventListener('show.bs.modal', function(event) {
                const modalEl = event.target;
                if (modalEl && modalEl.parentElement !== document.body) {
                    document.body.appendChild(modalEl);
                }
            });

            // Icon picker behavior
            const iconInput = document.getElementById('navbar_icon_input');
            const iconButtons = document.querySelectorAll('.icon-choice-btn');
            if (iconInput && iconButtons.length) {
                iconButtons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const icon = this.getAttribute('data-icon') || '';
                        iconInput.value = icon;
                        iconButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                    });
                });
            }

            // Toggle text & icon untuk collapse Pengaturan
            const settingsBody = document.getElementById('materi_settings_collapse');
            const contentCol = document.getElementById('materi_content_col');
            const toggleBtn = document.getElementById('materi_settings_toggle');
            const toggleText = document.getElementById('materi_settings_toggle_text');
            const toggleIcon = document.getElementById('materi_settings_toggle_icon');

            if (settingsBody && toggleBtn && toggleText && toggleIcon && contentCol && window.bootstrap) {
                settingsBody.addEventListener('shown.bs.collapse', function () {
                    toggleText.textContent = 'Sembunyikan Pengaturan';
                    toggleIcon.classList.remove('ri-arrow-down-s-line');
                    toggleIcon.classList.add('ri-arrow-up-s-line');
                    contentCol.classList.remove('col-lg-12');
                    contentCol.classList.add('col-lg-8');
                });

                settingsBody.addEventListener('hidden.bs.collapse', function () {
                    toggleText.textContent = 'Tampilkan Pengaturan';
                    toggleIcon.classList.remove('ri-arrow-up-s-line');
                    toggleIcon.classList.add('ri-arrow-down-s-line');
                    contentCol.classList.remove('col-lg-8');
                    contentCol.classList.add('col-lg-12');
                });
            }

            // Pencarian Satuan Kerja dengan Select2 (sama seperti di Materi)
            if (window.jQuery && $('#materi_satker_select').length && $.fn.select2) {
                $('#materi_satker_select').select2({
                    placeholder: '-- semua satuan kerja --',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Validasi ukuran file front-end untuk semua input file materi
            const fileInputs = document.querySelectorAll('input[type="file"][data-max-size-mb]');
            fileInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;
                    const maxMb = parseFloat(this.getAttribute('data-max-size-mb') || '0');
                    if (!maxMb) return;
                    const file = this.files[0];
                    const sizeMb = file.size / 1024 / 1024;
                    if (sizeMb > maxMb) {
                        const message = 'Ukuran file maksimal ' + maxMb + ' MB. File ini: ' + sizeMb.toFixed(2) + ' MB.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'File terlalu besar',
                                text: message
                            });
                        } else {
                            alert(message);
                        }
                        this.value = '';
                    }
                });
            });
        });
    </script>
@endpush
