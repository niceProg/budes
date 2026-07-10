<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('show.bs.modal', function (event) {
            const modalEl = event.target;
            if (modalEl && modalEl.parentElement !== document.body) {
                document.body.appendChild(modalEl);
            }
        });

        const targetKhusus = document.getElementById('target_khusus');
        const targetSemua = document.getElementById('target_semua');
        const scopeWrapper = document.getElementById('scope-wrapper');
        const scopeAktif = document.getElementById('scope_aktif');
        const scopeLulus = document.getElementById('scope_lulus');
        const khususWrapper = document.getElementById('khusus-wrapper');
        const pesertaSelect = $('#peserta_ids');
        const counter = document.getElementById('selected-counter');
        const form = document.getElementById('broadcast-form');
        const submitBtn = document.getElementById('broadcast-submit-btn');

        function parseOption(rawText) {
            const text = (rawText || '').trim();
            const parts = text.split(' - ');
            const name = (parts[0] || '').trim();
            const email = (parts.slice(1).join(' - ') || '').trim();
            return {
                name: name || text,
                email: email || '',
            };
        }

        function initialsFromName(name) {
            const words = (name || '').trim().split(/\s+/).filter(Boolean);
            if (!words.length) return 'P';
            const first = words[0]?.[0] || '';
            const second = words[1]?.[0] || '';
            return (first + second).toUpperCase() || 'P';
        }

        function formatRecipient(state) {
            if (!state.id) return state.text;
            const parsed = parseOption(state.text || '');
            const initials = initialsFromName(parsed.name);
            const safeName = $('<div>').text(parsed.name).html();
            const safeEmail = $('<div>').text(parsed.email).html();
            return $(
                '<div class="gmail-option">' +
                    '<span class="gmail-option-avatar">' + initials + '</span>' +
                    '<span class="gmail-option-main">' +
                        '<div class="gmail-option-name">' + safeName + '</div>' +
                        (safeEmail ? '<div class="gmail-option-email">' + safeEmail + '</div>' : '') +
                    '</span>' +
                '</div>'
            );
        }

        function recipientMatcher(params, data) {
            const term = (params.term || '').trim().toLowerCase();
            if (!term) return data;

            const raw = (data.text || '').toLowerCase();
            const parsed = parseOption(data.text || '');
            const name = (parsed.name || '').toLowerCase();
            const email = (parsed.email || '').toLowerCase();

            if (name.startsWith(term) || email.startsWith(term)) {
                return data;
            }
            if (raw.includes(term)) {
                return data;
            }
            return null;
        }

        pesertaSelect.select2({
            width: '100%',
            placeholder: 'Pilih peserta...',
            allowClear: true,
            closeOnSelect: false,
            minimumResultsForSearch: 0,
            matcher: recipientMatcher,
            templateResult: formatRecipient,
            templateSelection: function (state) {
                if (!state.id) return state.text;
                const parsed = parseOption(state.text || '');
                return parsed.name + (parsed.email ? ' <' + parsed.email + '>' : '');
            },
            escapeMarkup: function (markup) { return markup; }
        });
        pesertaSelect.on('select2:open', function () {
            $('.select2-selection--multiple').addClass('gmail-like');
        });
        $('.select2-selection--multiple').addClass('gmail-like');
        pesertaSelect.on('select2:select', function () {
            const searchInput = document.querySelector('.select2-container--open .select2-search__field');
            if (searchInput) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            setTimeout(function () {
                pesertaSelect.select2('open');
                const reopenedInput = document.querySelector('.select2-container--open .select2-search__field');
                if (reopenedInput) reopenedInput.focus();
            }, 0);
        });

        function getTargetType() {
            if (targetKhusus && targetKhusus.checked) return 'khusus';
            return 'semua';
        }
        function toggleTargetFields() {
            const type = getTargetType();
            const isKhusus = type === 'khusus';
            if (khususWrapper) khususWrapper.style.display = isKhusus ? '' : 'none';
            if (scopeWrapper) scopeWrapper.style.display = isKhusus ? 'none' : '';

            if (!isKhusus) {
                const anyChecked = (scopeAktif && scopeAktif.checked) || (scopeLulus && scopeLulus.checked);
                if (!anyChecked && scopeAktif) {
                    scopeAktif.checked = true;
                }
            }
        }
        function updateCounter() {
            const val = pesertaSelect.val() || [];
            if (counter) {
                counter.textContent = 'Peserta terpilih: ' + val.length;
            }
        }
        toggleTargetFields();
        updateCounter();
        if (targetKhusus) targetKhusus.addEventListener('change', toggleTargetFields);
        if (targetSemua) targetSemua.addEventListener('change', toggleTargetFields);
        if (scopeAktif) scopeAktif.addEventListener('change', toggleTargetFields);
        if (scopeLulus) scopeLulus.addEventListener('change', toggleTargetFields);
        pesertaSelect.on('change', updateCounter);

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Kirim Broadcast Email?',
                    text: 'Pengumuman akan langsung dikirim ke target penerima yang dipilih.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#b08d48',
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> <span>Menjadwalkan...</span>';
                        }
                        form.submit();
                    }
                });
            });
        }
    });
</script>
<style>
    @keyframes spin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }
</style>
