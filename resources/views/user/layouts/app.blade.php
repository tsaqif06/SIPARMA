<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('user.components.head')

<body>
    {{--  @include('user.components.loading')  --}}

    <main class="dashboard-main">
        @if (!isset($withoutNavbar))
            @include('user.components.navbar')
        @endif
        <div class="dashboard-main-body" style="margin-top: 150px">
            @yield('content')
        </div>
        @if (!isset($withoutFooter))
            @include('user.components.footer')
        @endif
    </main>

    @include('user.components.script', ['script' => $script ?? ''])
    @include('user.components.flasher')
</body>

</html>
