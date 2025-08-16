// Menunggu hingga seluruh konten halaman (HTML) selesai dimuat sebelum menjalankan skrip
document.addEventListener('DOMContentLoaded', function () {

  // =======================================================
  // HELPER (bisa dipakai di cart/search)
  // =======================================================

  function setPrice(el, baseIdrNumber) {
    if (!el) return;
    el.classList.add('price');
    el.setAttribute('data-price', String(Math.max(0, Number(baseIdrNumber || 0))));
    // render ulang harga (kalau currency-lang.js ada)
    window.__pricesRefresh && window.__pricesRefresh();
  }

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
    const logoutBtnMobile = document.getElementById('logout-btn');
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
  // MOBILE NAV (PROD-READY)
  // =======================================================
  (function () {
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('page-overlay');
    const openBtn = document.getElementById('hamburger-btn');
    const closeBtn = document.getElementById('close-menu-btn');

    if (!menu || !overlay || !openBtn || !closeBtn) return;

    const openMenu = () => {
      menu.classList.add('show');
      overlay.classList.add('show');
      menu.setAttribute('aria-hidden', 'false');
      document.body.classList.add('no-scroll');
      const firstLink = menu.querySelector('a, button');
      firstLink && firstLink.focus({ preventScroll: true });
    };

    const closeMenu = () => {
      menu.classList.remove('show');
      overlay.classList.remove('show');
      menu.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('no-scroll');
      openBtn.focus({ preventScroll: true });
    };

    openBtn.addEventListener('click', (e) => { e.preventDefault(); openMenu(); });
    closeBtn.addEventListener('click', (e) => { e.preventDefault(); closeMenu(); });
    overlay.addEventListener('click', closeMenu);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && menu.classList.contains('show')) closeMenu();
    });

    const dropBtn = menu.querySelector('.dropbtn-mobile');
    const dropCt = dropBtn ? dropBtn.nextElementSibling : null;

    if (dropBtn && dropCt) {
      dropBtn.setAttribute('aria-expanded', 'false');
      dropBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const isOpen = dropBtn.getAttribute('aria-expanded') === 'true';
        dropBtn.setAttribute('aria-expanded', String(!isOpen));
        dropBtn.classList.toggle('active', !isOpen);
        dropCt.classList.toggle('show', !isOpen);
      });
    }

    const logoutBtn = document.getElementById('logout-btn');
    const logoutForm = document.getElementById('logout-form');
    if (logoutBtn && logoutForm) {
      logoutBtn.addEventListener('click', (e) => { e.preventDefault(); logoutForm.submit(); });
    }
  })();

 // =======================================================
  // BAGIAN 3: SEARCH BAR (UI + BACKEND /search)
  // =======================================================
  (function () {
    const searchContainer = document.querySelector('.search-container');
    const searchButton = document.querySelector('.search-btn-header');
    const searchInputHeader = document.querySelector('.search-input-header');
    const searchResultsBox = document.getElementById('search-results');

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
            <em class="price" data-price="${Number(p.price || 0)}"></em>
          </a>
        `).join('');

        // render ulang harga setelah DOM disuntik
        window.__pricesRefresh && window.__pricesRefresh();
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
      document.addEventListener('click', (e) => {
        const inside = e.target.closest('.search-container');
        if (!inside) searchResultsBox.innerHTML = '';
      });
    }
  })();

// =======================================================
// BAGIAN 4: INISIALISASI SWIPER JS UNTUK BANNER (safe-init)
// =======================================================
(function () {
  const hasContainer = document.querySelector('.swiper'); // pastikan ada container
  const hasSwiper = typeof window !== 'undefined' && typeof window.Swiper !== 'undefined'; // pastikan lib ada
  if (!hasContainer || !hasSwiper) return;

  // pakai `void` supaya tidak warning "unused variable"
  void new window.Swiper('.swiper', {
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
})();

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

  const productImageElement   = document.getElementById('product-image');
  const productPriceElement   = document.getElementById('product-price');
  const quantityInputElement  = productDetailContainer.querySelector('.quantity-input input');
  const addToCartButton       = productDetailContainer.querySelector('.btn-add-to-cart');
  const buyNowButton          = productDetailContainer.querySelector('.btn-buy-now');

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
        // NOTE: setPrice diasumsikan global dari util harga kamu
        //       (fungsi ini tidak didefinisikan di file ini)
        setPrice(productPriceElement, newPrice); // base IDR
      }
    });
  });

  // Pilih warna pertama secara default jika ada
  if (colorOptions.length > 0) {
    colorOptions[0].click();
  }

  // --- 4b. BUTTON EVENTS ---
  addToCartButton?.addEventListener('click', () => {
    handleFormSubmit('add-to-cart');
  });

  buyNowButton?.addEventListener('click', () => {
    handleFormSubmit('buy-now');
  });

  // --- 5. LOADING HELPER (baru, mengganti logic disable massal) ---
  // Ganti bagian set tombol loading & submit:
  function setLoading(btn, isLoading) {
    if (!btn) return;
    btn.disabled = isLoading;
    btn.dataset.origText ??= btn.textContent;
    btn.textContent = isLoading ? 'Memproses...' : btn.dataset.origText;
  }

  // --- 6. LOGIC FOR SUBMITTING DATA (versi revised, tanpa duplikasi) ---
  async function handleFormSubmit(action) {
    // Validasi Sisi Klien
    if (!state.selectedSize) { alert('Pilih ukuran produk terlebih dahulu.'); return; }
    // Hanya validasi warna jika ada pilihan warna di halaman
    if (colorOptions.length > 0 && !state.selectedColor) { alert('Pilih warna produk terlebih dahulu.'); return; }

    // pastikan qty minimal 1
    const qty = Math.max(1, utils.toInt(quantityInputElement?.value, 1));

    // Buat payload
    const payload = {
      product_id: state.productId,
      color: state.selectedColor,
      size: state.selectedSize,
      qty
    };

    // Hanya disable tombol yang diklik
    const clickedBtn = (action === 'add-to-cart') ? addToCartButton : buyNowButton;
    setLoading(clickedBtn, true);

    try {
      const urlMap = { 'add-to-cart': '/add-to-cart', 'buy-now': '/buy-now' };
      const url = urlMap[action];
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        let msg = `HTTP ${response.status}`;
        try { const j = await response.json(); if (j?.message) msg = j.message; } catch {}
        throw new Error(msg);
      }

      const destination = { 'add-to-cart': '/cart', 'buy-now': '/checkout' };
      window.location.href = destination[action];

    } catch (error) {
      console.error('Gagal mengirim data ke server:', error);
      alert(`Terjadi kesalahan: ${error.message}. Silakan coba lagi.`);
      setLoading(clickedBtn, false);
    }
  }
})();


// =======================================================
// CART PAGE (Modal remove + auto submit qty via hidden submit button)
// =======================================================
(function () {
  'use strict';

  const list = document.querySelector('.cart-items-list');
  if (!list) return;

  // --- helpers (scoped, biar nggak tabrakan dgn global lain) ---
  const parsePriceLocal = (s) => parseInt(String(s || '').replace(/[^\d]/g, ''), 10) || 0;
  const debounceLocal = (fn, ms = 400) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; };

  // --- modal refs ---
  const overlay  = document.getElementById('modal-overlay');
  const modal    = document.getElementById('confirmation-modal');
  const btnNo    = document.getElementById('cancel-btn');
  const btnYes   = document.getElementById('confirm-delete-btn');
  const modalTxt = document.getElementById('modal-text');

  function showModal() { overlay?.classList.remove('hidden'); modal?.classList.remove('hidden'); }
  function hideModal() { overlay?.classList.add('hidden');   modal?.classList.add('hidden');   }

  let removeFormId = null;

  // submit qty via tombol submit "asli" (hidden) -> aman CSRF (Laravel)
  function submitQty(row) {
    const btn = row.querySelector('.js-submit-qty');
    if (btn) btn.click();
  }

  function updateRowTotal(row) {
    const priceEl = row.querySelector('.item-price');
    let unitBase = 0;

    if (priceEl?.dataset?.price) {
      // base IDR dari Blade (paling akurat)
      unitBase = parseInt(priceEl.dataset.price, 10) || 0;
    } else {
      // fallback dari teks tampilan (kurang presisi kalau non-IDR)
      unitBase = parsePriceLocal(priceEl?.textContent || '0');
    }

    const qtyInput = row.querySelector('.js-qty');
    const qty = Math.max(1, parseInt(qtyInput?.value || '1', 10));
    const out = row.querySelector('.item-total');

    if (out) setPrice(out, unitBase * qty); // pakai helper harga global kamu
    updateSubtotal();
  }

  function updateSubtotal() {
    let subtotal = 0;

    document.querySelectorAll('.cart-items-list .cart-item').forEach(row => {
      const priceEl = row.querySelector('.item-price');
      let unitBase = 0;

      if (priceEl?.dataset?.price) {
        unitBase = parseInt(priceEl.dataset.price, 10) || 0;
      } else {
        unitBase = parsePriceLocal(priceEl?.textContent || '0');
      }

      const qtyInput = row.querySelector('.js-qty');
      const qty = Math.max(1, parseInt(qtyInput?.value || '1', 10));

      subtotal += unitBase * qty;
    });

    const el = document.getElementById('subtotal-value');
    if (el) setPrice(el, subtotal);
  }

  // --- CLICK HANDLERS (plus/minus & remove) ---
  list.addEventListener('click', (e) => {
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
      const label = e.target.textContent.trim();
      if (label === '+') v += 1;
      if (label === '-') v = Math.max(1, v - 1);

      input.value = v;
      updateRowTotal(row);
      submitQty(row); // kirim PATCH via tombol submit hidden
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

  // --- INPUT HANDLER (ketik qty manual → debounce submit) ---
  const debouncedSubmit = debounceLocal((row) => submitQty(row), 500);
  list.addEventListener('input', (e) => {
    if (!e.target.matches('.js-qty')) return;
    const row = e.target.closest('.cart-item'); 
    if (!row) return;

    if (!e.target.value || parseInt(e.target.value, 10) < 1) e.target.value = 1;
    updateRowTotal(row);
    debouncedSubmit(row);
  });

  // --- modal actions ---
  btnYes?.addEventListener('click', () => {
    if (removeFormId) document.getElementById(removeFormId)?.submit(); // DELETE ke server
    hideModal();
  });
  btnNo?.addEventListener('click', hideModal);
  overlay?.addEventListener('click', hideModal);

  // --- init ---
  updateSubtotal();
})();


// ===============================================
// checkout.js - Logic for Checkout Page
// ===============================================
(function () {
  'use strict';

  // --- 1. Helpers ---
  const utils = {
    // Parse "Rp 100.000" / "IDR 100.000" / "100.000" / "100,5"
    parsePrice: (str = '') => {
      const s = String(str)
        .replace(/[^0-9.,]/g, '')  // keep digits, comma, dot
        .replace(/\./g, '')        // remove thousand separators
        .replace(',', '.');        // normalize decimal
      return parseFloat(s) || 0;
    },
    formatIDR: (num) => {
      const number = Number(num) || 0;
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(number);
    }
  };

  // --- 2. DOM ---
  const checkoutContainer = document.querySelector('.checkout-container');
  if (!checkoutContainer) return;

  const mobileSummaryHeader  = checkoutContainer.querySelector('.mobile-summary-header');
  const mobileSummaryContent = checkoutContainer.querySelector('.mobile-summary-content');

  // --- 3. Provinces (lengkap) ---
  const provinceData = {
    "ID": ["Aceh","Bali","Banten","Bengkulu","DI Yogyakarta","DKI Jakarta","Gorontalo","Jambi","Jawa Barat","Jawa Tengah","Jawa Timur","Kalimantan Barat","Kalimantan Selatan","Kalimantan Tengah","Kalimantan Timur","Kalimantan Utara","Kepulauan Bangka Belitung","Kepulauan Riau","Lampung","Maluku","Maluku Utara","Nusa Tenggara Barat","Nusa Tenggara Timur","Papua","Papua Barat","Riau","Sulawesi Barat","Sulawesi Selatan","Sulawesi Tengah","Sulawesi Tenggara","Sulawesi Utara","Sumatera Barat","Sumatera Selatan","Sumatera Utara"],
    "SG": ["Singapore"],
    "MY": ["Johor","Kedah","Kelantan","Kuala Lumpur","Labuan","Melaka","Negeri Sembilan","Pahang","Penang","Perak","Perlis","Putrajaya","Sabah","Sarawak","Selangor","Terengganu"],
    "TH": ["Amnat Charoen","Ang Thong","Bangkok","Bueng Kan","Buri Ram","Chachoengsao","Chai Nat","Chaiyaphum","Chanthaburi","Chiang Mai","Chiang Rai","Chon Buri","Chumphon","Kalasin","Kamphaeng Phet","Kanchanaburi","Khon Kaen","Krabi","Lampang","Lamphun","Loei","Lop Buri","Mae Hong Son","Maha Sarakham","Mukdahan","Nakhon Nayok","Nakhon Pathom","Nakhon Phanom","Nakhon Ratchasima","Nakhon Sawan","Nakhon Si Thammarat","Nan","Narathiwat","Nong Bua Lam Phu","Nong Khai","Nonthaburi","Pathum Thani","Pattani","Phangnga","Phatthalung","Phayao","Phetchabun","Phetchaburi","Phichit","Phitsanulok","Phra Nakhon Si Ayutthaya","Phrae","Phuket","Prachin Buri","Prachuap Khiri Khan","Ranong","Ratchaburi","Rayong","Roi Et","Sa Kaeo","Sakon Nakhon","Samut Prakan","Samut Sakhon","Samut Songkhram","Saraburi","Satun","Sing Buri","Sisaket","Songkhla","Sukhothai","Suphan Buri","Surat Thani","Surin","Tak","Trang","Trat","Ubon Ratchathani","Udon Thani","Uthai Thani","Uttaradit","Yala","Yasothon"],
    "PH": ["Abra","Agusan del Norte","Agusan del Sur","Aklan","Albay","Antique","Apayao","Aurora","Basilan","Bataan","Batanes","Batangas","Benguet","Biliran","Bohol","Bukidnon","Bulacan","Cagayan","Camarines Norte","Camarines Sur","Camiguin","Capiz","Catanduanes","Cavite","Cebu","Cotabato","Davao de Oro","Davao del Norte","Davao del Sur","Davao Occidental","Davao Oriental","Dinagat Islands","Eastern Samar","Guimaras","Ifugao","Ilocos Norte","Ilocos Sur","Iloilo","Isabela","Kalinga","La Union","Laguna","Lanao del Norte","Lanao del Sur","Leyte","Maguindanao","Marinduque","Masbate","Metro Manila","Misamis Occidental","Misamis Oriental","Mountain Province","Negros Occidental","Negros Oriental","Northern Samar","Nueva Ecija","Nueva Vizcaya","Occidental Mindoro","Oriental Mindoro","Palawan","Pampanga","Pangasinan","Quezon","Quirino","Rizal","Romblon","Samar","Sarangani","Siquijor","Sorsogon","South Cotabato","Southern Leyte","Sultan Kudarat","Sulu","Surigao del Norte","Surigao del Sur","Tarlac","Tawi-Tawi","Zambales","Zamboanga del Norte","Zamboanga del Sur","Zamboanga Sibugay"],
    "VN": ["An Giang","Ba Ria-Vung Tau","Bac Giang","Bac Kan","Bac Lieu","Bac Ninh","Ben Tre","Binh Dinh","Binh Duong","Binh Phuoc","Binh Thuan","Ca Mau","Can Tho","Cao Bang","Da Nang","Dak Lak","Dak Nong","Dien Bien","Dong Nai","Dong Thap","Gia Lai","Ha Giang","Ha Nam","Ha Noi","Ha Tinh","Hai Duong","Hai Phong","Hau Giang","Ho Chi Minh","Hoa Binh","Hung Yen","Khanh Hoa","Kien Giang","Kon Tum","Lai Chau","Lam Dong","Lang Son","Lao Cai","Long An","Nam Dinh","Nghe An","Ninh Binh","Ninh Thuan","Phu Tho","Phu Yen","Quang Binh","Quang Nam","Quang Ngai","Quang Ninh","Quang Tri","Soc Trang","Son La","Tay Ninh","Thai Binh","Thai Nguyen","Thanh Hoa","Thua Thien Hue","Tien Giang","Tra Vinh","Tuyen Quang","Vinh Long","Vinh Phuc","Yen Bai"],
    "BN": ["Belait","Brunei-Muara","Temburong","Tutong"],
    "CN": ["Anhui","Beijing","Chongqing","Fujian","Gansu","Guangdong","Guangxi","Guizhou","Hainan","Hebei","Heilongjiang","Henan","Hubei","Hunan","Inner Mongolia","Jiangsu","Jiangxi","Jilin","Liaoning","Ningxia","Qinghai","Shaanxi","Shandong","Shanghai","Shanxi","Sichuan","Tianjin","Tibet","Xinjiang","Yunnan","Zhejiang"],
    "HK": ["Hong Kong"],
    "JP": ["Aichi","Akita","Aomori","Chiba","Ehime","Fukui","Fukuoka","Fukushima","Gifu","Gunma","Hiroshima","Hokkaido","Hyogo","Ibaraki","Ishikawa","Iwate","Kagawa","Kagoshima","Kanagawa","Kochi","Kumamoto","Kyoto","Mie","Miyagi","Miyazaki","Nagano","Nagasaki","Nara","Niigata","Oita","Okayama","Okinawa","Osaka","Saga","Saitama","Shiga","Shimane","Shizuoka","Tochigi","Tokushima","Tokyo","Tottori","Toyama","Wakayama","Yamagata","Yamaguchi","Yamanashi"],
    "KR": ["Busan","Chungcheongbuk-do","Chungcheongnam-do","Daegu","Daejeon","Gangwon-do","Gwangju","Gyeonggi-do","Gyeongsangbuk-do","Gyeongsangnam-do","Incheon","Jeju-do","Jeollabuk-do","Jeollanam-do","Sejong","Seoul","Ulsan"],
    "TW": ["Changhua","Chiayi City","Chiayi County","Hsinchu City","Hsinchu County","Hualien","Kaohsiung","Keelung","Kinmen","Lienchiang","Miaoli","Nantou","New Taipei","Penghu","Pingtung","Taichung","Tainan","Taipei","Taitung","Taoyuan","Yilan","Yunlin"],
    "AU": ["Australian Capital Territory","New South Wales","Northern Territory","Queensland","South Australia","Tasmania","Victoria","Western Australia"],
    "US": ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"],
    "GB": ["England","Northern Ireland","Scotland","Wales"],
    "DE": ["Baden-Württemberg","Bavaria","Berlin","Brandenburg","Bremen","Hamburg","Hesse","Lower Saxony","Mecklenburg-Vorpommern","North Rhine-Westphalia","Rhineland-Palatinate","Saarland","Saxony","Saxony-Anhalt","Schleswig-Holstein","Thuringia"]
  };

  // --- 4. Mobile summary toggle ---
  mobileSummaryHeader?.addEventListener('click', () => {
    const expanded = mobileSummaryContent?.classList.toggle('expanded');
    const icon = mobileSummaryHeader.querySelector('.fa-chevron-down');
    if (icon) icon.style.transform = expanded ? 'rotate(180deg)' : 'rotate(0deg)';
    mobileSummaryHeader.setAttribute('aria-expanded', expanded ? 'true' : 'false');
  });

  // --- 5. Radio group (billing same/different) ---
  checkoutContainer.querySelectorAll('.radio-group').forEach(group => {
    group.addEventListener('click', (e) => {
      const option = e.target.closest('.radio-option');
      if (!option) return;

      group.querySelectorAll('.radio-option').forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');

      const radioInput = option.querySelector('input[type="radio"]');
      if (radioInput) radioInput.checked = true;

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

  // --- 6. Provinces dropdown init ---
  function initializeProvincesDropdowns() {
    function init(countryId, provinceId) {
      const countrySelect  = document.getElementById(countryId);
      const provinceSelect = document.getElementById(provinceId);
      if (!countrySelect || !provinceSelect) return;

      function populateProvinces() {
        const code = countrySelect.value;
        const provinces = provinceData[code] || [];

        const prev = provinceSelect.value; // try keep previous selection
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

        provinces.forEach(name => {
          const opt = document.createElement('option');
          opt.value = name;
          opt.textContent = name;
          provinceSelect.appendChild(opt);
        });

        // restore if still valid
        if (provinces.includes(prev)) {
          provinceSelect.value = prev;
          placeholder.selected = false;
        }
      }

      countrySelect.addEventListener('change', populateProvinces);
      populateProvinces();
    }

    init('shipping-country', 'shipping-province');
    init('billing-country', 'billing-province');
  }
  initializeProvincesDropdowns();

  // --- 7. Discount Code (Backend-Ready) ---
  checkoutContainer.querySelector('#apply-discount-btn')?.addEventListener('click', async (e) => {
    const button = e.currentTarget;
    const input  = button?.previousElementSibling;
    const discountCode = input?.value.trim() || '';

    if (!discountCode) {
      alert('Masukkan kode diskon terlebih dahulu.');
      return;
    }

    // loading state
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Menerapkan...';

    try {
      const response = await fetch('/checkout/apply-discount', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ code: discountCode })
      });

      let result = {};
      try { result = await response.json(); } catch { /* ignore json errors */ }

      if (!response.ok || !result?.success) {
        throw new Error(result?.message || 'Kode diskon tidak valid atau terjadi kesalahan.');
      }

      const { subtotal: newSubtotal = 0, discountAmount = 0, total: newTotal = 0 } = result.data || {};

      document.querySelectorAll('#summary-subtotal').forEach(el => setPrice(el, newSubtotal));
      document.querySelectorAll('#summary-discount-amount').forEach(el => {
        setPrice(el, Math.abs(discountAmount)); // tampilkan positif
      });
      document.querySelectorAll('#discount-line').forEach(el => el.classList.remove('hidden'));
      document.querySelectorAll('#summary-total').forEach(el => {
        el.innerHTML = '<strong class="price" data-price="' + Number(newTotal || 0) + '"></strong>';
      });
      window.__pricesRefresh && window.__pricesRefresh();

      alert('Kode diskon berhasil diterapkan!');
      if (input) input.disabled = true;
      button.textContent = 'Berhasil';

    } catch (err) {
      console.error('Error applying discount:', err);
      alert(`Gagal menerapkan kode: ${err.message}`);
      button.disabled = false;
      button.textContent = originalText;
    }
  });
})();

// =============================
// FAB Language + Currency (floating)
// =============================
(function () {
  'use strict';

  const root  = document.getElementById('fab-prefs');
  if (!root) return; // FAB tidak ada di halaman ini, skip

  const btn   = document.getElementById('fab-prefs-btn');
  const sheet = document.getElementById('fab-prefs-sheet');
  const close = document.getElementById('fab-prefs-close');
  const list  = document.getElementById('fab-prefs-list');
  const form  = document.getElementById('fab-pref-form');
  const fLoc  = document.getElementById('fab-locale');
  const fCur  = document.getElementById('fab-currency');

  if (!btn || !sheet) return;

  const open = () => { sheet.hidden = false; btn.setAttribute('aria-expanded','true'); };
  const hide = () => { sheet.hidden = true;  btn.setAttribute('aria-expanded','false'); };

  // toggle open/close
  btn.addEventListener('click', (e)=>{ e.preventDefault(); sheet.hidden ? open() : hide(); });
  close?.addEventListener('click', hide);
  document.addEventListener('click', (e)=>{ if (!root.contains(e.target)) hide(); });
  document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') hide(); });

  // map locale -> default currency
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

  // optional: submit via hidden form (kalau mau full server-side)
  const submitPref = (locale, currency) => {
    if (!form || !fLoc || !fCur) return;
    fLoc.value = locale;
    fCur.value = currency;
    form.submit(); // POST ke route('pref.update')
  };

  // apply preference (shared)
  function applyPreference(loc, cur, {persist=true, sync=true} = {}) {
    const locale   = (loc || 'id-ID');
    const currency = (cur || currencyByLocale(locale)).toUpperCase();

    if (persist) {
      localStorage.setItem('pref_locale', locale);
      localStorage.setItem('pref_currency', currency);
    }

    // re-render harga di UI
    window.__pricesRefresh && window.__pricesRefresh(currency, locale);

    // sync ke backend (silent)
    if (sync) {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
      if (csrf) {
        fetch('/pref', {
          method:'POST',
          headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
          body: JSON.stringify({ locale, currency })
        }).catch(()=>{ /* silent */ });
      }
    }
  }

  // click pada opsi
  list?.addEventListener('click', (e) => {
    const opt = e.target.closest('.fab-prefs__opt');
    if (!opt) return;
    e.preventDefault();

    const rawLoc = opt.dataset.locale || localStorage.getItem('pref_locale') || 'id-ID';
    const loc    = rawLoc.toLowerCase().startsWith('id') ? 'id-ID' : rawLoc;
    const cur    = (opt.dataset.currency || currencyByLocale(loc)).toUpperCase();

    applyPreference(loc, cur, {persist:true, sync:true});
    hide();

    // kalau mau pakai form hidden instead of fetch:
    // submitPref(loc, cur);
  });

  // restore preferensi saat load (kalau ada)
  (function restoreOnLoad(){
    const savedLoc = localStorage.getItem('pref_locale');
    const savedCur = localStorage.getItem('pref_currency');
    if (savedLoc || savedCur) {
      applyPreference(savedLoc || 'id-ID', (savedCur || currencyByLocale(savedLoc || 'id-ID')), {persist:false, sync:false});
    }
  })();
})();
});
