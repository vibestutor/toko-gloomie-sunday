// Menunggu hingga seluruh konten halaman (HTML) selesai dimuat sebelum menjalankan skrip
document.addEventListener('DOMContentLoaded', function () {

   // =======================================================
  // HELPER (bisa dipakai di cart/search)
  // =======================================================
  
  const parsePrice = (priceString) => {
    if (!priceString) return 0;
    return parseFloat(String(priceString).replace(/[^0-9]/g, '')) || 0;
  };
  const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(Number(number || 0));
  };
  const debounce = (fn, ms = 400) => {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
  };

  // =======================================================
  // BAGIAN 0: POPUP LOGIN/REGISTER/FORGOT (BACKEND-READY)
  // =======================================================

  (function () {
    const wrapper = document.querySelector('.wrapper');
    const forgotWrapper = document.getElementById('forgot-password-wrapper');
    const loginLinks = document.querySelectorAll('.login-link');
    const registerLink = document.querySelector('.register-link');
    const btnLoginPopup = document.getElementById('login-btn-popup');
    const iconClose = document.querySelectorAll('.icon-close');
    const forgotPasswordLink = document.querySelector('.forgot-link');
    const logoutBtnDesktop = document.getElementById('logout-btn-desktop');
    const logoutBtnMobile  = document.getElementById('logout-btn');
    const logoutForm = document.getElementById('logout-form');

    registerLink?.addEventListener('click', (e) => {
      e.preventDefault();
      wrapper?.classList.add('active', 'active-popup');
    });
    loginLinks.forEach(link => link.addEventListener('click', (e) => {
      e.preventDefault();
      wrapper?.classList.remove('active');
      forgotWrapper?.classList.remove('active-popup');
      wrapper?.classList.add('active-popup');
    }));
    btnLoginPopup?.addEventListener('click', (e) => { e.preventDefault(); wrapper?.classList.add('active-popup'); });
    iconClose.forEach(btn => btn.addEventListener('click', () => {
      wrapper?.classList.remove('active-popup');
      forgotWrapper?.classList.remove('active-popup');
    }));
    forgotPasswordLink?.addEventListener('click', (e) => {
      e.preventDefault();
      wrapper?.classList.remove('active-popup');
      forgotWrapper?.classList.add('active-popup');
    });
    const doLogout = (e) => { e.preventDefault(); logoutForm?.submit(); };
    logoutBtnDesktop?.addEventListener('click', doLogout);
    logoutBtnMobile?.addEventListener('click', doLogout);
  })();

   // =======================================================
  // BAGIAN 1: HEADER HOME TRANSPARAN → BIRU SAAT SCROLL
  // =======================================================

  (function () {
    const header = document.getElementById('main-header');
    if (!header) return;
    const isHome = header.dataset.home === '1';
    const onScroll = () => { if (isHome) header.classList.toggle('header-solid', window.scrollY > 50); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  })();

    // =======================================================
  // BAGIAN 2: NAVIGASI MOBILE (SIDEBAR)
  // =======================================================
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const mobileMenu   = document.getElementById('mobile-menu');
  const closeMenuBtn = document.getElementById('close-menu-btn');
  const pageOverlay  = document.getElementById('page-overlay');

  const toggleMenu = () => {
    mobileMenu?.classList.toggle('show');  // class lama kamu
    pageOverlay?.classList.toggle('show'); // overlay on/off
  };
  hamburgerBtn?.addEventListener('click', toggleMenu);
  closeMenuBtn?.addEventListener('click', toggleMenu);
  pageOverlay?.addEventListener('click', toggleMenu);

  // Dropdown kategori di mobile
  const dropbtnMobile = document.querySelector('.dropbtn-mobile');
  dropbtnMobile?.addEventListener('click', function (e) {
    e.preventDefault();
    const dropdownContent = this.nextElementSibling;
    dropdownContent?.classList.toggle('show');
    this.classList.toggle('active');
  });


     // =======================================================
  // BAGIAN 3: SEARCH BAR (UI + BACKEND /search)
  // =======================================================
  const searchContainer   = document.querySelector('.search-container');
  const searchButton      = document.querySelector('.search-btn-header');
  const searchInputHeader = document.querySelector('.search-input-header');
  const searchResultsBox  = document.getElementById('search-results');

  // buka/tutup panel search (pakai class lama kamu)
  if (searchContainer && searchButton && searchInputHeader) {
    searchButton.addEventListener('click', function (e) {
      e.preventDefault();
      searchContainer.classList.toggle('active');
      if (searchContainer.classList.contains('active')) {
        searchInputHeader.focus();
      } else {
        searchResultsBox && (searchResultsBox.innerHTML = '');
      }
    });
  }

  // AJAX search → /search?q=... (server balikin JSON)
  if (searchInputHeader && searchResultsBox) {
    const renderResults = (items = []) => {
      if (!items.length) {
        searchResultsBox.innerHTML = '<div class="empty">No results</div>';
        return;
      }
      searchResultsBox.innerHTML = items.map(p => `
        <a class="search-item" href="/products/${p.slug}">
          <img src="${p.image || '/img/placeholder.png'}" alt="">
          <span>${p.name}</span>
          <em>${formatRupiah(p.price || 0)}</em>
        </a>
      `).join('');
    };

    const doSearch = debounce(async () => {
      const q = searchInputHeader.value.trim();
      if (!q) { searchResultsBox.innerHTML = ''; return; }
      try {
        const res = await fetch(`/search?q=${encodeURIComponent(q)}`, {
          headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Search failed');
        const data = await res.json();
        renderResults(Array.isArray(data) ? data : []);
      } catch (err) {
        console.error(err);
        searchResultsBox.innerHTML = '<div class="empty">Error</div>';
      }
    }, 400);

    searchInputHeader.addEventListener('input', doSearch);
    // klik di luar → tutup hasil
    document.addEventListener('click', (e) => {
      const inside = e.target.closest('.search-container');
      if (!inside) searchResultsBox.innerHTML = '';
    });
  }

    // =======================================================
    // BAGIAN 4: INISIALISASI SWIPER JS UNTUK BANNER
    // =======================================================
    if (typeof Swiper !== 'undefined' && document.querySelector('.swiper')) {
        const swiper = new Swiper('.swiper', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }

/**
 * ==========================================================
 * JS HALAMAN DETAIL PRODUK
 * File ini mengatur interaksi user di halaman detail produk,
 * termasuk: pilih warna, pilih ukuran, atur quantity, 
 * tombol Add to Cart, dan tombol Buy Now.
 * ==========================================================
 */

(function () {
  'use strict';

  // --- 1. DOM SELECTORS ---
  // Gunakan 'const' untuk elemen yang tidak berubah
  const productDetailContainer = document.querySelector('.product-detail-container');
  if (!productDetailContainer) {
    console.error('Elemen `.product-detail-container` tidak ditemukan. Skrip dihentikan.');
    return;
  }

  const productImageElement = document.getElementById('product-image');
  const productPriceElement = document.getElementById('product-price');
  const quantityInputElement = productDetailContainer.querySelector('.quantity-input input');
  const addToCartButton = productDetailContainer.querySelector('.btn-add-to-cart');
  const buyNowButton = productDetailContainer.querySelector('.btn-buy-now');

  // --- 2. HELPER FUNCTIONS ---
  // Fungsi utilitas diatur dalam satu objek untuk kerapian.
  const utils = {
    /**
     * Mengubah nilai menjadi integer dengan aman.
     * @param {*} v - Nilai yang akan diubah.
     * @param {number} fallback - Nilai default jika konversi gagal.
     * @returns {number}
     */
    toInt: (v, fallback = 0) => {
      const n = parseInt(v, 10);
      return isNaN(n) ? fallback : n;
    },

    /**
     * Format angka menjadi mata uang Rupiah standar.
     * @param {number|string} num - Angka yang akan diformat.
     * @returns {string}
     */
    formatRupiah: (num) => {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(utils.toInt(num));
    }
  };

  /**
   * Membuat logika untuk pilihan yang bisa diklik (seperti size & color).
   * @param {string} selector - Selector CSS untuk semua pilihan.
   * @param {string} activeClass - Nama kelas CSS untuk pilihan aktif.
   * @returns {NodeListOf<Element>}
   */
  function createOptionSelector(selector, activeClass) {
    const options = productDetailContainer.querySelectorAll(selector);
    options.forEach(option => {
      option.addEventListener('click', () => {
        // Hapus kelas aktif dari semua pilihan
        options.forEach(o => o.classList.remove(activeClass));
        // Tambahkan kelas aktif ke yang diklik
        option.classList.add(activeClass);
      });
    });
    return options;
  }

  // --- 3. STATE MANAGEMENT ---
  const state = {
    selectedColor: null,
    selectedSize: null,
    productId: productDetailContainer.dataset.productId,
  };

  // --- 4. INICIALIZATION & EVENT LISTENERS ---
  
  // Pilih Ukuran
  const sizeOptions = createOptionSelector('.pd-size-option', 'selected');
  sizeOptions.forEach(sizeEl => {
    sizeEl.addEventListener('click', () => {
      state.selectedSize = sizeEl.textContent.trim();
    });
  });

  // Pilih Warna
  const colorOptions = createOptionSelector('.pd-color-option', 'active');
  colorOptions.forEach(colorEl => {
    colorEl.addEventListener('click', () => {
      state.selectedColor = colorEl.dataset.color;
      const newImage = colorEl.dataset.image;
      const newPrice = colorEl.dataset.price;

      if (newImage && productImageElement) {
        productImageElement.src = newImage;
      }
      if (newPrice && productPriceElement) {
        productPriceElement.textContent = utils.formatRupiah(newPrice);
      }
    });
  });

  // Pilih warna pertama secara default jika ada
  if (colorOptions.length > 0) {
    colorOptions[0].click();
  }

  // Tombol 'Tambah ke Keranjang'
  addToCartButton?.addEventListener('click', () => {
    handleFormSubmit('add-to-cart');
  });

  // Tombol 'Beli Sekarang'
  buyNowButton?.addEventListener('click', () => {
    handleFormSubmit('buy-now');
  });

  // --- 5. LOGIC FOR SUBMITTING DATA ---
  async function handleFormSubmit(action) {
    // Validasi Sisi Klien
    if (!state.selectedSize) {
      alert('Pilih ukuran produk terlebih dahulu.');
      return;
    }
    // Hanya validasi warna jika ada pilihan warna di halaman
    if (colorOptions.length > 0 && !state.selectedColor) {
      alert('Pilih warna produk terlebih dahulu.');
      return;
    }

    // Buat payload
    const payload = {
      product_id: state.productId,
      color: state.selectedColor,
      size: state.selectedSize,
      qty: utils.toInt(quantityInputElement?.value, 1)
    };

    // Tambahkan indikator loading
    const buttons = [addToCartButton, buyNowButton];
    buttons.forEach(btn => {
      if (btn) {
        btn.disabled = true;
        btn.textContent = 'Memproses...';
      }
    });

    try {
      const response = await fetch(`/${action}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        // Coba baca pesan error dari backend
        const errorData = await response.json();
        throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
      }

      const destination = { 'add-to-cart': '/cart', 'buy-now': '/checkout' };
      if (destination[action]) {
        window.location.href = destination[action];
      }

    } catch (error) {
      console.error('Gagal mengirim data ke server:', error);
      alert(`Terjadi kesalahan: ${error.message}. Silakan coba lagi.`);
      
      // Kembalikan tombol ke keadaan semula
      buttons.forEach(btn => {
        if (btn) {
          btn.disabled = false;
          btn.textContent = (btn === addToCartButton) ? 'Tambah ke Keranjang' : 'Beli Sekarang';
        }
      });
    }
  }

})();

// =======================================================
// CART PAGE (Modal remove + auto submit qty via hidden submit button)
// =======================================================
(function () {
  const list = document.querySelector('.cart-items-list');
  if (!list) return;

  // helpers
  const parsePrice = (s) => parseInt(String(s || '').replace(/[^\d]/g, ''), 10) || 0;
  const formatRupiah = (n) => 'Rp ' + Math.max(0, parseInt(n || 0, 10)).toLocaleString('id-ID');
  const debounce = (fn, ms = 400) => { let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), ms);} };

  // modal refs
  const overlay = document.getElementById('modal-overlay');
  const modal   = document.getElementById('confirmation-modal');
  const btnNo   = document.getElementById('cancel-btn');
  const btnYes  = document.getElementById('confirm-delete-btn');
  const modalTxt= document.getElementById('modal-text');

  let removeFormId = null;

  // submit qty melalui tombol submit "asli" (hidden) -> CSRF aman
  function submitQty(row) {
    const btn = row.querySelector('.js-submit-qty');
    if (btn) btn.click();
  }

  function updateRowTotal(row){
    const price = parsePrice(row.querySelector('.item-price')?.textContent || 'Rp 0');
    const qty   = Math.max(1, parseInt(row.querySelector('.js-qty')?.value || '1',10));
    const out   = row.querySelector('.item-total');
    if (out) out.textContent = formatRupiah(price * qty);
    updateSubtotal();
  }

  function updateSubtotal(){
  let subtotal = 0;

  document.querySelectorAll('.cart-items-list .cart-item').forEach(row => {
    const priceText = row.querySelector('.item-price')?.textContent || '0';
    const price = parseInt(String(priceText).replace(/[^\d]/g, ''), 10) || 0;

    const qtyInput = row.querySelector('.js-qty');
    const qty = Math.max(1, parseInt(qtyInput?.value || '1', 10));

    subtotal += price * qty;
  });

  const el = document.getElementById('subtotal-value');
  if (el) el.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
}

  // klik +/− & remove
  list.addEventListener('click', (e)=>{
    const row = e.target.closest('.cart-item');
    if (!row) return;

    // +/- quantity
    if (
      e.target.classList.contains('js-plus') ||
      e.target.classList.contains('js-minus') ||
      e.target.classList.contains('quantity-btn')
    ) {
      e.preventDefault();
      const input = row.querySelector('.js-qty');
      if (!input) return;
      let v = parseInt(input.value || '1', 10) || 1;
      if (e.target.textContent.trim() === '+') v += 1;
      if (e.target.textContent.trim() === '-') v = Math.max(1, v - 1);
      input.value = v;
      updateRowTotal(row);
      submitQty(row); // <-- kirim PATCH lewat tombol submit hidden
      return;
    }

    // remove (open modal)
    const removeBtn = e.target.closest('.remove-btn');
    if (removeBtn) {
      e.preventDefault();
      removeFormId = removeBtn.getAttribute('data-remove'); // id form DELETE
      const name = row.querySelector('.item-info p')?.textContent?.trim() || 'item ini';
      if (modalTxt) modalTxt.textContent = `Apakah Anda yakin ingin menghapus "${name}" dari keranjang?`;
      showModal();
    }
  });

  // ketik qty manual → debounce submit
  const debounced = debounce((row)=> submitQty(row), 500);
  list.addEventListener('input', (e)=>{
    if (!e.target.matches('.js-qty')) return;
    const row = e.target.closest('.cart-item'); if (!row) return;
    if (!e.target.value || parseInt(e.target.value,10) < 1) e.target.value = 1;
    updateRowTotal(row);
    debounced(row);
  });

  // modal actions
  btnYes?.addEventListener('click', ()=>{
    if (removeFormId) document.getElementById(removeFormId)?.submit(); // DELETE ke server
    hideModal();
  });
  btnNo?.addEventListener('click', hideModal);
  overlay?.addEventListener('click', hideModal);

  // init
  updateSubtotal();
})();

// ===============================================
// checkout.js - Logic for Checkout Page
// ===============================================

(function () {
  'use strict';

  // --- 1. Helper Functions ---
  const utils = {
    /**
     * Mengubah nilai string harga (e.g., "IDR 10.000") menjadi angka.
     * @param {string} str - String harga.
     * @returns {number} - Nilai angka dari harga.
     */
    parsePrice: (str) => {
      // Hapus semua karakter non-digit kecuali koma dan titik.
      const cleanStr = str.replace(/[^0-9,]/g, '').replace(',', '.');
      return parseFloat(cleanStr) || 0;
    },

    /**
     * Format angka menjadi mata uang Rupiah standar.
     * @param {number|string} num - Angka yang akan diformat.
     * @returns {string} - String mata uang Rupiah.
     */
    formatIDR: (num) => {
      const number = parseFloat(num);
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(number);
    }
  };

  // --- 2. DOM Elements Cache ---
  const checkoutContainer = document.querySelector('.checkout-container');
  if (!checkoutContainer) return;

  const mobileSummaryHeader = checkoutContainer.querySelector('.mobile-summary-header');
  const mobileSummaryContent = checkoutContainer.querySelector('.mobile-summary-content');

  // --- 3. Dynamic Province Data ---
  const provinceData = {
    "ID": ["Aceh", "Bali", "Banten", "Bengkulu", "DI Yogyakarta", "DKI Jakarta", "Gorontalo", "Jambi", "Jawa Barat", "Jawa Tengah", "Jawa Timur", "Kalimantan Barat", "Kalimantan Selatan", "Kalimantan Tengah", "Kalimantan Timur", "Kalimantan Utara", "Kepulauan Bangka Belitung", "Kepulauan Riau", "Lampung", "Maluku", "Maluku Utara", "Nusa Tenggara Barat", "Nusa Tenggara Timur", "Papua", "Papua Barat", "Riau", "Sulawesi Barat", "Sulawesi Selatan", "Sulawesi Tengah", "Sulawesi Tenggara", "Sulawesi Utara", "Sumatera Barat", "Sumatera Selatan", "Sumatera Utara"],
    "SG": ["Singapore"],
    "MY": ["Johor", "Kedah", "Kelantan", "Kuala Lumpur", "Labuan", "Melaka", "Negeri Sembilan", "Pahang", "Penang", "Perak", "Perlis", "Putrajaya", "Sabah", "Sarawak", "Selangor", "Terengganu"],
    "TH": ["Amnat Charoen", "Ang Thong", "Bangkok", "Bueng Kan", "Buri Ram", "Chachoengsao", "Chai Nat", "Chaiyaphum", "Chanthaburi", "Chiang Mai", "Chiang Rai", "Chon Buri", "Chumphon", "Kalasin", "Kamphaeng Phet", "Kanchanaburi", "Khon Kaen", "Krabi", "Lampang", "Lamphun", "Loei", "Lop Buri", "Mae Hong Son", "Maha Sarakham", "Mukdahan", "Nakhon Nayok", "Nakhon Pathom", "Nakhon Phanom", "Nakhon Ratchasima", "Nakhon Sawan", "Nakhon Si Thammarat", "Nan", "Narathiwat", "Nong Bua Lam Phu", "Nong Khai", "Nonthaburi", "Pathum Thani", "Pattani", "Phangnga", "Phatthalung", "Phayao", "Phetchabun", "Phetchaburi", "Phichit", "Phitsanulok", "Phra Nakhon Si Ayutthaya", "Phrae", "Phuket", "Prachin Buri", "Prachuap Khiri Khan", "Ranong", "Ratchaburi", "Rayong", "Roi Et", "Sa Kaeo", "Sakon Nakhon", "Samut Prakan", "Samut Sakhon", "Samut Songkhram", "Saraburi", "Satun", "Sing Buri", "Sisaket", "Songkhla", "Sukhothai", "Suphan Buri", "Surat Thani", "Surin", "Tak", "Trang", "Trat", "Ubon Ratchathani", "Udon Thani", "Uthai Thani", "Uttaradit", "Yala", "Yasothon"],
    "PH": ["Abra", "Agusan del Norte", "Agusan del Sur", "Aklan", "Albay", "Antique", "Apayao", "Aurora", "Basilan", "Bataan", "Batanes", "Batangas", "Benguet", "Biliran", "Bohol", "Bukidnon", "Bulacan", "Cagayan", "Camarines Norte", "Camarines Sur", "Camiguin", "Capiz", "Catanduanes", "Cavite", "Cebu", "Cotabato", "Davao de Oro", "Davao del Norte", "Davao del Sur", "Davao Occidental", "Davao Oriental", "Dinagat Islands", "Eastern Samar", "Guimaras", "Ifugao", "Ilocos Norte", "Ilocos Sur", "Iloilo", "Isabela", "Kalinga", "La Union", "Laguna", "Lanao del Norte", "Lanao del Sur", "Leyte", "Maguindanao", "Marinduque", "Masbate", "Metro Manila", "Misamis Occidental", "Misamis Oriental", "Mountain Province", "Negros Occidental", "Negros Oriental", "Northern Samar", "Nueva Ecija", "Nueva Vizcaya", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Pampanga", "Pangasinan", "Quezon", "Quirino", "Rizal", "Romblon", "Samar", "Sarangani", "Siquijor", "Sorsogon", "South Cotabato", "Southern Leyte", "Sultan Kudarat", "Sulu", "Surigao del Norte", "Surigao del Sur", "Tarlac", "Tawi-Tawi", "Zambales", "Zamboanga del Norte", "Zamboanga del Sur", "Zamboanga Sibugay"],
    "VN": ["An Giang", "Ba Ria-Vung Tau", "Bac Giang", "Bac Kan", "Bac Lieu", "Bac Ninh", "Ben Tre", "Binh Dinh", "Binh Duong", "Binh Phuoc", "Binh Thuan", "Ca Mau", "Can Tho", "Cao Bang", "Da Nang", "Dak Lak", "Dak Nong", "Dien Bien", "Dong Nai", "Dong Thap", "Gia Lai", "Ha Giang", "Ha Nam", "Ha Noi", "Ha Tinh", "Hai Duong", "Hai Phong", "Hau Giang", "Ho Chi Minh", "Hoa Binh", "Hung Yen", "Khanh Hoa", "Kien Giang", "Kon Tum", "Lai Chau", "Lam Dong", "Lang Son", "Lao Cai", "Long An", "Nam Dinh", "Nghe An", "Ninh Binh", "Ninh Thuan", "Phu Tho", "Phu Yen", "Quang Binh", "Quang Nam", "Quang Ngai", "Quang Ninh", "Quang Tri", "Soc Trang", "Son La", "Tay Ninh", "Thai Binh", "Thai Nguyen", "Thanh Hoa", "Thua Thien Hue", "Tien Giang", "Tra Vinh", "Tuyen Quang", "Vinh Long", "Vinh Phuc", "Yen Bai"],
    "BN": ["Belait", "Brunei-Muara", "Temburong", "Tutong"],
    "CN": ["Anhui", "Beijing", "Chongqing", "Fujian", "Gansu", "Guangdong", "Guangxi", "Guizhou", "Hainan", "Hebei", "Heilongjiang", "Henan", "Hubei", "Hunan", "Inner Mongolia", "Jiangsu", "Jiangxi", "Jilin", "Liaoning", "Ningxia", "Qinghai", "Shaanxi", "Shandong", "Shanghai", "Shanxi", "Sichuan", "Tianjin", "Tibet", "Xinjiang", "Yunnan", "Zhejiang"],
    "HK": ["Hong Kong"],
    "JP": ["Aichi", "Akita", "Aomori", "Chiba", "Ehime", "Fukui", "Fukuoka", "Fukushima", "Gifu", "Gunma", "Hiroshima", "Hokkaido", "Hyogo", "Ibaraki", "Ishikawa", "Iwate", "Kagawa", "Kagoshima", "Kanagawa", "Kochi", "Kumamoto", "Kyoto", "Mie", "Miyagi", "Miyazaki", "Nagano", "Nagasaki", "Nara", "Niigata", "Oita", "Okayama", "Okinawa", "Osaka", "Saga", "Saitama", "Shiga", "Shimane", "Shizuoka", "Tochigi", "Tokushima", "Tokyo", "Tottori", "Toyama", "Wakayama", "Yamagata", "Yamaguchi", "Yamanashi"],
    "KR": ["Busan", "Chungcheongbuk-do", "Chungcheongnam-do", "Daegu", "Daejeon", "Gangwon-do", "Gwangju", "Gyeonggi-do", "Gyeongsangbuk-do", "Gyeongsangnam-do", "Incheon", "Jeju-do", "Jeollabuk-do", "Jeollanam-do", "Sejong", "Seoul", "Ulsan"],
    "TW": ["Changhua", "Chiayi City", "Chiayi County", "Hsinchu City", "Hsinchu County", "Hualien", "Kaohsiung", "Keelung", "Kinmen", "Lienchiang", "Miaoli", "Nantou", "New Taipei", "Penghu", "Pingtung", "Taichung", "Tainan", "Taipei", "Taitung", "Taoyuan", "Yilan", "Yunlin"],
    "AU": ["Australian Capital Territory", "New South Wales", "Northern Territory", "Queensland", "South Australia", "Tasmania", "Victoria", "Western Australia"],
    "US": ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"],
    "GB": ["England", "Northern Ireland", "Scotland", "Wales"],
    "DE": ["Baden-Württemberg", "Bavaria", "Berlin", "Brandenburg", "Bremen", "Hamburg", "Hesse", "Lower Saxony", "Mecklenburg-Vorpommern", "North Rhine-Westphalia", "Rhineland-Palatinate", "Saarland", "Saxony", "Saxony-Anhalt", "Schleswig-Holstein", "Thuringia"]
  };

  // --- 4. Event Listeners ---

  // Handle toggle for mobile order summary
  mobileSummaryHeader?.addEventListener('click', () => {
    mobileSummaryContent?.classList.toggle('expanded');
    const icon = mobileSummaryHeader.querySelector('.fa-chevron-down');
    if (icon) {
      icon.style.transform = mobileSummaryContent?.classList.contains('expanded') ? 'rotate(180deg)' : 'rotate(0deg)';
    }
  });

  // Handle radio group selection
  checkoutContainer.querySelectorAll('.radio-group').forEach(group => {
    group.addEventListener('click', (e) => {
      const option = e.target.closest('.radio-option');
      if (!option) return;

      group.querySelectorAll('.radio-option').forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');

      const radioInput = option.querySelector('input[type="radio"]');
      if (radioInput) radioInput.checked = true;

      // Conditional logic for billing address
      if (group.id === 'billing-address-group') {
        const billingForm = document.getElementById('billing-address-form');
        if (radioInput?.id === 'billing-different') {
          billingForm?.classList.remove('hidden');
        } else {
          billingForm?.classList.add('hidden');
        }
      }
    });
  });

  // --- 5. Logic for Dynamic Provinces ---
  function initializeProvincesDropdowns() {
    function init(countryId, provinceId) {
      const countrySelect = document.getElementById(countryId);
      const provinceSelect = document.getElementById(provinceId);
      if (!countrySelect || !provinceSelect) return;

      function populateProvinces() {
        const selectedCountryCode = countrySelect.value;
        const provinces = provinceData[selectedCountryCode] || [];

        provinceSelect.innerHTML = '';
        if (provinces.length === 0) {
          provinceSelect.innerHTML = '<option value="">Provinsi/State tidak tersedia</option>';
          provinceSelect.disabled = true;
          return;
        }

        provinceSelect.disabled = false;
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Pilih Provinsi/State';
        placeholder.disabled = true;
        placeholder.selected = true;
        provinceSelect.appendChild(placeholder);

        provinces.forEach(provinceName => {
          const option = document.createElement('option');
          option.value = provinceName;
          option.textContent = provinceName;
          provinceSelect.appendChild(option);
        });
      }

      countrySelect.addEventListener('change', populateProvinces);
      populateProvinces(); // Call on page load
    }

    init('shipping-country', 'shipping-province');
    init('billing-country', 'billing-province');
  }

  initializeProvincesDropdowns();

  // --- 6. Discount Code Logic (Backend-Ready) ---
  // Ini adalah contoh yang aman, di mana logika diskon ada di backend.
  checkoutContainer.querySelector('#apply-discount-btn')?.addEventListener('click', async (e) => {
    const button = e.target;
    const input = button.previousElementSibling;
    const discountCode = input?.value.trim() || '';

    if (!discountCode) {
      alert('Masukkan kode diskon terlebih dahulu.');
      return;
    }

    // Tampilkan loading state
    button.disabled = true;
    button.textContent = 'Menerapkan...';

    try {
      // KIRIM KODE DISKON KE BACKEND
      const response = await fetch('/api/apply-discount', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({ code: discountCode })
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || 'Kode diskon tidak valid atau terjadi kesalahan.');
      }

      // Backend akan mengembalikan total harga yang sudah didiskon.
      const newSubtotal = result.data.subtotal;
      const discountAmount = result.data.discountAmount;
      const newTotal = result.data.total;

      // Update UI dengan nilai dari BACKEND
      document.querySelectorAll('#summary-subtotal').forEach(el => el.textContent = utils.formatIDR(newSubtotal));
      document.querySelectorAll('#summary-discount-amount').forEach(el => el.textContent = `- ${utils.formatIDR(discountAmount)}`);
      document.querySelectorAll('#discount-line').forEach(el => el.classList.remove('hidden'));
      document.querySelectorAll('#summary-total').forEach(el => el.innerHTML = `IDR <strong>${utils.formatIDR(newTotal)}</strong>`);

      alert('Kode diskon berhasil diterapkan!');
      
      // Menonaktifkan input dan tombol setelah sukses
      input.disabled = true;
      button.textContent = 'Berhasil';

    } catch (error) {
      console.error('Error applying discount:', error);
      alert(`Gagal menerapkan kode: ${error.message}`);
      
      // Kembalikan tombol ke keadaan semula jika gagal
      button.disabled = false;
      button.textContent = 'Terapkan';
    }
  });

})();

 // =============================
  // 9) FAB Language + Currency (floating)
  // =============================
  (function () {
    const root  = document.getElementById('fab-prefs');
    if (!root) return;

    const btn   = document.getElementById('fab-prefs-btn');
    const sheet = document.getElementById('fab-prefs-sheet');
    const close = document.getElementById('fab-prefs-close');
    const list  = document.getElementById('fab-prefs-list');
    const form  = document.getElementById('fab-pref-form');
    const fLoc  = document.getElementById('fab-locale');
    const fCur  = document.getElementById('fab-currency');

    const open = () => { sheet.hidden = false; btn.setAttribute('aria-expanded','true'); };
    const hide = () => { sheet.hidden = true;  btn.setAttribute('aria-expanded','false'); };

    btn?.addEventListener('click', (e)=>{ e.preventDefault(); sheet.hidden ? open() : hide(); });
    close?.addEventListener('click', hide);
    document.addEventListener('click', (e)=>{ if (!root.contains(e.target)) hide(); });

    const currencyByLocale = (loc) => {
      const n = (loc||'').toLowerCase();
      if (n.startsWith('id')) return 'IDR';
      if (n.startsWith('ms') || n.includes('my')) return 'MYR';
      if (n.startsWith('th')) return 'THB';
      if (n.startsWith('ko') || n.includes('kr')) return 'KRW';
      if (n.startsWith('ja') || n.includes('jp')) return 'JPY';
      if (n.startsWith('de') || n.startsWith('fr') || n.startsWith('es') || n.startsWith('it')) return 'EUR';
      return 'USD';
    };

    const submitPref = (locale, currency) => {
      fLoc.value = locale;
      fCur.value = currency;
      form.submit(); // POST ke route('pref.update')
    };

    list?.addEventListener('click', (e) => {
      const opt = e.target.closest('.fab-prefs__opt'); if (!opt) return;

      if (opt.dataset.auto) {
        const nav = (navigator.language || 'en-US').toLowerCase(); // ex: id-ID
        const loc = nav.split('-')[0]; // 'id'
        const cur = currencyByLocale(nav);
        submitPref(loc, cur);
        return;
      }
      const loc = opt.dataset.locale || 'en';
      const cur = opt.dataset.currency || currencyByLocale(loc);
      submitPref(loc, cur);
    });
  })();

}); // DOMContentLoaded end