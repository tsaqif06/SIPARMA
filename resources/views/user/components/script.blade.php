<script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/user/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/prism.js') }}"></script>
<script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery.dlmenu.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery-plugin-collection.js') }}"></script>

<script src="{{ asset('assets/user/js/script.js') }}"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        let table = new DataTable("#dataTable");
    });
</script>

@if (isset($transaction))
    <script>
        $("#pay-button").click(function() {
            let name = $("#name").val();
            let email = $("#email").val();
            let phone = $("#phone_number").val();

            $.ajax({
                url: "{{ route('payment.process', $transaction->transaction_code) }}",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                contentType: "application/json",
                data: JSON.stringify({
                    transaction_id: "{{ $transaction->id }}",
                    name: name,
                    email: email,
                    phone: phone
                }),
                success: function(response) {
                    window.snap.pay(response.snapToken, {
                        onSuccess: function(result) {
                            alert("Pembayaran Berhasil!");

                            $.ajax({
                                url: "/payment/callback",
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                contentType: "application/json",
                                data: JSON.stringify({
                                    order_id: result.order_id,
                                    transaction_status: 'settlement'
                                }),
                                success: function() {
                                    window.location.href = "/invoice/" + result
                                        .order_id;
                                }
                            });
                        },
                        onPending: function(result) {
                            alert("Menunggu Pembayaran...");
                        },
                        onError: function(result) {
                            alert("Pembayaran Gagal!");

                            $.ajax({
                                url: "/payment/callback",
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                contentType: "application/json",
                                data: JSON.stringify({
                                    order_id: result.order_id,
                                    transaction_status: 'failed'
                                }),
                                success: function() {
                                    let userConfirm = confirm(
                                        "Status pembayaran diperbarui menjadi gagal. Kembali ke halaman utama?"
                                    );
                                    if (userConfirm) {
                                        window.location.href =
                                            "/";
                                    }
                                }
                            });
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat memproses pembayaran.");
                }
            });
        });
    </script>
@endif

<?php echo isset($script) ? $script : ''; ?>
