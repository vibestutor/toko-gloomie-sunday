document.addEventListener('DOMContentLoaded', function() {
    // Ambil elemen kontainer utama sekali saja untuk efisiensi.
    const container = document.querySelector('.product-detail-container');

    // Buat fungsi kecil untuk menampilkan pesan error secara konsisten.
    const displayError = (message) => {
        container.innerHTML = `<h1>Oops! Terjadi Masalah</h1><p>${message}</p>`;
        document.title = 'Error - Gloomie Sunday'; // Ganti judul tab juga
    };

    // --- 1. VALIDASI INPUT ---
    // Pastikan parameter ID ada di URL.
    const params = new URLSearchParams(window.location.search);
    const productId = params.get('id');

    if (!productId) {
        displayError('Halaman tidak valid. ID produk tidak ditemukan di URL.');
        return; // Hentikan eksekusi jika tidak ada ID.
    }

    // --- 2. VALIDASI DATA SUMBER ---
    // Pastikan variabel 'allProducts' ada dan merupakan sebuah array.
    if (typeof allProducts === 'undefined' || !Array.isArray(allProducts)) {
        displayError('Gagal memuat data produk. Variabel "allProducts" tidak ditemukan.');
        return; // Hentikan eksekusi.
    }

    // --- 3. PROSES PENCARIAN PRODUK ---
    const product = allProducts.find(p => p.id === productId);

    // --- 4. TAMPILKAN HASIL ATAU PESAN ERROR ---
    if (product) {
        // Jika produk ditemukan, lanjutkan menampilkan data.

        // Ganti judul tab dengan nama produk, beri nilai default jika nama kosong.
        document.title = `${product.name || 'Detail Produk'} - Gloomie Sunday`;

        // Gunakan helper untuk mengisi elemen dengan aman.
        const setElementContent = (id, property, value) => {
            const element = document.getElementById(id);
            if (element) {
                element[property] = value;
            } else {
                // Beri peringatan di console untuk developer jika elemen tidak ada.
                console.warn(`Peringatan: Elemen dengan ID '${id}' tidak ditemukan di HTML.`);
            }
        };
        
        // Isi semua elemen dengan data produk.
        // Beri nilai default (misal: string kosong atau pesan) jika properti tidak ada di objek produk.
        setElementContent('product-image', 'src', product.image || '');
        setElementContent('product-brand', 'textContent', product.brand || 'Tanpa Merek');
        setElementContent('product-title', 'textContent', product.name || 'Nama Produk Tidak Tersedia');
        setElementContent('product-description', 'textContent', product.description || 'Deskripsi tidak tersedia.');

        // Khusus untuk harga, periksa apakah tipenya angka.
        if (typeof product.price === 'number') {
            setElementContent('product-price', 'textContent', `IDR ${product.price.toLocaleString('id-ID')}`);
        } else {
            setElementContent('product-price', 'textContent', 'Harga tidak tersedia');
        }

        // Mengisi thumbnail dengan aman.
        const thumbnailContainer = document.getElementById('product-thumbnails');
        if (thumbnailContainer) {
            thumbnailContainer.innerHTML = ''; // Kosongkan dulu
            // Pastikan 'thumbnails' ada dan merupakan array yang tidak kosong.
            if (Array.isArray(product.thumbnails) && product.thumbnails.length > 0) {
                product.thumbnails.forEach(thumbSrc => {
                    const img = document.createElement('img');
                    img.src = thumbSrc;
                    img.alt = `Thumbnail ${product.name}`; // Tambahkan alt text untuk aksesibilitas
                    img.classList.add('pd-thumbnail');
                    img.onclick = () => {
                        // Gunakan helper lagi untuk keamanan saat thumbnail diklik.
                        setElementContent('product-image', 'src', thumbSrc);
                    };
                    thumbnailContainer.appendChild(img);
                });
            }
        }
        
    } else {
        // Jika produk dengan ID tersebut tidak ditemukan di dalam array.
        displayError(`Produk dengan ID "${productId}" tidak dapat ditemukan.`);
    }
});