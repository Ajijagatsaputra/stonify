@extends('layouts.master')

@push('header-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    .article-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .article-thumbnail {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .article-content {
        padding: 20px;
    }
    .article-title {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: #333;
        font-weight: 600;
    }
    .article-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }
    .article-date {
        color: #888;
        font-size: 0.8rem;
    }
    .read-more {
        color: #2f2f2f;
        font-size: 0.9rem;
        text-decoration: none;
        font-weight: 500;
    }
    .read-more:hover {
        color: #000;
    }
    .hero {
        /* background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/img/hero-bg.jpg'); */
        background-size: cover;
        background-position: center;
        padding: 100px 0;
        color: white;
    }
    .hero h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .category-filter {
        margin-bottom: 30px;
    }
    .category-btn {
        padding: 8px 20px;
        margin: 0 5px;
        border: 2px solid #2f2f2f;
        border-radius: 25px;
        background: transparent;
        color: #2f2f2f;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .category-btn:hover, .category-btn.active {
        background: #2f2f2f;
        color: white;
    }
    .article-category {
        display: none;
    }
    .fade-out {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .fade-in {
        opacity: 1;
        transition: opacity 0.3s ease;
    }
</style>
@endpush

@section('title', 'Artikel - Stonify')

@section('content')

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Artikel</h1>
                    <p class="mb-4">Temukan berbagai artikel menarik seputar batu alam dan dekorasi</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <!-- Category Filter -->
        <div class="category-filter text-center">
            <button class="category-btn active" data-category="all">Semua</button>
            <button class="category-btn" data-category="tips">Tips & Trik</button>
            <button class="category-btn" data-category="inspirasi">Inspirasi</button>
            <button class="category-btn" data-category="trend">Trend</button>
            <button class="category-btn" data-category="news">Berita</button>
        </div>

        <div class="row" id="article-container">
            @foreach($artikels as $artikel)
                <div class="col-12 col-md-6 col-lg-4 mb-5 article-item" data-category="{{ $artikel->kategori }}">
                    <div class="article-card">
                        <img src="{{ asset('storage/'.$artikel->gambar) }}" class="article-thumbnail" alt="{{ $artikel->judul }}">
                        <div class="article-content">
                            <h3 class="article-title">{{ $artikel->judul }}</h3>
                            <p class="article-description">{{ Str::limit($artikel->deskripsi_singkat, 150) }}</p>
                        </div>
                        <div class="article-meta">
                            <span class="article-date">{{ $artikel->created_at->format('d M Y') }}</span>
                            <a href="{{ route('frontend.artikel-detail', $artikel->slug) }}" class="read-more">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        // Category filter functionality
        $('.category-btn').click(function() {
            $('.category-btn').removeClass('active');
            $(this).addClass('active');
            filterArticles($(this).data('category'));
        });

        // Add smooth scroll animation
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });
    });

    function filterArticles(category) {
        $('.article-card').each(function() {
            var card = $(this);
            // console.log(card);
            var cardCategory = card.closest('.article-item').data('category');
            if (category === 'all' || cardCategory == category) {
                card.show();
            } else {
                card.hide();
            }
        });
    }
</script>

@endsection
