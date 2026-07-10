@php
    $b = $broadcast ?? null;
    $formChannel = $formChannel ?? ($channel ?? 'email');
    $isWebForm = $formChannel === 'web';
    $rp = $routePrefix ?? 'broadcast-email';
    $submitLabel = $submitLabel ?? 'Kirim Pengumuman';

    $subjectOld = old('subject', $b->subject ?? '');
    $isiOld = old('isi', $b ? $b->content_html : '<p></p>');
    $targetTypeOld = old('target_type', $b->target_type ?? 'semua');

    $initialScopes = ['aktif'];
    if ($b && $b->target_type === 'semua' && filled($b->target_scope)) {
        $initialScopes = array_values(array_filter(explode(',', (string) $b->target_scope)));
    }
    $oldScopes = old('target_scopes', $initialScopes);
    if (! is_array($oldScopes)) {
        $oldScopes = ['aktif'];
    }

    $selectedIds = old('peserta_ids');
    if (! is_array($selectedIds)) {
        if ($b && $b->target_type === 'khusus' && filled($b->target_peserta_ids)) {
            $decoded = json_decode($b->target_peserta_ids, true);
            $selectedIds = is_array($decoded) ? array_map('strval', $decoded) : [];
        } else {
            $selectedIds = [];
        }
    } else {
        $selectedIds = array_map('strval', $selectedIds);
    }
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="mb-3">
            <label class="form-label fw-700">{{ $isWebForm ? 'Judul pengumuman' : 'Subjek Email' }} <span class="text-danger">*</span></label>
            <input type="text" name="subject" class="form-control shadow-none"
                   value="{{ $subjectOld }}"
                   placeholder="{{ $isWebForm ? 'Contoh: Pengumuman penting dari panitia' : 'Contoh: Pengumuman Jadwal Briefing' }}">
            <div class="field-help">
                @if($isWebForm)
                    Judul tampil di popup &amp; daftar pengumuman di aplikasi peserta.
                @else
                    Subjek akan tampil di email dan juga judul popup pengumuman peserta (untuk penerima email).
                @endif
            </div>
            @error('subject')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-700">Isi Pengumuman <span class="text-danger">*</span></label>

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

                <textarea id="materi_content" name="isi" hidden>{{ $isiOld }}</textarea>

                <div class="tiptap-editor">
                    <div id="materi-editor"
                         data-upload-url="{{ route('broadcast-email.upload-image') }}"
                         data-csrf="{{ csrf_token() }}"></div>
                </div>
            </div>
            @error('isi')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-4">
        <div class="broadcast-side-card">
            <div class="broadcast-side-header">
                <p class="title">
                    <i class="{{ $isWebForm ? 'ri-notification-3-line' : 'ri-send-plane-2-line' }} me-2 text-warning"></i>
                    {{ $isWebForm ? 'Penerbitan' : 'Target & Ringkasan' }}
                </p>
                @if(! $isWebForm)
                    <span class="stat-pill"><i class="ri-user-line"></i> {{ count($pesertaOptions ?? []) }}</span>
                @endif
            </div>

            <div class="broadcast-side-body">
                <div class="mb-3">
                    <label class="form-label fw-700">Kategori</label>
                    <input type="text" class="form-control shadow-none" value="Sistem Magang" readonly>
                </div>

                @if($isWebForm)
                <div class="mb-3 p-3" style="border:1px solid var(--b-border);border-radius:14px;background:rgba(14,165,233,0.06);">
                    <div class="fw-800 mb-1"><i class="ri-global-line me-1"></i> Semua peserta</div>
                    <p class="small text-muted mb-0">
                        Pengumuman diterbitkan ke <strong>seluruh peserta magang</strong> (status aktif &amp; lulus) di aplikasi.
                        Tidak ada pengiriman email dan tidak bisa memilih peserta khusus.
                    </p>
                </div>
                @else

                <div class="mb-3">
                    <label class="form-label fw-700">Target Pengiriman <span class="text-danger">*</span></label>

                    <label class="choice-tile w-100 mb-2" for="target_khusus">
                        <span class="icon"><i class="ri-user-search-line"></i></span>
                        <span>
                            <span class="label">Pengiriman Khusus</span>
                            <span class="desc d-block">Pilih peserta satu per satu (aktif maupun lulus).</span>
                        </span>
                        <span class="ms-auto">
                            <input class="form-check-input mt-1" type="radio" name="target_type" id="target_khusus" value="khusus"
                                   {{ $targetTypeOld === 'khusus' ? 'checked' : '' }}>
                        </span>
                    </label>

                    <label class="choice-tile w-100" for="target_semua">
                        <span class="icon" style="background:#f0f9ff;color:#0284c7;"><i class="ri-group-line"></i></span>
                        <span>
                            <span class="label">Semua Peserta</span>
                            <span class="desc d-block">Pilih cakupan: aktif, lulus, atau keduanya.</span>
                        </span>
                        <span class="ms-auto">
                            <input class="form-check-input mt-1" type="radio" name="target_type" id="target_semua" value="semua"
                                   {{ $targetTypeOld === 'semua' ? 'checked' : '' }}>
                        </span>
                    </label>

                    @error('target_type')
                        <div class="text-danger small mt-2 error-box">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="scope-wrapper" style="display:none;">
                    <label class="form-label fw-700">Cakupan Semua Peserta <span class="text-danger">*</span></label>
                    <div class="p-3" style="border:1px solid var(--b-border);border-radius:14px;background:var(--b-bg);">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="target_scopes[]" id="scope_aktif" value="aktif"
                                   {{ in_array('aktif', $oldScopes, true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-800" for="scope_aktif">Peserta Aktif</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="target_scopes[]" id="scope_lulus" value="lulus"
                                   {{ in_array('lulus', $oldScopes, true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-800" for="scope_lulus">Peserta Lulus</label>
                        </div>
                    </div>
                    @error('target_scopes')
                        <div class="text-danger small mt-2 error-box">{{ $message }}</div>
                    @enderror
                    @error('target_scopes.*')
                        <div class="text-danger small mt-2 error-box">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="khusus-wrapper" style="display:none;">
                    <label class="form-label fw-700">Pilih Peserta Khusus <span class="text-danger">*</span></label>
                    <div class="gmail-recipient-shell">
                        <div class="gmail-recipient-header">
                            <span class="gmail-recipient-label">To:</span>
                            <span class="gmail-recipient-note">Ketik nama atau email peserta</span>
                        </div>
                        <select name="peserta_ids[]" id="peserta_ids" class="form-select shadow-none" multiple>
                            @foreach($pesertaOptions as $item)
                                <option value="{{ $item['id'] }}"
                                    data-label="{{ $item['label'] }}"
                                    {{ in_array((string) $item['id'], $selectedIds, true) ? 'selected' : '' }}>
                                    {{ $item['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="recipient-counter" id="selected-counter">Peserta terpilih: 0</div>
                    @error('peserta_ids')
                        <div class="text-danger small mt-2 error-box">{{ $message }}</div>
                    @enderror
                    @error('peserta_ids.*')
                        <div class="text-danger small mt-2 error-box">{{ $message }}</div>
                    @enderror
                </div>

                @endif

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn-broadcast-primary" id="broadcast-submit-btn">
                        @if($isWebForm)
                            <i class="ri-notification-badge-line"></i>
                        @else
                            <i class="ri-mail-send-line"></i>
                        @endif
                        <span>{{ $submitLabel }}</span>
                    </button>
                    <a href="{{ route($rp.'.index') }}" class="btn btn-light btn-broadcast-light">
                        <i class="ri-arrow-left-line"></i> Kembali ke Riwayat
                    </a>
                </div>
                <div class="field-help mt-2">
                    @if($isWebForm)
                        Hanya tampil di aplikasi (popup &amp; notifikasi peserta). Email tidak dikirim.
                    @else
                        Setelah dikirim, pengumuman juga tampil sebagai popup untuk peserta yang menjadi penerima email.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
