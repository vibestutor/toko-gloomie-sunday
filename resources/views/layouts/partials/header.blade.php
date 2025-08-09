<header id="main-header" class="{{ request()->is('/') ? '' : 'header-solid' }}">
    <div class="navbar-container">
        <div class="navbar-left">
            <button class="hamburger-btn" id="hamburger-btn" aria-label="Buka Menu">
                <svg class="hamburger-icon" fill="none" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <a href="/" class="logo-desktop">
                <img src="{{ asset('img/perlogoan/logo-gif.gif') }}" alt="Logo Desktop" class="logo-spin" />
            </a>
            <ul class="desktop-nav-menu">
                <li class="dropdown">
                <a href="/products" class="dropbtn">SHOP ▼</a>
                <ul class="dropdown-content">
                    <li><a href="/products">ALL PRODUCT</a></li>
                    {{-- Nanti kita isi kategori dari database --}}
                </ul>
                </li>
                <li><a href="/gallery">GALLERY</a></li>
                <li><a href="/about">ABOUT</a></li>
                <li><a href="/contact">CONTACT</a></li>
            </ul>
        </div>
        <div class="navbar-center">
            <a href="/" class="logo-mobile">
                <img src="{{ asset('img/perlogoan/logo-gif.gif') }}" alt="Logo Mobile" class="logo-spin">
            </a>
        </div>
        <div class="navbar-right">
            <div class="search-container">
                <input type="search" class="search-input-header" placeholder="Search...">
                <button class="search-btn-header">
                    <i class="fas fa-search action-icon"></i>
                </button>
                <div id="search-results" class="search-results"></div>
            </div>
            <a href="#" id="login-btn-popup" class="btnLogin-popup" aria-label="Login">
                <i class="fas fa-user action-icon"></i>
            </a>
            <a href="/cart" id="cart-icon" class="cart-btn hidden" aria-label="Keranjang">
                <i class="fas fa-shopping-bag action-icon"></i>
            </a>
            <a href="#" id="logout-btn-desktop" class="hidden" aria-label="Logout">
                <i class="fas fa-sign-out-alt action-icon"></i>
            </a>
        </div>
    </div>
</header>

{{-- Kode untuk Pop-up Login/Register --}}
<div class="wrapper">
        <span class="icon-close">
            <ion-icon name="close"></ion-icon>
        </span>

        <div class="form-box login">
            <h2>Login</h2>
            <form action="#">
                <div class="input-box">
    <span class="icon">
        <ion-icon name="mail"></ion-icon>
    </span>
    <input type="email" required placeholder=" ">
    <label>Email</label>
</div>
<div class="input-box">
    <span class="icon">
        <ion-icon name="lock-closed"></ion-icon>
    </span>
    <input type="password" required placeholder=" ">
    <label>Password</label>
</div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <h2>Registration</h2>
            <form action="#">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" required>
                    <label>Password</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> I agree to the term & conditions</label>
                </div>
                <button type="submit" class="btn">Register</button>
                <div class="login-register">
            <p>Already have an account? <a href="#" class="login-link">Login</a></p>
        </div>
        </form>
    </div>
</div> <div id="forgot-password-wrapper" class="wrapper">
    <span class="icon-close">
        <ion-icon name="close"></ion-icon>
    </span>
    <div class="form-box">
        <h2>Reset Password</h2>
        <form action="#">
            <p class="form-intro">Enter your email address to reset your password.</p>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail-outline"></ion-icon>
                </span>
                <input type="email" required placeholder=" ">
                <label>Email</label>
            </div>
            <button type="submit" class="btn">Reset</button>
            <div class="login-register">
                <p><a href="#" class="login-link">Return to Login</a></p>
            </div>
        </form>
    </div>
</div>

{{-- Kode untuk Menu Mobile --}}
<nav class="mobile-nav" id="mobile-menu">
    <button class="close-menu-btn" id="close-menu-btn" aria-label="Tutup Menu">&times;</button>
    
    <div class="sidebar-header">
        <a href="index.php" id="sidebar-logo" class="sidebar-profile">
            <img src="img/perlogoan/logo-gif.gif" alt="Logo Gloomie" class="profile-pic" />
            <span class="profile-name">Gloomie</span>
        </a>
        <a href="#" id="sidebar-user-profile" class="sidebar-profile hidden">
            <img src="https://via.placeholder.com/50" alt="Foto Profil" class="profile-pic">
            <div class="user-info">
                <span class="profile-name">[Nama Pengguna]</span>
                <span class="profile-email">[Email Pengguna]</span>
            </div>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li class="dropdown">
            <a href="#" class="dropbtn-mobile">
                <i class="fas fa-store"></i>
                <span>SHOP</span>
                <i class="fas fa-chevron-down arrow-icon"></i>
            </a>
            <ul class="dropdown-content">
                <li><a href="All-Product.php">ALL PRODUCT</a></li>
                <li><a href="top.php">TOP</a></li>
                <li><a href="outers.php">OUTERS</a></li>
                <li><a href="bottom.php">BOTTOM</a></li>
            </ul>
        </li>
        <li>
            <a href="galery.php">
                <i class="fas fa-images"></i>
                <span>GALLERY</span>
            </a>
        </li>
        <li>
            <a href="about.php">
                <i class="fas fa-info-circle"></i>
                <span>ABOUT</span>
            </a>
        </li>
        <li>
            <a href="contact.php">
                <i class="fas fa-envelope"></i>
                <span>CONTACT</span>
            </a>
        </li>
    </ul>

    <ul id="logout-menu" class="sidebar-account-menu hidden">
        <li>
            <a href="#" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>LOGOUT</span>
            </a>
        </li>
    </ul>
</nav>


{{-- Kode untuk Overlay --}}
<div class="page-overlay" id="page-overlay"></div>