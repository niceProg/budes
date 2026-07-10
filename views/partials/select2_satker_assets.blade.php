@once('magangdpr-select2-satker-assets')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    (function () {
        /** Label satker: dukung kolom baru (nama_satker) dan alias lama (nama). */
        window.satkerNama = function (uk) {
            if (!uk) return '-';
            return uk.nama_satker || uk.nama || '-';
        };
        window.satkerKode = function (uk) {
            if (!uk) return '-';
            return uk.kode_satker || uk.kode || '-';
        };
        window.satkerLabel = function (uk) {
            return window.satkerNama(uk) + ' (' + window.satkerKode(uk) + ')';
        };

        if (window.initSelect2Satker) {
            return;
        }
        /**
         * Inisialisasi Select2 untuk dropdown satuan kerja (hancurkan instance lama jika ada).
         * @param {string|Element|jQuery} el
         * @param {object} [opts] placeholder, allowClear, dropdownParent (jQuery), width, minimumResultsForSearch
         */
        window.initSelect2Satker = function (el, opts) {
            if (!window.jQuery || typeof $.fn.select2 === 'undefined') {
                return;
            }
            var $el = el instanceof jQuery ? el : $(el);
            if (!$el.length) {
                return;
            }
            if ($el.data('select2')) {
                try {
                    $el.select2('destroy');
                } catch (e) {}
            }
            opts = opts || {};
            var merged = {
                placeholder: opts.placeholder != null ? opts.placeholder : '-- Pilih Satuan Kerja --',
                allowClear: opts.allowClear !== false,
                width: opts.width || '100%'
            };
            if (opts.dropdownParent) {
                merged.dropdownParent = opts.dropdownParent;
            } else {
                var $modal = $el.closest('.modal');
                if ($modal.length) {
                    merged.dropdownParent = $modal;
                } else if (typeof Swal !== 'undefined' && Swal.getPopup) {
                    var $p = $(Swal.getPopup());
                    if ($p.length) {
                        merged.dropdownParent = $p;
                    }
                }
            }
            if (opts.minimumResultsForSearch !== undefined) {
                merged.minimumResultsForSearch = opts.minimumResultsForSearch;
            }
            $el.select2(merged);
        };

        window.destroySelect2SatkerIfAny = function (el) {
            if (!window.jQuery) {
                return;
            }
            var $el = el instanceof jQuery ? el : $(el);
            if ($el.length && $el.data('select2')) {
                try {
                    $el.select2('destroy');
                } catch (e) {}
            }
        };
    })();
    </script>
@endpush
@endonce
