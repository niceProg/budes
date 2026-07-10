<button class="back-to-top" id="backToTop" type="button" aria-label="Kembali ke atas" onclick="scrollToTop()">
    <i class="fas fa-arrow-up"></i>
</button>

<div style="width: 100%; margin: 2rem 0 0; display: flex; justify-content: center;">
    <img src="{{ asset('theme/admin-dashbyte/dist/assets/img/logo-footer.png') }}" alt="Logo DPR RI" style="max-width: 700px; width: 100%; height: auto;">
</div>

<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <h3>{{ config('app.name') }}</h3>
                <p><span class="gold-text">Pusat Pengembangan Kompetensi SDM Legislatif</span> — Sekretariat Jenderal DPR RI. Platform terintegrasi untuk Magang, PKL, dan Penelitian.</p>
                <div class="footer-social">
                    <h4>Media Sosial</h4>
                    <div class="footer-social-links">
                        <a href="https://www.instagram.com/dpr_ri/" target="_blank" rel="noopener noreferrer" title="Instagram DPR RI"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.facebook.com/DPRRI/" target="_blank" rel="noopener noreferrer" title="Facebook DPR RI"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/DPR_RI" target="_blank" rel="noopener noreferrer" title="Twitter/X DPR RI"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.youtube.com/c/DPRRIOfficial" target="_blank" rel="noopener noreferrer" title="YouTube DPR RI"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.tiktok.com/@dpr_ri" target="_blank" rel="noopener noreferrer" title="TikTok DPR RI"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-right">
                <div class="footer-links">
                    <h4>Tautan Cepat</h4>
                    <ul>
                        <li><a href="https://www.dpr.go.id/" target="_blank">Website DPR RI</a></li>
                        <li><a href="https://pusbangkom.dpr.go.id/" target="_blank">PUSBANGKOM</a></li>
                        <li><a href="{{ route('lowongan.khusus') }}">Lowongan</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="https://pusbangkom.dpr.go.id/kontak/index" target="_blank">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Kontak</h4>
                    <p class="contact-item" style="margin-bottom: 0.75rem;"><i class="fas fa-location-dot"></i> Gedung Setjen DPR RI, Lantai 4, Jl. Gatot Subroto, Jakarta</p>
                    <p class="contact-item"><i class="fas fa-phone"></i> 021 - 571 5823</p>
                    <p class="contact-item"><i class="fas fa-phone"></i> 021 - 571 5817</p>
                    <p class="contact-item"><i class="fas fa-envelope"></i> <a href="mailto:bid_bangkom_msk@dpr.go.id">bid_bangkom_msk@dpr.go.id</a></p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy" style="text-align: center;">© {{ date('Y') }} Pustekinfo Sekretariat Jenderal DPR RI. All rights reserved.</div>
        </div>
    </div>
</footer>
