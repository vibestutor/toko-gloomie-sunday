// Menunggu hingga seluruh konten halaman (HTML) selesai dimuat sebelum menjalankan skrip
document.addEventListener('DOMContentLoaded', function () {

    // =======================================================
    // FUNGSI HELPER (Didefinisikan di atas agar bisa dipakai semua)
    // =======================================================
    const parsePrice = (priceString) => {
        if (!priceString) return 0;
        return parseFloat(priceString.replace(/[^0-9]/g, ''));
    };

    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

// =======================================================
// BAGIAN 0: LOGIKA POPUP DAN STATUS LOGIN (VERSI BARU)
// =======================================================

// --- Pengaturan Elemen ---
const wrapper = document.querySelector('.wrapper'); // Popup Login/Register
const forgotWrapper = document.getElementById('forgot-password-wrapper'); // Popup Forgot Password
const loginLink = document.querySelectorAll('.login-link'); // Sekarang ada 2 link login
const registerLink = document.querySelector('.register-link');
const btnLoginPopup = document.getElementById('login-btn-popup');
const iconClose = document.querySelectorAll('.icon-close'); // Sekarang ada 2 tombol close
const loginFormBtn = document.querySelector('.form-box.login .btn');
const forgotPasswordLink = document.querySelector('.remember-forgot a');
const resetPasswordBtn = document.querySelector('#forgot-password-wrapper .btn'); // Tombol Reset
const cartIcon = document.getElementById('cart-icon');
const logoutBtnDesktop = document.getElementById('logout-btn-desktop');
const logoutBtnMobile = document.getElementById('logout-btn');

// --- Fungsi untuk Memeriksa Status Login & Mengatur Ikon ---
function updateLoginStatus() {
    if (localStorage.getItem('isLoggedIn') === 'true') {
        if (btnLoginPopup) btnLoginPopup.style.display = 'none';
        if (cartIcon) cartIcon.classList.remove('hidden');
        if (logoutBtnDesktop) logoutBtnDesktop.classList.remove('hidden');
        if (logoutBtnMobile && logoutBtnMobile.parentElement) logoutBtnMobile.parentElement.style.display = 'block';
    } else {
        if (btnLoginPopup) btnLoginPopup.style.display = 'flex';
        if (cartIcon) cartIcon.classList.add('hidden');
        if (logoutBtnDesktop) logoutBtnDesktop.classList.add('hidden');
        if (logoutBtnMobile && logoutBtnMobile.parentElement) logoutBtnMobile.parentElement.style.display = 'none';
    }
}

// --- Event Listeners untuk Semua Popup ---
if (wrapper && forgotWrapper) {
    // Klik link 'Register'
    registerLink.addEventListener('click', () => wrapper.classList.add('active'));

    // Klik link 'Login' (di kedua popup)
    loginLink.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            wrapper.classList.remove('active');
            forgotWrapper.classList.remove('active-popup');
            wrapper.classList.add('active-popup');
        });
    });

    // Klik tombol 'Login' di header
    btnLoginPopup.addEventListener('click', (event) => {
        event.preventDefault();
        wrapper.classList.add('active-popup');
    });

    // Klik tombol 'X' (di kedua popup)
    iconClose.forEach(btn => {
        btn.addEventListener('click', () => {
            wrapper.classList.remove('active-popup');
            forgotWrapper.classList.remove('active-popup');
        });
    });

    // Klik link 'Forgot Password?'
    forgotPasswordLink.addEventListener('click', (event) => {
        event.preventDefault();
        wrapper.classList.remove('active-popup'); // Sembunyikan popup login
        forgotWrapper.classList.add('active-popup'); // Tampilkan popup forgot
    });
    
    // Tombol 'Login' di dalam popup
    loginFormBtn.addEventListener('click', (event) => {
        event.preventDefault();
        localStorage.setItem('isLoggedIn', 'true');
        updateLoginStatus();
        wrapper.classList.remove('active-popup');
        alert('Login Berhasil!');
    });

    // Tombol 'Reset' di dalam popup (Backend-ready)
    resetPasswordBtn.addEventListener('click', function(event) {
        event.preventDefault();
        const emailInput = document.querySelector('#forgot-password-wrapper input[type="email"]');
        if (!emailInput.value) {
            alert('Silakan masukkan email Anda.');
            return;
        }
        alert('Mengirim permintaan reset password...');
        
        // Logika fetch tetap di sini, siap untuk backend
        fetch('/api/forgot-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: emailInput.value })
        })
        .then(response => {
            if (!response.ok) { throw new Error('Server not ready'); }
            return response.json();
        })
        .then(result => {
            alert(result.message);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal terhubung ke server. Fitur ini belum aktif.');
        });
        
        forgotWrapper.classList.remove('active-popup');
    });
}

// Panggil fungsi status login saat halaman dimuat
updateLoginStatus();

// --- Logika Logout ---
function handleLogout(event) {
    event.preventDefault();
    localStorage.removeItem('isLoggedIn');
    updateLoginStatus();
    alert('Anda berhasil logout!');
    window.location.reload();
}
if (logoutBtnMobile) logoutBtnMobile.addEventListener('click', handleLogout);
if (logoutBtnDesktop) logoutBtnDesktop.addEventListener('click', handleLogout);

    // =======================================================
    // BAGIAN 1: LOGIKA UMUM & NAVIGASI
    // =======================================================
    const header = document.getElementById('main-header');

    // Hanya jalankan logika scroll jika elemen header ditemukan
    if (header) {
        // Fungsi ini akan dijalankan setiap kali pengguna scroll
        const handleScroll = () => {
            // Jika header TIDAK punya class 'header-solid' (artinya kita di homepage)
            if (!header.classList.contains('header-solid')) {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            }
        };
        
        // Tambahkan "pendengar" untuk event scroll
        window.addEventListener('scroll', handleScroll);
    }

    // =======================================================
    // BAGIAN 2: LOGIKA NAVIGASI MOBILE (SIDEBAR)
    // =======================================================
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMenuBtn = document.getElementById('close-menu-btn');
    const pageOverlay = document.getElementById('page-overlay');

    const toggleMenu = () => {
        if (mobileMenu) mobileMenu.classList.toggle('show');
        if (pageOverlay) pageOverlay.classList.toggle('show');
    };

    if (hamburgerBtn) hamburgerBtn.addEventListener('click', toggleMenu);
    if (closeMenuBtn) closeMenuBtn.addEventListener('click', toggleMenu);
    if (pageOverlay) pageOverlay.addEventListener('click', toggleMenu);

    const dropbtnMobile = document.querySelector('.dropbtn-mobile');
    if (dropbtnMobile) {
        dropbtnMobile.addEventListener('click', function(event) {
            event.preventDefault();
            const dropdownContent = this.nextElementSibling;
            if (dropdownContent) {
                dropdownContent.classList.toggle('show');
            }
            this.classList.toggle('active');
        });
    }

    // =======================================================
    // BAGIAN 3: LOGIKA SEARCH BAR (HANYA ANIMASI BUKA/TUTUP)
    // =======================================================
    const searchContainer = document.querySelector('.search-container');
    const searchButton = document.querySelector('.search-btn-header');
    const searchInputHeader = document.querySelector('.search-input-header');

    if (searchContainer && searchButton && searchInputHeader) {
        searchButton.addEventListener('click', function(event) {
            event.preventDefault();
            searchContainer.classList.toggle('active');
            if (searchContainer.classList.contains('active')) {
                searchInputHeader.focus();
            }
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


    // =======================================================
    // BAGIAN 2: LOGIKA KHUSUS HALAMAN DETAIL PRODUK
    // =======================================================
    const productDetailContainer = document.querySelector('.product-detail-container');
    if (productDetailContainer) {
        const minusBtn = productDetailContainer.querySelector('.minus-btn');
        const plusBtn = productDetailContainer.querySelector('.plus-btn');
        const quantityInput = productDetailContainer.querySelector('.quantity-input input');
        if (plusBtn) {
            plusBtn.addEventListener('click', () => {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });
        }
        if (minusBtn) {
            minusBtn.addEventListener('click', () => {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
        }
        const sizeOptions = productDetailContainer.querySelectorAll('.pd-size-option');
        sizeOptions.forEach(option => {
            option.addEventListener('click', () => {
                sizeOptions.forEach(btn => btn.classList.remove('selected'));
                option.classList.add('selected');
            });
        });
    }

    // =======================================================
    // BAGIAN 3: LOGIKA KHUSUS HALAMAN KERANJANG BELANJA
    // =======================================================
    const cartItemsList = document.querySelector('.cart-items-list');
    if (cartItemsList) { 
        const modalOverlay = document.getElementById('modal-overlay');
        const confirmationModal = document.getElementById('confirmation-modal');
        const cancelBtn = document.getElementById('cancel-btn');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        const modalText = document.getElementById('modal-text');
        let itemToDelete = null;

        function updateItemTotal(cartItem) {
            const priceString = cartItem.querySelector('.item-price').textContent;
            const price = parsePrice(priceString);
            const quantityInput = cartItem.querySelector('.item-quantity input');
            const quantity = parseInt(quantityInput.value);
            const totalElement = cartItem.querySelector('.item-total');
            totalElement.textContent = formatRupiah(price * quantity);
            updateCartSubtotal();
        }

        function updateCartSubtotal() {
            const allItems = document.querySelectorAll('.cart-items-list .cart-item');
            let subtotal = 0;
            allItems.forEach(item => {
                const totalString = item.querySelector('.item-total').textContent;
                subtotal += parsePrice(totalString);
            });
            const subtotalValueElement = document.getElementById('subtotal-value');
            if (subtotalValueElement) {
                subtotalValueElement.textContent = formatRupiah(subtotal);
            }
        }

        function hideModal() {
            if(modalOverlay) modalOverlay.classList.add('hidden');
            if(confirmationModal) confirmationModal.classList.add('hidden');
        }

        cartItemsList.addEventListener('click', function(e) {
            const cartItem = e.target.closest('.cart-item');
            if (!cartItem) return;
            if (e.target.classList.contains('quantity-btn')) {
                const input = cartItem.querySelector('.item-quantity input');
                if (e.target.textContent.trim() === '+') {
                    input.value = parseInt(input.value) + 1;
                    updateItemTotal(cartItem);
                }
                if (e.target.textContent.trim() === '-') {
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateItemTotal(cartItem);
                    }
                }
            }
            if (e.target.closest('.remove-btn')) {
                itemToDelete = cartItem;
                const productName = itemToDelete.querySelector('.item-info p').textContent;
                modalText.textContent = `Apakah Anda yakin ingin menghapus "${productName}" dari keranjang?`;
                modalOverlay.classList.remove('hidden');
                confirmationModal.classList.remove('hidden');
            }
        });
        cartItemsList.addEventListener('input', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.closest('.cart-item')) {
                if (e.target.value < 1 || e.target.value === '') {
                    e.target.value = 1;
                }
                updateItemTotal(e.target.closest('.cart-item'));
            }
        });
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                if (itemToDelete) {
                    itemToDelete.remove();
                    updateCartSubtotal();
                    itemToDelete = null;
                }
                hideModal();
            });
        }
        if (cancelBtn) cancelBtn.addEventListener('click', hideModal);
        if (modalOverlay) modalOverlay.addEventListener('click', hideModal);
        updateCartSubtotal();
    }
    
    // =======================================================
    // BAGIAN 4: LOGIKA KHUSUS HALAMAN PAYMENT / CHECKOUT
    // =======================================================
    const checkoutContainer = document.querySelector('.checkout-container');
    if (checkoutContainer) {

        // --- Toggle mobile order summary ---
        const mobileSummaryHeader = document.querySelector('.mobile-summary-header');
        const mobileSummaryContent = document.querySelector('.mobile-summary-content');
        if (mobileSummaryHeader && mobileSummaryContent) {
            mobileSummaryHeader.addEventListener('click', () => {
                mobileSummaryContent.classList.toggle('expanded');
                const icon = mobileSummaryHeader.querySelector('.fa-chevron-down');
                icon.style.transform = mobileSummaryContent.classList.contains('expanded') ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        }

        // --- Handle selection of ALL radio options ---
        const radioGroups = document.querySelectorAll('.radio-group');
        radioGroups.forEach(group => {
            group.addEventListener('click', (e) => {
                const selectedOption = e.target.closest('.radio-option');
                if (!selectedOption) return;
                const allOptions = group.querySelectorAll('.radio-option');
                allOptions.forEach(option => option.classList.remove('selected'));
                selectedOption.classList.add('selected');
                const radioInput = selectedOption.querySelector('input[type="radio"]');
                if (radioInput) radioInput.checked = true;
                if (group.id === 'billing-address-group') {
                    const billingForm = document.getElementById('billing-address-form');
                    if (radioInput.id === 'billing-different') {
                        billingForm.classList.remove('hidden');
                    } else {
                        billingForm.classList.add('hidden');
                    }
                }
            });
        });

        // --- Logika untuk Dropdown Provinsi Dinamis ---
        const provinceData = {
            "ID": ["Aceh", "Bali", "Banten", "Bengkulu", "DI Yogyakarta", "DKI Jakarta", "Gorontalo", "Jambi", "Jawa Barat", "Jawa Tengah", "Jawa Timur", "Kalimantan Barat", "Kalimantan Selatan", "Kalimantan Tengah", "Kalimantan Timur", "Kalimantan Utara", "Kepulauan Bangka Belitung", "Kepulauan Riau", "Lampung", "Maluku", "Maluku Utara", "Nusa Tenggara Barat", "Nusa Tenggara Timur", "Papua", "Papua Barat", "Riau", "Sulawesi Barat", "Sulawesi Selatan", "Sulawesi Tengah", "Sulawesi Tenggara", "Sulawesi Utara", "Sumatera Barat", "Sumatera Selatan", "Sumatera Utara"],
            "SG": ["Singapore"], "MY": ["Johor", "Kedah", "Kelantan", "Kuala Lumpur", "Labuan", "Melaka", "Negeri Sembilan", "Pahang", "Penang", "Perak", "Perlis", "Putrajaya", "Sabah", "Sarawak", "Selangor", "Terengganu"],
            "TH": ["Amnat Charoen", "Ang Thong", "Bangkok", "Bueng Kan", "Buri Ram", "Chachoengsao", "Chai Nat", "Chaiyaphum", "Chanthaburi", "Chiang Mai", "Chiang Rai", "Chon Buri", "Chumphon", "Kalasin", "Kamphaeng Phet", "Kanchanaburi", "Khon Kaen", "Krabi", "Lampang", "Lamphun", "Loei", "Lop Buri", "Mae Hong Son", "Maha Sarakham", "Mukdahan", "Nakhon Nayok", "Nakhon Pathom", "Nakhon Phanom", "Nakhon Ratchasima", "Nakhon Sawan", "Nakhon Si Thammarat", "Nan", "Narathiwat", "Nong Bua Lam Phu", "Nong Khai", "Nonthaburi", "Pathum Thani", "Pattani", "Phangnga", "Phatthalung", "Phayao", "Phetchabun", "Phetchaburi", "Phichit", "Phitsanulok", "Phra Nakhon Si Ayutthaya", "Phrae", "Phuket", "Prachin Buri", "Prachuap Khiri Khan", "Ranong", "Ratchaburi", "Rayong", "Roi Et", "Sa Kaeo", "Sakon Nakhon", "Samut Prakan", "Samut Sakhon", "Samut Songkhram", "Saraburi", "Satun", "Sing Buri", "Sisaket", "Songkhla", "Sukhothai", "Suphan Buri", "Surat Thani", "Surin", "Tak", "Trang", "Trat", "Ubon Ratchathani", "Udon Thani", "Uthai Thani", "Uttaradit", "Yala", "Yasothon"], "PH": ["Abra", "Agusan del Norte", "Agusan del Sur", "Aklan", "Albay", "Antique", "Apayao", "Aurora", "Basilan", "Bataan", "Batanes", "Batangas", "Benguet", "Biliran", "Bohol", "Bukidnon", "Bulacan", "Cagayan", "Camarines Norte", "Camarines Sur", "Camiguin", "Capiz", "Catanduanes", "Cavite", "Cebu", "Cotabato", "Davao de Oro", "Davao del Norte", "Davao del Sur", "Davao Occidental", "Davao Oriental", "Dinagat Islands", "Eastern Samar", "Guimaras", "Ifugao", "Ilocos Norte", "Ilocos Sur", "Iloilo", "Isabela", "Kalinga", "La Union", "Laguna", "Lanao del Norte", "Lanao del Sur", "Leyte", "Maguindanao", "Marinduque", "Masbate", "Metro Manila", "Misamis Occidental", "Misamis Oriental", "Mountain Province", "Negros Occidental", "Negros Oriental", "Northern Samar", "Nueva Ecija", "Nueva Vizcaya", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Pampanga", "Pangasinan", "Quezon", "Quirino", "Rizal", "Romblon", "Samar", "Sarangani", "Siquijor", "Sorsogon", "South Cotabato", "Southern Leyte", "Sultan Kudarat", "Sulu", "Surigao del Norte", "Surigao del Sur", "Tarlac", "Tawi-Tawi", "Zambales", "Zamboanga del Norte", "Zamboanga del Sur", "Zamboanga Sibugay"], "VN": ["An Giang", "Ba Ria-Vung Tau", "Bac Giang", "Bac Kan", "Bac Lieu", "Bac Ninh", "Ben Tre", "Binh Dinh", "Binh Duong", "Binh Phuoc", "Binh Thuan", "Ca Mau", "Can Tho", "Cao Bang", "Da Nang", "Dak Lak", "Dak Nong", "Dien Bien", "Dong Nai", "Dong Thap", "Gia Lai", "Ha Giang", "Ha Nam", "Ha Noi", "Ha Tinh", "Hai Duong", "Hai Phong", "Hau Giang", "Ho Chi Minh", "Hoa Binh", "Hung Yen", "Khanh Hoa", "Kien Giang", "Kon Tum", "Lai Chau", "Lam Dong", "Lang Son", "Lao Cai", "Long An", "Nam Dinh", "Nghe An", "Ninh Binh", "Ninh Thuan", "Phu Tho", "Phu Yen", "Quang Binh", "Quang Nam", "Quang Ngai", "Quang Ninh", "Quang Tri", "Soc Trang", "Son La", "Tay Ninh", "Thai Binh", "Thai Nguyen", "Thanh Hoa", "Thua Thien Hue", "Tien Giang", "Tra Vinh", "Tuyen Quang", "Vinh Long", "Vinh Phuc", "Yen Bai"], "BN": ["Belait", "Brunei-Muara", "Temburong", "Tutong"], "CN": ["Anhui", "Beijing", "Chongqing", "Fujian", "Gansu", "Guangdong", "Guangxi", "Guizhou", "Hainan", "Hebei", "Heilongjiang", "Henan", "Hubei", "Hunan", "Inner Mongolia", "Jiangsu", "Jiangxi", "Jilin", "Liaoning", "Ningxia", "Qinghai", "Shaanxi", "Shandong", "Shanghai", "Shanxi", "Sichuan", "Tianjin", "Tibet", "Xinjiang", "Yunnan", "Zhejiang"], "HK": ["Hong Kong"], "JP": ["Aichi", "Akita", "Aomori", "Chiba", "Ehime", "Fukui", "Fukuoka", "Fukushima", "Gifu", "Gunma", "Hiroshima", "Hokkaido", "Hyogo", "Ibaraki", "Ishikawa", "Iwate", "Kagawa", "Kagoshima", "Kanagawa", "Kochi", "Kumamoto", "Kyoto", "Mie", "Miyagi", "Miyazaki", "Nagano", "Nagasaki", "Nara", "Niigata", "Oita", "Okayama", "Okinawa", "Osaka", "Saga", "Saitama", "Shiga", "Shimane", "Shizuoka", "Tochigi", "Tokushima", "Tokyo", "Tottori", "Toyama", "Wakayama", "Yamagata", "Yamaguchi", "Yamanashi"], "KR": ["Busan", "Chungcheongbuk-do", "Chungcheongnam-do", "Daegu", "Daejeon", "Gangwon-do", "Gwangju", "Gyeonggi-do", "Gyeongsangbuk-do", "Gyeongsangnam-do", "Incheon", "Jeju-do", "Jeollabuk-do", "Jeollanam-do", "Sejong", "Seoul", "Ulsan"], "TW": ["Changhua", "Chiayi City", "Chiayi County", "Hsinchu City", "Hsinchu County", "Hualien", "Kaohsiung", "Keelung", "Kinmen", "Lienchiang", "Miaoli", "Nantou", "New Taipei", "Penghu", "Pingtung", "Taichung", "Tainan", "Taipei", "Taitung", "Taoyuan", "Yilan", "Yunlin"], "AU": ["Australian Capital Territory", "New South Wales", "Northern Territory", "Queensland", "South Australia", "Tasmania", "Victoria", "Western Australia"], "US": ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"], "GB": ["England", "Northern Ireland", "Scotland", "Wales"], "DE": ["Baden-Württemberg", "Bavaria", "Berlin", "Brandenburg", "Bremen", "Hamburg", "Hesse", "Lower Saxony", "Mecklenburg-Vorpommern", "North Rhine-Westphalia", "Rhineland-Palatinate", "Saarland", "Saxony", "Saxony-Anhalt", "Schleswig-Holstein", "Thuringia"]
        };
        function initializeDynamicProvinces(countryDropdownId, provinceDropdownId) {
            const countrySelect = document.getElementById(countryDropdownId);
            const provinceSelect = document.getElementById(provinceDropdownId);
            if (!countrySelect || !provinceSelect) return;
            function populateProvinces() {
                const selectedCountry = countrySelect.value;
                const provinces = provinceData[selectedCountry] || [];
                provinceSelect.innerHTML = '';
                if (provinces.length === 0) {
                    provinceSelect.innerHTML = '<option>Provinsi tidak tersedia</option>';
                    provinceSelect.disabled = true;
                    return;
                }
                provinceSelect.disabled = false;
                let placeholder = document.createElement('option');
                placeholder.value = "";
                placeholder.textContent = "Provinsi";
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
            populateProvinces();
        }
        initializeDynamicProvinces('shipping-country', 'shipping-province');
        initializeDynamicProvinces('billing-country', 'billing-province');
    }

    // --- LOGIKA DISKON (Event Delegation) ---
    document.addEventListener('click', function (event) {
        if (event.target.id === 'apply-discount-btn') {
            const discountInput = event.target.previousElementSibling;
            const summaryContainer = event.target.closest('.order-summary, .mobile-summary-content');
            if (!discountInput || !summaryContainer) return;
            const subtotalEl = summaryContainer.querySelector('#summary-subtotal');
            if (!subtotalEl) return;
            let subtotal = parsePrice(subtotalEl.textContent);

            if (discountInput.value.trim().toUpperCase() === 'GLOOMIE10') {
                const discountAmount = subtotal * 0.10;
                const newTotal = subtotal - discountAmount;
                const allDiscountLines = document.querySelectorAll('#discount-line');
                const allDiscountAmountEls = document.querySelectorAll('#summary-discount-amount');
                const allTotalEls = document.querySelectorAll('#summary-total');

                allDiscountAmountEls.forEach(el => el.textContent = `- ${formatRupiah(discountAmount)}`);
                allDiscountLines.forEach(el => el.classList.remove('hidden'));
                allTotalEls.forEach(el => el.innerHTML = `IDR <strong>${formatRupiah(newTotal)}</strong>`);
                
                alert('Discount code applied successfully!');
                
                document.querySelectorAll('#apply-discount-btn').forEach(btn => btn.disabled = true);
                document.querySelectorAll('#discount-code-input').forEach(input => input.disabled = true);
            } else {
                alert('Invalid discount code!');
            }
        }
    });

}); // Penutup akhir dari DOMContentLoaded