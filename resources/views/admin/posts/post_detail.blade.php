@extends('layouts.admin')
@section('title', 'Chi tiết bài viết')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    CHI TIẾT BÀI VIẾT
                </div>
                <div class="card-body">
                    <div class="px-5">
                        <h3 class="fw-semibold">{{ $post->post_title}}</h3>
                        <p><span>{{$post->user_create}}</span>|<span>{{$post->created_at}}</span></p>
                        <h5 class="fw-semibold">{{$post->post_desc}}</h5>
                        <p>{!! $post->post_content !!}</p>
                    </div>
                </div>
            </div>
            <br>
            <a href="{{ route('admin.post.list', $data_redirect) }}" class="btn btn-primary">Trở lại</a>
        </div>
    </div>
@endsection
