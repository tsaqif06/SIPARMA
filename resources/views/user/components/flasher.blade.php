@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ __('main.berhasil') }}',
            text: '{{ session('success') }}',
            confirmButtonText: '{{ __('main.tutup') }}',
            timer: 1000
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ __('main.gagal') }}',
            text: '{{ session('error') }}',
            confirmButtonText: '{{ __('main.tutup') }}',
            timer: 1000
        });
    </script>
@endif
