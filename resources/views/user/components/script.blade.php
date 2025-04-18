<script src="{{ asset('assets/user/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/user/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/user/js/wow.min.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery-plugin-collection.js') }}"></script>
<script src="{{ asset('assets/user/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery.dlmenu.js') }}"></script>
<script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/prism.js') }}"></script>
<script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
<script src="{{ asset('assets/js/lib/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/leaflet.js') }}"></script>
<script src="{{ asset('assets/js/lib/leaflet-routing-machine.js') }}"></script>

<script src="{{ asset('assets/user/js/script.js') }}"></script>

<script>
    $(document).ready(function() {
        new WOW().init();
        // Inisialisasi DataTable
        let table = new DataTable("#dataTable");
        let flag = $(".leaflet-attribution-flag");
        if (flag) {
            flag.remove();
        }

        $(".lazy-bg").each(function() {
            let $el = $(this);

            let observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const bgUrl = $el.data("bg");

                        if ($el.hasClass("overlay-dark")) {
                            $el.css("background-image",
                                `linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('${bgUrl}')`
                            );
                        } else {
                            $el.css("background-image", `url('${bgUrl}')`);
                        }

                        $el.css({
                            "background-size": "cover",
                            "background-position": "center",
                            "background-repeat": "no-repeat"
                        });

                        $el.removeClass("lazy-bg");
                        observer.unobserve(entry.target);
                    }
                });
            });

            observer.observe(this);
        });

        var navbarMobile = $(".navigation-holder-mobile");
        var openBtn = $(".mobail-menu .navbar-toogler");
        var closeBtn = $(".menu-close");

        // Buka menu
        openBtn.on("click", function(e) {
            console.log("offnen")
            e.preventDefault();
            navbarMobile.addClass("slideInn");
            $("body").addClass("menu-open");
        });

        // Tutup menu
        closeBtn.on("click", function(e) {
            e.preventDefault();
            navbarMobile.removeClass("slideInn");
            $("body").removeClass("menu-open");
        });

        // Tutup jika klik di luar menu
        $(document).on("click", function(e) {
            if (!$(e.target).closest(".navigation-holder-mobile, .open-btn").length) {
                navbarMobile.removeClass("slideInn");
                $("body").removeClass("menu-open");
            }
        });

        var lastScrollTop = 0;

        $(window).on("scroll", function() {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > lastScrollTop) {
                $(".navigation").stop().animate({
                    top: "-100px"
                }, 200);
            } else {
                $(".navigation").stop().animate({
                    top: "20px"
                }, 200);
            }

            lastScrollTop = scrollTop;
        });

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
                                    alert("Pembayaran Berhasil!");
                                    window.location.href = "/invoice/" +
                                        result.order_id;
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
