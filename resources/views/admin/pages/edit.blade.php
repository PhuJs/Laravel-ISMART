@extends('layouts.admin')
@section('title', 'Cập nhật trang')
@section('content')
    <div class="content" class="mh-full pt-4">
        <div class="add_posts p-16">
            <div class="card">
                <div class="card-header fs-5 fw-semibold">
                    Cập nhật trang
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <form action="{{ route('admin.page.update', $page->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="page_title" class="form-label">Tiêu đề trang</label>
                                    <input type="text" name="page_title" class="form-control" id="page_title"
                                        value="{{ $page->title }}">
                                    @error('page_title')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="page_url" class="form-label">URL</label>
                                    <input type="text" name="page_url" class="form-control" id="page_url"
                                        value="{{ $page->url }}" placeholder="VD: lien-he.html">
                                    @error('page_url')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="page_content" class="form-label">Nội dung trang</label>
                                    <textarea name="page_content" id="page_content" cols="30" rows="20">
                                        {{ $page->content }}
                                    </textarea>
                                    @error('page_content')
                                        <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="radio" name="page_status" class="form-check-input"
                                            id="formCheckInput1" value="1" {{ ($page->status == 1)?'checked':'' }}>
                                        <label for="formCheckInput1" class="form-check-label">Chờ duyệt</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="page_status" class="form-check-input"
                                            id="formCheckInput2" value="2"  {{ ($page->status == 2)?'checked':'' }}>
                                        <label for="formCheckInput2" class="form-label-label">Hoạt động</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" name="submit_page" class="btn btn-primary" value="Cập nhật">
                                </div>
                            </form>
                        </div>
                        <div class="col-4">
                            <!-- Trống -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
