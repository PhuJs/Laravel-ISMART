@extends('layouts.admin')
@section('title', 'Nội dung trang')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="list_post p-16">
            <div class="card">
                <div class="card-header fw-semibold fs-5">
                    NỘI DUNG TRANG
                </div>
                <div class="card-body">
                    <div class="content_page px-5 py-2">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.page.show', ['status' => $status]) }}" class="btn btn-primary mt-4">Trở lại</a>
        </div>
    </div>
@endsection
