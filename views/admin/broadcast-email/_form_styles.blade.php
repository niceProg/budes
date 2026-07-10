<style>

    :root {
        --b-gold: #b08d48;
        --b-slate: #334155;
        --b-muted: #64748b;
        --b-border: #e2e8f0;
        --b-bg: #f8fafc;
        /* Selaras dengan form Materi (Tiptap) */
        --text-main: #334155;
        --text-muted: #64748b;
        --primary-dark: #0f172a;
        --gold-light: #fdfaf3;
        --gold-solid: #b08d48;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .broadcast-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 22px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .broadcast-card-header {
        padding: 1rem 1.25rem;
        background: linear-gradient(120deg, #fffaf1, #f8fafc);
        border-bottom: 1px solid #e2e8f0;
    }
    .broadcast-body {
        padding: 1.25rem;
    }
    .broadcast-meta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: .78rem;
        font-weight: 700;
        color: #6b7280;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        padding: 6px 12px;
    }
    .broadcast-guide {
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        border-radius: 14px;
        padding: 10px 12px;
        font-size: .83rem;
        color: #475569;
        margin-top: .55rem;
    }
    .broadcast-side-card {
        border: 1px solid var(--b-border);
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        position: sticky;
        top: 92px;
    }
    .broadcast-side-header {
        padding: .9rem 1rem;
        border-bottom: 1px solid var(--b-border);
        background: linear-gradient(135deg, #fffaf1, #f8fafc);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .broadcast-side-header .title {
        font-weight: 900;
        color: #0f172a;
        margin: 0;
        font-size: 0.98rem;
    }
    .broadcast-side-body {
        padding: 1rem;
    }
    .choice-tile {
        border: 1px solid var(--b-border);
        border-radius: 14px;
        background: #fff;
        padding: 12px 12px;
        display: flex;
        gap: 10px;
        align-items: flex-start;
        cursor: pointer;
        transition: .2s ease;
    }
    .choice-tile:hover {
        border-color: rgba(176, 141, 72, 0.55);
        box-shadow: 0 12px 18px rgba(176, 141, 72, 0.12);
        transform: translateY(-1px);
    }
    .choice-tile .icon {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fffbeb;
        color: var(--b-gold);
        flex: 0 0 auto;
        font-size: 18px;
    }
    .choice-tile .label {
        font-weight: 900;
        color: #0f172a;
        margin: 0;
        font-size: .92rem;
    }
    .choice-tile .desc {
        margin: 4px 0 0;
        color: var(--b-muted);
        font-size: .82rem;
        line-height: 1.4;
    }
    .stat-pill {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid var(--b-border);
        background: var(--b-bg);
        font-size: .78rem;
        font-weight: 800;
        color: var(--b-slate);
    }
    .btn-broadcast-primary {
        background: linear-gradient(135deg, #c5a059 0%, #8e6d2f 100%);
        border: none;
        border-radius: 14px;
        font-weight: 900;
        padding: 0.75rem 1.15rem;
        display: inline-flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
        width: 100%;
        color: #fff;
        transition: .2s ease;
    }
    .btn-broadcast-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(176, 141, 72, 0.28);
        color: #fff;
    }
    .btn-broadcast-light {
        border-radius: 14px;
        font-weight: 800;
        width: 100%;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .74rem;
        font-weight: 800;
        border-radius: 999px;
        padding: 4px 10px;
        border: 1px solid transparent;
    }
    .status-badge.queued { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .status-badge.processing { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
    .status-badge.done { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .status-badge.failed { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
    .history-list { display: grid; gap: 8px; }
    .history-item {
        border: 1px solid var(--b-border);
        border-radius: 12px;
        padding: 10px;
        background: #fff;
    }
    .history-item .subject {
        font-size: .82rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 6px;
    }
    .history-item .meta {
        font-size: .75rem;
        color: #64748b;
    }
    .history-item .counts {
        font-size: .74rem;
        color: #334155;
        font-weight: 700;
        margin-top: 4px;
    }
    .gmail-recipient-shell {
        border: 1px solid var(--b-border);
        border-radius: 14px;
        background: #fff;
        padding: 10px 10px 8px;
    }
    .gmail-recipient-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .gmail-recipient-label {
        font-size: .76rem;
        font-weight: 800;
        color: #64748b;
        letter-spacing: .02em;
        text-transform: uppercase;
    }
    .gmail-recipient-note {
        font-size: .73rem;
        color: #94a3b8;
    }
    .select2-container--default .select2-selection--multiple.gmail-like {
        border: 1px solid #dbe4ef;
        border-radius: 12px;
        min-height: 44px;
        padding: 4px 6px;
        background: #fff;
    }
    .select2-container--default .select2-selection--multiple.gmail-like .select2-selection__choice {
        background: #f1f3f4;
        border: 1px solid #dadce0;
        color: #202124;
        border-radius: 999px;
        padding: 4px 10px 4px 11px;
        font-size: .79rem;
        font-weight: 600;
        margin-top: 4px;
        margin-right: 6px;
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        line-height: 1.3;
        vertical-align: middle;
    }
    .select2-container--default .select2-selection--multiple.gmail-like .select2-selection__choice__remove {
        color: #5f6368;
        margin: 0;
        border-right: none;
        font-size: 15px;
        line-height: 1;
        padding: 0 !important;
        border-radius: 999px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        cursor: pointer;
        position: static !important;
        float: none !important;
        left: auto !important;
        right: auto !important;
        top: auto !important;
        transform: none !important;
        order: 2;
        margin-left: 2px !important;
        background: transparent;
    }
    .select2-container--default .select2-selection--multiple.gmail-like .select2-selection__choice__remove:hover {
        color: #202124;
        background: #dfe1e5;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        margin-top: 4px;
        font-size: .84rem;
    }
    .gmail-option {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .gmail-option-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 800;
        flex: 0 0 auto;
    }
    .gmail-option-main {
        min-width: 0;
    }
    .gmail-option-name {
        font-size: .82rem;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .gmail-option-email {
        font-size: .74rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* === Tiptap: sama seperti admin/materi/_form.blade.php === */
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
    .tiptap-editor { background-color: #ffffff; }
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
    #materi-editor .resize-cursor { cursor: col-resize !important; }
    #materi-editor img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    #materi-editor img.tiptap-image-align-left { margin: 0.5rem auto 0.75rem 0; }
    #materi-editor img.tiptap-image-align-center { margin: 0.75rem auto; }
    #materi-editor img.tiptap-image-align-right { margin: 0.5rem 0 0.75rem auto; }
    #materi-editor {
        min-height: 340px;
        padding: 1rem 1.1rem;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--text-main);
    }
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
    #materi-editor :not(pre) > code {
        background: rgba(148, 163, 184, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.28);
        padding: 0.12rem 0.35rem;
        border-radius: 8px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.92em;
    }
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
    .tiptap-table-grid-cell { border-radius: 4px; }
    .target-hint { font-size: .82rem; color: #64748b; }
    .recipient-counter { font-size: .78rem; color: #475569; font-weight: 700; margin-top: .35rem; }
    .field-help { font-size: .82rem; color: var(--b-muted); margin-top: .35rem; }
    .error-box { border-left: 4px solid #ef4444; background: #fff1f2; padding: 10px 12px; border-radius: 12px; }

    /* =========================
       Dark theme overrides
       (mengikuti data-skin="dark")
    ========================== */
    html[data-skin="dark"] .broadcast-card {
        background: rgba(15, 23, 42, 0.92);
        border-color: rgba(51, 65, 85, 0.85);
        box-shadow: 0 18px 46px rgba(0, 0, 0, 0.35);
    }
    html[data-skin="dark"] .broadcast-card-header {
        background: linear-gradient(120deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.95));
        border-bottom-color: rgba(51, 65, 85, 0.85);
    }
    html[data-skin="dark"] .broadcast-card-header h5,
    html[data-skin="dark"] .broadcast-side-header .title {
        color: #e5e7eb !important;
    }
    html[data-skin="dark"] .broadcast-card-header .text-muted {
        color: #94a3b8 !important;
    }
    html[data-skin="dark"] .broadcast-meta {
        background: rgba(2, 6, 23, 0.55);
        border-color: rgba(51, 65, 85, 0.85);
        color: #cbd5e1;
    }
    html[data-skin="dark"] .broadcast-side-card {
        background: rgba(15, 23, 42, 0.9);
        border-color: rgba(51, 65, 85, 0.85);
        box-shadow: 0 18px 46px rgba(0, 0, 0, 0.35);
    }
    html[data-skin="dark"] .broadcast-side-header {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.95));
        border-bottom-color: rgba(51, 65, 85, 0.85);
    }
    html[data-skin="dark"] .stat-pill {
        background: rgba(2, 6, 23, 0.55);
        border-color: rgba(51, 65, 85, 0.85);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .form-label,
    html[data-skin="dark"] .field-help,
    html[data-skin="dark"] .target-hint,
    html[data-skin="dark"] .recipient-counter {
        color: #cbd5e1 !important;
    }
    html[data-skin="dark"] .form-control,
    html[data-skin="dark"] .form-select {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .form-control::placeholder {
        color: rgba(203, 213, 225, 0.55);
    }
    html[data-skin="dark"] .form-control:focus,
    html[data-skin="dark"] .form-select:focus {
        border-color: rgba(176, 141, 72, 0.75);
        box-shadow: 0 0 0 0.2rem rgba(176, 141, 72, 0.18);
    }
    html[data-skin="dark"] .choice-tile {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
    }
    html[data-skin="dark"] .choice-tile:hover {
        border-color: rgba(176, 141, 72, 0.65);
        box-shadow: 0 14px 26px rgba(0, 0, 0, 0.35);
    }
    html[data-skin="dark"] .choice-tile .label {
        color: #e5e7eb;
    }
    html[data-skin="dark"] .choice-tile .desc {
        color: #94a3b8;
    }
    html[data-skin="dark"] .choice-tile .icon {
        background: rgba(176, 141, 72, 0.12);
        color: #e9d5a7;
    }
    html[data-skin="dark"] .broadcast-guide {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(148, 163, 184, 0.35);
        color: #cbd5e1;
    }
    html[data-skin="dark"] .error-box {
        background: rgba(190, 18, 60, 0.12);
        color: #fecdd3;
        border-left-color: #fb7185;
    }

    /* Tiptap dark (selaras Materi) */
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
    html[data-skin="dark"] #materi-editor table { background: #0b1220 !important; }
    html[data-skin="dark"] #materi-editor th,
    html[data-skin="dark"] #materi-editor td {
        border-color: rgb(69, 81, 100) !important;
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
    html[data-skin="dark"] #materi-editor a { color: #93c5fd; }
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
    html[data-skin="dark"] #tableSizeModal .modal-title { color: #ffffff !important; }
    html[data-skin="dark"] #tableSizeModal .text-muted { color: #cbd5e1 !important; }
    html[data-skin="dark"] #tableSizeModal .btn-close {
        filter: invert(1) grayscale(1);
        opacity: 0.85;
    }

    /* Select2 (peserta khusus) */
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
        color: #e5e7eb;
        border-radius: 12px;
        min-height: 42px;
    }
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: rgba(148, 163, 184, 0.14);
        border: 1px solid rgba(51, 65, 85, 0.85);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #cbd5e1;
    }
    html[data-skin="dark"] .select2-dropdown {
        background: rgba(15, 23, 42, 0.98);
        border-color: rgba(51, 65, 85, 0.85);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background: rgba(176, 141, 72, 0.22);
        color: #ffffff;
    }
    html[data-skin="dark"] .select2-container--default .select2-search--dropdown .select2-search__field {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
        color: #e5e7eb;
        border-radius: 10px;
        outline: none;
    }
    html[data-skin="dark"] .history-item {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
    }
    html[data-skin="dark"] .history-item .subject { color: #e5e7eb; }
    html[data-skin="dark"] .history-item .meta { color: #94a3b8; }
    html[data-skin="dark"] .history-item .counts { color: #cbd5e1; }
    html[data-skin="dark"] .gmail-recipient-shell {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
    }
    html[data-skin="dark"] .gmail-recipient-label { color: #cbd5e1; }
    html[data-skin="dark"] .gmail-recipient-note { color: #94a3b8; }
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple.gmail-like {
        background: rgba(2, 6, 23, 0.35);
        border-color: rgba(51, 65, 85, 0.85);
    }
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple.gmail-like .select2-selection__choice {
        background: rgba(148, 163, 184, 0.16);
        border-color: rgba(71, 85, 105, 0.9);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple.gmail-like .select2-selection__choice__remove {
        color: #cbd5e1;
    }
    html[data-skin="dark"] .select2-container--default .select2-selection--multiple.gmail-like .select2-selection__choice__remove:hover {
        background: rgba(148, 163, 184, 0.25);
        color: #ffffff;
    }
    html[data-skin="dark"] .gmail-option-avatar {
        background: rgba(148, 163, 184, 0.18);
        color: #e5e7eb;
    }
    html[data-skin="dark"] .gmail-option-name { color: #e5e7eb; }
    html[data-skin="dark"] .gmail-option-email { color: #94a3b8; }
</style>
