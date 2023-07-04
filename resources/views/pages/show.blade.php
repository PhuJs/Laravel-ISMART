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
                                <a href="{{ request()->url() }}">{{ $page->title }}</a>
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
                        <div class="section_about" style="padding:25px 30px;">
                            {!! $page->content !!}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
