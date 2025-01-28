<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('admin.components.head')

<body>
    @include('admin.components.sidebar')

    <main class="dashboard-main">
        @include('admin.components.navbar')
        <div class="dashboard-main-body">
            {{--  <x-breadcrumb title='{{ isset($title) ? $title : '' }}' subTitle='{{ isset($subTitle) ? $subTitle : '' }}' />  --}}
            @include('admin.components.breadcrumb', [
                'title' => $title ?? '',
                'subTitle' => $subTitle ?? '',
            ])
            @yield('content')

        </div>
        @include('admin.components.footer')
    </main>

    @include('admin.components.script', ['script' => $script ?? ''])
    @include('admin.components.flasher')
</body>

</html>
