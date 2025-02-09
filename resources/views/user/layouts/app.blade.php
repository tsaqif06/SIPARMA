<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('user.components.head')

<body>
    {{--  @include('user.components.sidebar')  --}}

    <main class="dashboard-main">
        @if (!isset($withoutNavbar))
            @include('user.components.navbar')
        @endif
        <div class="dashboard-main-body">
            @yield('content')
        </div>
        @include('user.components.footer')
    </main>

    @include('user.components.script', ['script' => $script ?? ''])
    @include('user.components.flasher')
</body>

</html>
