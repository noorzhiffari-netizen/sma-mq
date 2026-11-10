<footer class="situs">
    <div class="wadah">
        <div class="footer-grid">
            <div>
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:14px;">
                    <img src="<?= BASE_URL ?>assets/img/logo.png" alt="Logo SMAI MQ" style="width:48px; height:auto; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.3));">
                    <h4 style="margin:0; font-size:1.15rem;"><?= e($profil['nama_sekolah']) ?></h4>
                </div>
                <p>Sekolah Menengah Atas berbasis Pondok Pesantren. Seluruh santri wajib tinggal di asrama selama menempuh pendidikan pada program IPA maupun IPS.</p>
            </div>
            <div>
                <h4>Tautan</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>profil.php">Profil Sekolah</a></li>
                    <li><a href="<?= BASE_URL ?>jurusan.php">Program Jurusan</a></li>
                    <li><a href="<?= BASE_URL ?>asrama.php">Asrama Santri</a></li>
                    <li><a href="https://spmb.ponpes-mhmq.my.id/" target="_blank">Pendaftaran (SPMB)</a></li>
                </ul>
            </div>
            <div>
                <h4>Informasi</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>berita.php">Berita &amp; Pengumuman</a></li>
                    <li><a href="<?= BASE_URL ?>galeri.php">Galeri Kegiatan</a></li>
                    <li><a href="<?= BASE_URL ?>kontak.php">Hubungi Kami</a></li>
                </ul>
            </div>
            <div>
                <h4>Alamat &amp; Kontak</h4>
                <p>
                    <?= e($profil['alamat']) ?>, Desa <?= e($profil['desa_kelurahan']) ?>,
                    <?= e($profil['kecamatan']) ?>, <?= e($profil['kabupaten']) ?>,
                    <?= e($profil['provinsi']) ?> <?= e($profil['kode_pos']) ?>
                </p>
                <p><?= e($profil['telepon']) ?><br><?= e($profil['email']) ?></p>
                <div style="margin-top:15px; display:flex; gap:10px;">
                    <?php if(!empty($profil['facebook'])): ?><a href="<?= e($profil['facebook']) ?>" target="_blank" style="display:inline-block; padding:5px 10px; background:#3b5998; color:white; border-radius:4px; text-decoration:none; font-weight:bold;">FB</a><?php endif; ?>
                    <?php if(!empty($profil['instagram'])): ?><a href="<?= e($profil['instagram']) ?>" target="_blank" style="display:inline-block; padding:5px 10px; background:#C13584; color:white; border-radius:4px; text-decoration:none; font-weight:bold;">IG</a><?php endif; ?>
                    <?php if(!empty($profil['youtube'])): ?><a href="<?= e($profil['youtube']) ?>" target="_blank" style="display:inline-block; padding:5px 10px; background:#FF0000; color:white; border-radius:4px; text-decoration:none; font-weight:bold;">YT</a><?php endif; ?>
                </div>
            </div>
        </div>
        <div class="footer-bawah">
            <span>&copy; <?= date('Y') ?> <?= e($profil['nama_sekolah']) ?>. Berdiri sejak <?= e($profil['tahun_berdiri']) ?>.</span>
            <span>Dikelola oleh Tim IT SMAI MQ</span>
        </div>
    </div>
</footer>

<?php if (!empty($profil['whatsapp'])): ?>
    <a href="https://wa.me/<?= e($profil['whatsapp']) ?>" target="_blank" class="floating-wa" aria-label="Chat WhatsApp">
        <svg viewBox="0 0 32 32" width="30" height="30" fill="currentColor">
            <path d="M16 2a13 13 0 0 0-11 20l-2 6 6-2a13 13 0 1 0 7-24zm0 24a11 11 0 0 1-5.6-1.5l-.4-.2-4.2 1.4 1.4-4-.3-.5A11 11 0 1 1 16 26zm6-8c-.3-.2-1.8-.9-2-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.3-.6.1-1.2-.6-2.4-1.6-3.3-2.7-.3-.3.1-.3.5-.8.2-.2.3-.4.5-.6.1-.2 0-.4 0-.5-.2-.4-1-2.4-1.3-3.2-.3-.8-.6-.7-.8-.7h-.7c-.3 0-.7.1-1 .4-.3.4-1.2 1.2-1.2 2.9 0 1.7 1.3 3.3 1.5 3.5.2.3 2.4 3.7 5.8 5.1.8.3 1.5.5 2 .7.8.2 1.5.2 2 .1.6-.1 1.8-.7 2-1.4.3-.7.3-1.3.2-1.4-.2-.1-.4-.2-.7-.3z"></path>
        </svg>
    </a>
<?php endif; ?>

<script src="<?= BASE_URL ?>assets/js/script.js"></script>
</body>
</html>
