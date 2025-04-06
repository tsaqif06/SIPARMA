@extends('user.layouts.app')

@section('content')
    <section class="wpo-blog-pg-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col col-lg-8">
                    <div class="wpo-blog-content">
                        <div class="post format-standard-image">
                            <div class="entry-media">
                                <img src="assets/images/blog/img-4.jpg" alt>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="fi flaticon-user"></i> By <a href="#">Jenny Watson</a>
                                    </li>
                                    <li><i class="fi flaticon-comment-white-oval-bubble"></i> Comments 35
                                    </li>
                                    <li><i class="fi flaticon-calendar"></i> 24 Nov 2023</li>
                                </ul>
                            </div>
                            <div class="entry-details">
                                <h3><a href="blog-single.html">Five Of Nature’s Swimming Pools.</a></h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur dolorem iste
                                    minus quibusdam. Sunt nulla laboriosam possimus, mollitia laudantium, incidunt
                                    sapiente quidem aspernatur nemo blanditiis ad fugiat culpa delectus vero?</p>
                                <a href="blog-single.html" class="read-more">Read More...</a>
                            </div>
                        </div>
                        <div class="post format-standard">
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="fi flaticon-user"></i> By <a href="#">Jenny Watson</a>
                                    </li>
                                    <li><i class="fi flaticon-comment-white-oval-bubble"></i> Comments 35
                                    </li>
                                    <li><i class="fi flaticon-calendar"></i> 24 Nov 2023</li>
                                </ul>
                            </div>
                            <div class="entry-details">
                                <h3><a href="blog-single.html">A wedding night in resort.</a></h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur dolorem iste
                                    minus quibusdam. Sunt nulla laboriosam possimus, mollitia laudantium, incidunt
                                    sapiente quidem aspernatur nemo blanditiis ad fugiat culpa delectus vero?</p>
                                <a href="blog-single.html" class="read-more">Read More...</a>
                            </div>
                        </div>

                        <div class="post format-gallery">
                            <div class="entry-media">
                                <div class="post-slider owl-carousel">
                                    <img src="assets/images/blog/img-5.jpg" alt>
                                    <img src="assets/images/blog/img-6.jpg" alt>
                                </div>

                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="fi flaticon-user"></i> By <a href="#">Jenny Watson</a>
                                    </li>
                                    <li><i class="fi flaticon-comment-white-oval-bubble"></i> Comments 35
                                    </li>
                                    <li><i class="fi flaticon-calendar"></i> 24 Nov 2023</li>
                                </ul>
                            </div>
                            <div class="entry-details">
                                <h3><a href="blog-single.html">The Joyless Side Of Happy Hour.</a></h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur dolorem iste
                                    minus quibusdam. Sunt nulla laboriosam possimus, mollitia laudantium, incidunt
                                    sapiente quidem aspernatur nemo blanditiis ad fugiat culpa delectus vero?</p>
                                <a href="blog-single.html" class="read-more">Read More...</a>
                            </div>
                        </div>
                        <div class="post format-video">
                            <div class="entry-media video-holder">
                                <img src="assets/images/blog/img-7.jpg" alt>
                                <a href="https://www.youtube.com/embed/kcftdnS0YGE" class="video-btn"
                                    data-type="iframe">

                                </a>

                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="fi flaticon-user"></i> By <a href="#">Jenny Watson</a>
                                    </li>
                                    <li><i class="fi flaticon-comment-white-oval-bubble"></i> Comments 35
                                    </li>
                                    <li><i class="fi flaticon-calendar"></i> 24 Nov 2023</li>
                                </ul>
                            </div>
                            <div class="entry-details">
                                <h3><a href="blog-single.html">What Happened During My First Trip Alone</a>
                                </h3>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur dolorem iste
                                    minus quibusdam. Sunt nulla laboriosam possimus, mollitia laudantium, incidunt
                                    sapiente quidem aspernatur nemo blanditiis ad fugiat culpa delectus vero?</p>
                                <a href="blog-single.html" class="read-more">Read More...</a>
                            </div>
                        </div>
                        <div class="pagination-wrapper pagination-wrapper-left">
                            <ul class="pg-pagination">
                                <li>
                                    <a href="#" aria-label="Previous">
                                        <i class="fi ti-angle-left"></i>
                                    </a>
                                </li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li>
                                    <a href="#" aria-label="Next">
                                        <i class="fi ti-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-4">
                    <div class="blog-sidebar">
                        <div class="widget about-widget">
                            <div class="img-holder">
                                <img src="assets/images/blog/about-widget.jpg" alt>
                            </div>
                            <h4>Jenny Watson</h4>
                            <p>Hi! beautiful people. I`m an authtor of this blog. Read our post - stay with us</p>
                            <div class="social">
                                <ul class="clearfix">
                                    <li><a href="#"><i class="ti-facebook"></i></a></li>
                                    <li><a href="#"><i class="ti-twitter-alt"></i></a></li>
                                    <li><a href="#"><i class="ti-linkedin"></i></a></li>
                                    <li><a href="#"><i class="ti-pinterest"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="widget search-widget">
                            <form>
                                <div>
                                    <input type="text" class="form-control" placeholder="Search Post..">
                                    <button type="submit"><i class="ti-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="widget category-widget">
                            <h3>Categories</h3>
                            <ul>
                                <li><a href="blog.html">Guide Information<span>5</span></a></li>
                                <li><a href="blog.html">Hotel Management <span>7</span></a></li>
                                <li><a href="blog.html">Popular Hosts <span>3</span></a></li>
                                <li><a href="blog.html">Room With a View <span>6</span></a></li>
                                <li><a href="blog.html">Vacational Plan <span>2</span></a></li>
                            </ul>
                        </div>
                        <div class="widget recent-post-widget">
                            <h3>Related Posts</h3>
                            <div class="posts">
                                <div class="post">
                                    <div class="img-holder">
                                        <img src="assets/images/recent-posts/img-1.jpg" alt>
                                    </div>
                                    <div class="details">
                                        <h4><a href="blog-single.html">17 places you cannot ignore in Paris</a></h4>
                                        <span class="date">19 Jun 2023 </span>
                                    </div>
                                </div>
                                <div class="post">
                                    <div class="img-holder">
                                        <img src="assets/images/recent-posts/img-2.jpg" alt>
                                    </div>
                                    <div class="details">
                                        <h4><a href="blog-single.html">Be Careful About This, When You Are In
                                                Snow</a></h4>
                                        <span class="date">22 May 2023 </span>
                                    </div>
                                </div>
                                <div class="post">
                                    <div class="img-holder">
                                        <img src="assets/images/recent-posts/img-3.jpg" alt>
                                    </div>
                                    <div class="details">
                                        <h4><a href="blog-single.html">Things You Must Need To See While You’re In
                                                Dubai</a></h4>
                                        <span class="date">12 Apr 2023 </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget wpo-instagram-widget">
                            <div class="widget-title">
                                <h3>Discover Our Rooms</h3>
                            </div>
                            <ul class="d-flex">
                                <li><a href="hotel-single.html"><img src="assets/images/instragram/1.jpg"
                                            alt=""></a>
                                </li>
                                <li><a href="hotel-single.html"><img src="assets/images/instragram/2.jpg"
                                            alt=""></a>
                                </li>
                                <li><a href="hotel-single.html"><img src="assets/images/instragram/3.jpg"
                                            alt=""></a>
                                </li>
                                <li><a href="hotel-single.html"><img src="assets/images/instragram/4.jpg"
                                            alt=""></a>
                                </li>
                                <li><a href="hotel-single.html"><img src="assets/images/instragram/5.jpg"
                                            alt=""></a>
                                </li>
                                <li><a href="hotel-single.html"><img src="assets/images/instragram/6.jpg"
                                            alt=""></a>
                                </li>
                            </ul>
                        </div>
                        <div class="widget tag-widget">
                            <h3>Tags</h3>
                            <ul>
                                <li><a href="#">Travelling</a></li>
                                <li><a href="#">Hotel</a></li>
                                <li><a href="#">Restaurant</a></li>
                                <li><a href="destination.html">Destination</a></li>
                                <li><a href="#">World Tour</a></li>
                                <li><a href="#">Hotel Room</a></li>
                                <li><a href="#">Spa</a></li>
                                <li><a href="#">Guide</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
