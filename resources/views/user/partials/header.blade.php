<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">SIPARMA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home.index') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home.wisata') }}">Wisata</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('home.tempat') }}">Tempat</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="langDropdown"
                        data-bs-toggle="dropdown">Language</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?lang=id">Bahasa Indonesia</a></li>
                        <li><a class="dropdown-item" href="?lang=en">English</a></li>
                    </ul>
                </li>

                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <!-- Display Profil and Logout if logged in -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">Profil</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Logout</button>
                        </form>
                    </li>
                @endguest

                <li class="nav-item">
                    <button id="darkModeToggle" class="btn btn-outline-dark">Dark Mode</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
