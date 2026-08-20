<style>
    /* DAFTAR SEMUA TOMBOL YANG AKAN DI-ANIMASI */
    button, 
    .btn, 
    a.btn, 
    input[type="submit"], 
    .btn-beli, 
    .btn-cart, 
    .btn-add,        /* Tambahan: Tombol Add di Index */
    .btn-checkout,   /* Tambahan: Tombol Checkout di Keranjang */
    .btn-custom      /* Tambahan: Tombol Login/Register */
    {
        /* Pastikan transisi aktif */
        transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* EFEK SAAT DITEKAN (ACTIVE) */
    button:active, 
    .btn:active, 
    a.btn:active, 
    input[type="submit"]:active, 
    .btn-beli:active, 
    .btn-cart:active, 
    .btn-add:active, 
    .btn-checkout:active,
    .btn-custom:active
    {
        /* Mengecil ke 90% (lebih terlihat daripada 95%) */
        transform: scale(0.90) !important; 
        
        /* Ubah warna background sedikit lebih gelap (opsional, biar makin kerasa) */
        filter: brightness(0.9);
    }
</style>

<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-warning">Retail Place</h5>
                <p>
                    Temukan produk terbaik dengan harga terjangkau hanya di Retail Place. 
                    Belanja mudah, aman, dan cepat sampai tujuan.
                </p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-warning">Menu</h5>
                <p><a href="index.php" class="text-white text-decoration-none">Home</a></p>
                <p><a href="keranjang.php" class="text-white text-decoration-none">Keranjang</a></p>
                <p>Kritik & Saran</p>
                <p>Syarat & Ketentuan</p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-warning">Kontak</h5>
                <p><i class="fas fa-home me-3"></i> Sidoarjo, Jawa Timur</p>
                <p><i class="fas fa-envelope me-3"></i> RetailPlace@gmail.com</p>
                <p><i class="fas fa-phone me-3"></i> +62 812-3456-7890</p>
            </div>

        </div>

        <hr class="mb-4">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p>© 2026 <strong>Retail Place</strong>. All Rights Reserved.</p>
            </div>
            
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end">
                    <ul class="list-unstyled list-inline">
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-instagram"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="btn-floating btn-sm text-white" style="font-size: 23px;"><i class="fab fa-whatsapp"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>