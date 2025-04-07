<header id="header">
    <div class="wpo-site-header">
        <nav class="navigation navbar navbar-expand-lg navbar-light" style="border-radius: 17px">
            <div class="container-fluid">
                <div class="row row-navbar align-items-center">
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
                                    src="{{ asset('assets/user/images/LOGO_SIPARMA_.png') }}" alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-1 col-1 d-none d-lg-block">
                        <div id="navbar" class="collapse navbar-collapse navigation-holder">
                            <button class="menu-close"><iconify-icon icon="bxs:x-circle" width="30"
                                    height="30"></iconify-icon></button>
                            <ul class="nav navbar-nav mb-2 mb-lg-0">
                                <li><a href="{{ route('home.index') }}">{{ __('main.beranda') }}</a></li>
                                <li><a href="{{ route('destination.browse') }}">{{ __('main.wisata') }}</a></li>
                                <li><a href="{{ route('place.browse') }}">{{ __('main.tempat') }}</a></li>
                                <li><a href="{{ route('article.browse') }}">{{ __('main.artikel') }}</a></li>
                                <li><a href="{{ route('article.detail') }}">Details</a></li>
                            </ul>

                        </div><!-- end of nav-collapse -->
                    </div>

                    <div class="col-lg-3 col-md-4 col-4">
                        <div class="header-right">
                            <div class="language-switch d-flex align-items-center d-none d-lg-flex"
                                data-bs-toggle="modal" data-bs-target="#languageModal" style="cursor: pointer;">
                                <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/{{ session('locale', 'id') == 'en' ? 'gb' : 'id' }}.svg"
                                    alt="Lang" width="24"
                                    style="border: 2px solid #ccc; border-radius: 4px; box-shadow: 0px 0px 5px rgba(0,0,0,0.2);">
                                <iconify-icon icon="mdi:chevron-down" width="20" class="ms-2"></iconify-icon>
                            </div>

                            <div class="close-form">
                                @guest
                                    <a href="{{ route('login') }}" class="btn px-4 py-2 rounded-pill"
                                        style="border: 2px solid #1D3557; color: white; background-color: #1D3557; transition: 0.3s;"
                                        onmouseover="this.style.backgroundColor='white'; this.style.color='#1D3557';"
                                        onmouseout="this.style.backgroundColor='#1D3557'; this.style.color='white';">
                                        {{ __('main.masuk') }}
                                    </a>
                                @endguest

                                @auth
                                    <a href="{{ route('profile') }}" class="btn px-4 py-2 rounded-pill"
                                        style="border: 2px solid #1D3557; color: white; background-color: #1D3557; transition: 0.3s;"
                                        onmouseover="this.style.backgroundColor='white'; this.style.color='#1D3557';"
                                        onmouseout="this.style.backgroundColor='#1D3557'; this.style.color='white';">
                                        {{ __('main.akun_saya') }}
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

<!-- Navigation Mobile (Pisahkan dari Header) -->
<div class="navigation-holder-mobile d-flex flex-column h-100">
    <button class="menu-close">
        <iconify-icon icon="bxs:x-circle" width="30" height="30"></iconify-icon>
    </button>
    <ul class="nav navbar-nav">
        <li><a href="{{ route('home.index') }}">{{ __('main.beranda') }}</a></li>
        <li><a href="{{ route('destination.browse') }}">{{ __('main.wisata') }}</a></li>
        <li><a href="{{ route('place.browse') }}">{{ __('main.tempat') }}</a></li>
        <li><a href="{{ route('article.browse') }}">{{ __('main.artikel') }}</a></li>
    </ul>

    <!-- Language Switch di bagian bawah -->
    <div class="language-switch d-flex align-items-center mt-auto ms-4 mb-5" data-bs-toggle="modal"
        data-bs-target="#languageModal" style="cursor: pointer;">
        <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/{{ session('locale', 'id') == 'en' ? 'gb' : 'id' }}.svg"
            alt="Lang" width="24"
            style="border: 2px solid #ccc; border-radius: 4px; box-shadow: 0px 0px 5px rgba(0,0,0,0.2);">
        <span class="ms-2">{{ session('locale', 'id') == 'en' ? 'English' : 'Indonesia' }}</span>
        <iconify-icon icon="mdi:chevron-down" width="20" class="ms-2"></iconify-icon>
    </div>
</div>


<div class="modal fade" id="languageModal" tabindex="-1" aria-labelledby="languageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="languageModalLabel">{{ __('main.pilih_bahasa') }}</h5>
            </div>
            <div class="modal-body text-center d-flex justify-content-center gap-4">
                <a href="{{ url('set-language/id') }}">
                    <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/id.svg"
                        alt="ID" width="50"
                        style="border: 2px solid #ccc; border-radius: 4px; box-shadow: 0px 0px 5px rgba(0,0,0,0.2);">
                </a>
                <a href="{{ url('set-language/en') }}">
                    <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/flags/4x3/gb.svg"
                        alt="EN" width="50"
                        style="border: 2px solid #ccc; border-radius: 4px; box-shadow: 0px 0px 5px rgba(0,0,0,0.2);">
                </a>
            </div>
        </div>
    </div>
</div>
