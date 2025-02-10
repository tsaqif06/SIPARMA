<header id="header">
    <div class="wpo-site-header">
        <nav class="navigation navbar navbar-expand-lg navbar-light">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-3 col-3 d-lg-none dl-block">
                        <div class="mobail-menu">
                            <button type="button" class="navbar-toggler open-btn">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar first-angle"></span>
                                <span class="icon-bar middle-angle"></span>
                                <span class="icon-bar last-angle"></span>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-4">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="{{ route('home.index') }}"><img
                                    src="{{ asset('assets/user/images/LOGO_SIPARMA_.png') }}" alt="logo"
                                    width="100"></a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-1 col-1">
                        <div id="navbar" class="collapse navbar-collapse navigation-holder">
                            <button class="menu-close"><iconify-icon icon="bxs:x-circle" width="30"
                                    height="30"></iconify-icon></button>
                            <ul class="nav navbar-nav mb-2 mb-lg-0">
                                <li><a href="{{ route('home.index') }}">Beranda</a></li>
                                <li><a href="{{ route('home.wisata') }}">Wisata</a></li>
                                <li><a href="{{ route('home.tempat') }}">Tempat</a></li>
                            </ul>

                        </div><!-- end of nav-collapse -->
                    </div>
                    <div class="col-lg-3 col-md-4 col-4">
                        <div class="header-right">
                            <div class="close-form">
                                @guest
                                    <!-- Jika belum login, tampilkan "Masuk" -->
                                    <a href="{{ route('login') }}" class="btn px-4 py-2 rounded-pill"
                                        style="border: 2px solid #1D3557; color: white; background-color: #1D3557; transition: 0.3s;"
                                        onmouseover="this.style.backgroundColor='white'; this.style.color='#1D3557';"
                                        onmouseout="this.style.backgroundColor='#1D3557'; this.style.color='white';">
                                        Masuk
                                    </a>
                                @endguest

                                @auth
                                    <!-- Jika sudah login, tampilkan "Akun Saya" -->
                                    <a href="{{ route('profile') }}" class="btn px-4 py-2 rounded-pill"
                                        style="border: 2px solid #1D3557; color: white; background-color: #1D3557; transition: 0.3s;"
                                        onmouseover="this.style.backgroundColor='white'; this.style.color='#1D3557';"
                                        onmouseout="this.style.backgroundColor='#1D3557'; this.style.color='white';">
                                        Akun Saya
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end of container -->
        </nav>
    </div>
</header>
