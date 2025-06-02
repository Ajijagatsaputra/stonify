@extends('layouts.master')

@push('styles')
<style>
    .article-detail {
        padding: 60px 0;
        background-color: #f8fafc;
    }
    .article-header {
        margin-bottom: 40px;
        text-align: center;
    }
    .article-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 20px;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }
    .article-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 25px;
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 30px;
    }
    .article-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border-radius: 50px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .article-meta span:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .article-meta i {
        font-size: 1.1rem;
        color: #3b82f6;
    }
    .featured-image-container {
        position: relative;
        margin-bottom: 40px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .featured-image {
        width: 100%;
        max-height: 600px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .featured-image:hover {
        transform: scale(1.02);
    }
    .article-description {
        font-size: 1.25rem;
        line-height: 1.8;
        color: #4b5563;
        margin-bottom: 40px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .article-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #374151;
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .article-content p {
        margin-bottom: 25px;
    }
    .article-content img {
        max-width: 100%;
        border-radius: 12px;
        margin: 30px 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .article-tags {
        margin: 40px 0;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .tag {
        padding: 8px 16px;
        background: #f1f5f9;
        border-radius: 50px;
        color: #475569;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #e2e8f0;
    }
    .tag:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .tag i {
        color: #3b82f6;
    }
    .share-section {
        margin-top: 50px;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .share-title {
        font-size: 1.4rem;
        color: #1a202c;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .share-title i {
        color: #3b82f6;
    }
    .share-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    .share-btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 24px;
        border-radius: 50px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1rem;
        font-weight: 500;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        color: white;
    }
    .share-btn i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    .share-btn.facebook {
        background: linear-gradient(45deg, #1877f2, #0d6efd);
    }
    .share-btn.twitter {
        background: linear-gradient(45deg, #1da1f2, #0ea5e9);
    }
    .share-btn.whatsapp {
        background: linear-gradient(45deg, #25d366, #22c55e);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .article-title {
            font-size: 2rem;
        }
        .article-meta {
            flex-direction: column;
            gap: 15px;
        }
        .article-content {
            padding: 20px;
        }
        .share-section {
            padding: 20px;
        }
    }
</style>
@endpush

@section('title', $artikel->judul . ' - Stonify')

@section('content')
<div class="article-detail">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <article>
                    <header class="article-header mt-5 mb-5 text-center">
                        <h1 class="article-title">{{ $artikel->judul }}</h1>
                        <div class="article-meta">
                            <span><i class="bi bi-calendar3"></i> {{ $artikel->created_at->format('d M Y') }}</span>
                            <span><i class="bi bi-bookmark-fill"></i> {{ $artikel->kategori }}</span>
                            <span><i class="bi bi-clock"></i> 5 min read</span>
                        </div>
                    </header>

                    <div class="featured-image-container mb-2">
                        <img src="{{ asset('storage/'.$artikel->gambar) }}" alt="{{ $artikel->judul }}" class="featured-image">
                    </div>

                    <div class="article-description mb-2">
                        {{ $artikel->deskripsi_singkat }}
                    </div>

                    <div class="article-content mb-5">
                        {!! $artikel->konten !!}
                    </div>

                    @if($artikel->tags)
                    <div class="article-tags">Tags :
                        @foreach(explode(',', $artikel->tags) as $tag)
                            <span class="tag">
                                <i class="bi bi-tag-fill"></i>
                                {{ trim($tag) }}
                            </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="share-section mb-5 mt-5">
                        <h4 class="share-title">
                            <i class="bi bi-share-fill"></i>
                            Bagikan artikel ini
                        </h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                               class="share-btn facebook" target="_blank">
                                <i class="bi bi-facebook"></i>Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $artikel->judul }}"
                               class="share-btn twitter" target="_blank">
                                <i class="bi bi-twitter"></i>Twitter
                            </a>
                            <a href="https://wa.me/?text={{ $artikel->judul }} {{ url()->current() }}"
                               class="share-btn whatsapp" target="_blank">
                                <i class="bi bi-whatsapp"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
@endsection
