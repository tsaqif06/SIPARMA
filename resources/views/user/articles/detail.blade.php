@extends('user.layouts.app')

@section('content')
    <section class="wpo-blog-pg-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col col-lg-8">
                    <div class="wpo-blog-content">
                        <div class="post format-standard-image">
                            <div class="entry-media">
                                <h3><a href="blog-single.html">Jalur Wisata ke Gunung Bromo Sempat Terputus Imbas Longsor.</a></h3>
                                <div class="entry-meta">
                                    <div style="overflow: hidden;">
                                        <div style="float: left;">
                                            <span><i class="fi flaticon-user"></i> Di unggah oleh Admin</span>
                                            <span style="margin-left: 16px;"><i class="fi flaticon-calendar"></i> Selasa, 18 Mar 2025</span>
                                        </div>
                                        <div style="float: right; display: flex; align-items: center; gap: 4px;">
                                            <iconify-icon icon="solar:heart-outline" style="color: black; font-size: 25px"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/hero-article.jpg') }}" alt>
                            </div>
                            <div class="entry-details mt-5">
                                <p>Banjir bandang hingga longsor terjadi di kawasan Puncak, 
                                    Bogor, Jawa Barat pada Minggu (2/3). Selain itu, juga 
                                    terdapat jembatan yang putus imbas hujan deras.</p>
                            </div>
                        </div>

                        <div class="post format-standard-image">
                            <div class="room-review">
                                <div class="room-title">
                                    <h2>Ulasan</h2>
                                </div>
                            
                                <div class="review-item">
                                    <div class="review-img">
                                        <div><img src="{{ asset('assets/images/hero-article.jpg') }}" alt></div>
                                    </div>
                                    <div class="review-text">
                                        <div class="r-title">
                                            <h2>John Doe</h2>
                                        </div>
                                        <p>Gunungnya sangat megah sekali waua</p>
                                    </div>
                                </div>
                            </div>

                            <div class="add-review">
                                <div class="room-title">
                                    <h2>Berikan Ulasan</h2>
                                </div>
                        
                                <form id="reviewForm" onsubmit="handleReviewSubmit(event)">
                                    <div class="wpo-blog-single-section review-form">
                                        <div class="review-add">
                                            <div class="comment-respond">
                                                <div class="comment-form">
                                                    <div class="form">
                                                        <textarea id="comment" name="comment" placeholder="Ketik ulasan Anda..." required></textarea>
                                                        <div id="commentError" class="text-danger" style="display: none;"></div>
                                                    </div>
                        
                                                    <!-- Submit Button -->
                                                    <div class="form-submit">
                                                        <button type="submit" class="btn btn-primary" style="font-size: 16px;">
                                                            Kirim Ulasan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-4">
                    <div class="blog-sidebar">
                        <div class="widget search-widget">
                            <form id="searchForm" class="d-flex justify-content-center">
                                <input type="text" id="searchInput" class="search-box rounded-3 w-100 me-2"
                                       placeholder="Cari Berita" style="
                                       background-color: white;
                                       border: 1px solid #000;
                                       padding: 8px 15px;
                                       outline: none;
                                       height: 50px;">
                            </form>
                        </div>
                        <div class="widget recent-post-widget">
                            <h3>Berita Relevan</h3>
                            <div class="posts">
                                <div class="post">
                                    <div class="img-holder">
                                        <img src="{{ asset('assets/images/hero-article.jpg') }}" alt>
                                    </div>
                                    <div class="details">
                                        <h4><a href="blog-single.html">17 places you cannot ignore in Paris</a></h4>
                                        <span class="date">19 Jun 2023 </span>
                                    </div>
                                </div>
                                <div class="post">
                                    <div class="img-holder">
                                        <img src="{{ asset('assets/images/hero-article.jpg') }}" alt>
                                    </div>
                                    <div class="details">
                                        <h4><a href="blog-single.html">Be Careful About This, When You Are In
                                                Snow</a></h4>
                                        <span class="date">22 May 2023 </span>
                                    </div>
                                </div>
                                <div class="post">
                                    <div class="img-holder">
                                        <img src="{{ asset('assets/images/hero-article.jpg') }}" alt>
                                    </div>
                                    <div class="details">
                                        <h4><a href="blog-single.html">Things You Must Need To See While You’re In
                                                Dubai</a></h4>
                                        <span class="date">12 Apr 2023 </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget category-widget">
                            <h3>Kategori Berita</h3>
                            <ul>
                                <li><a href="blog.html">Hotel </a></li>
                                <li><a href="blog.html">Wisata </a></li>
                                <li><a href="blog.html">Wahana </a></li>
                            </ul>
                        </div>
                        <div class="widget tag-widget">
                            <h3>Tag</h3>
                            <ul>
                                <li><a href="#">Travelling</a></li>
                                <li><a href="#">Hotel</a></li>
                                <li><a href="#">Restaurant</a></li>
                                <li><a href="destination.html">Destination</a></li>
                                <li><a href="#">World Tour</a></li>
                                <li><a href="#">Hotel Room</a></li>
                                <li><a href="#">Alam</a></li>
                                <li><a href="#">Guide</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
