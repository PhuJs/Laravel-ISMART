@extends('layouts.apps')
@section('title', 'Tin công nghệ')
@section('content')
    <div id="wapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb">
                        <i class="fa-solid fa-house-chimney"></i>
                        <ul class="list_item">
                            <li class="">
                                <i class="fa-solid fa-angle-right"></i>
                                <a href="?">Trang chủ</a>
                            </li>
                            <li class="">
                                <i class="fa-solid fa-angle-right"></i>
                                <a href="{{ request()->url() }}">{{ __('Tin công nghệ') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-3">
                    <!-- ==== SIDEBAR LEFT ==== -->
                    @include('layouts.sidebar-sp-detail')
                </div>
                <div class="gx-0 col-lg-9 gx-lg-4">
                    <!-- ===== CONTENT ==== -->
                    <section id="content">
                        <div class="list_post">
                            <h3 class="">Danh Sách Bài Viết</h3>
                            @if ($list_post->total() > 0)
                                @foreach ($list_post as $post)
                                    <div class="post_item mb-4">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 col-sm-4 mb-2">
                                                    <a href="{{ route('post.detail', $post->slug) }}">
                                                        <img src="{{ $post->thumbnail }}" alt=""
                                                            class="img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col-12 col-sm-8">
                                                    <a href="{{ route('post.detail', $post->slug) }}" class="post_title">
                                                       {{ $post->post_title }}
                                                    </a>
                                                    <span class="post_time">
                                                        <i class="fa-regular fa-clock"></i>
                                                        {{ $post->created_at }}</span>
                                                    <p class="post_desc">
                                                        {{ $post->post_desc }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    KHÔNG CÓ BÀI VIẾT TỒN TẠI
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
