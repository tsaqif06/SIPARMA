    <!-- jQuery library js -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Apex Chart js -->
    <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
    <!-- Data Table js -->
    <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
    <!-- Iconify Font js -->
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <!-- jQuery UI js -->
    <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
    <!-- Vector Map js -->
    <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- Popup js -->
    <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
    <!-- Slick Slider js -->
    <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
    <!-- prism js -->
    <script src="{{ asset('assets/js/lib/prism.js') }}"></script>
    <!-- file upload js -->
    <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
    <!-- audioplayer -->
    <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('assets/js/lib/sweetalert2.min.js') }}"></script>
    <!-- leaflet -->
    <script src="{{ asset('assets/js/lib/leaflet.js') }}"></script>
    <!-- trix -->
    <script src="{{ asset('assets/js/lib/trix.js') }}"></script>

    <!-- main js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            let table = new DataTable("#dataTable");
            let flag = $(".leaflet-attribution-flag");
            if (flag) {
                flag.remove();
            }

            document.addEventListener("trix-attachment-add", function(event) {
                let attachment = event.attachment;

                console.log("🔹 Gambar Ditambahkan di Trix:", attachment);

                if (attachment.file) {
                    let formData = new FormData();
                    formData.append("image", attachment.file);

                    console.log("🔹 Mengirim gambar ke server...");

                    fetch("{{ route('admin.articles.upload-image') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("✅ Respon dari Server:", data);
                        if (data.url) {
                            attachment.setAttributes({
                                url: data.url,
                                href: data.url
                            });
                        } else {
                            console.error("❌ Upload gagal: Respon tidak mengandung URL!");
                        }
                    })
                    .catch(error => {
                        console.error("❌ Upload gagal", error);
                    });
                }
            });
        });
    </script>

    <?php echo isset($script) ? $script : ''; ?>
