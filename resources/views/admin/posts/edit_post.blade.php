@extends('layouts.admin')
@section('title', 'Cập nhật bài viết')
@section('content')
    <div class="content" class="mh-full pt-4">
        <div class="add_posts p-16">
            <div class="card">
                <div class="card-header fs-5 fw-semibold">
                    Cập nhật bài viết
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <form action="{{ route('admin.post.update', ['id' => $post->id, 'map' => $data_redirect]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="post_title" class="form-label">Tiêu đề bài viết</label>
                                    <input type="text" id="post_title" class="form-control" name="post_title"
                                        aria-label=".form-control-sm example"
                                        value="@if (old('post_title')) {{ old('post_title') }} @else{{ $post->post_title }} @endif">
                                    @error('post_title')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="post_desc" class="form-label">Mô tả bài viết</label>
                                    <textarea name="post_desc" id="post_desc" cols="30" rows="8" class="form-control"
                                        aria-label=".form-control-sm example">
@if (old('post_desc'))
{{ old('post_desc') }} @else{{ $post->post_desc }}
@endif
</textarea>
                                    @error('post_desc')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Link thân thiện</label>
                                    <input type="text" id="slug" class="form-control" name="slug"
                                        aria-label=".form-control-sm example"
                                        placeholder="Tạo link thân thiện với người dùng"
                                        value="@if (old('slug')) {{ old('slug') }} @else{{ $post->slug }} @endif">
                                </div>
                                <div class="mb-3">
                                    <label for="product_detail" class="form-label">Nội dung bài viết </label>
                                    <textarea name="post_content" id="product_detail" cols="30" rows="20" class="form-control" aria-label="">
@if (old('post_content'))
{{ old('post_content') }}
@else
{{ $post->post_content }}
@endif
</textarea>
                                    @error('post_content')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Hình ảnh bài viết</label>
                                    <input type="file" id="formFile" class="form-control" name="thumbnail"
                                        aria-label=".form-control-sm example">
                                    <img id="formImage" class="img-fluid img-thumbnail" src="{{ $post->thumbnail }}"
                                        alt="">
                                    @error('thumbnail')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="img_detail" class="form-label">Hình ảnh chi tiết bài viết</label>
                                    <input type="file" id="img_detail" class="form-control" name="img_detail[]"
                                        aria-label=".form-control-sm example" multiple>
                                    @error('img_detail.*')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Danh mục bài viết </label>
                                    <select name="post_cat" id="post_cat" class="form-select"
                                        aria-label="Default select example">
                                        <option value="0">---- Chọn ----</option>
                                        @foreach ($list_post_cat as $post_cat)
                                            <option value="{{ $post_cat->id }}"
                                                {{ $post->post_cat == $post_cat->id ? 'selected' : '' }}>
                                                {{ $post_cat->cat_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('post_cat')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="post_status"
                                            id="flexRadioDefault1" value="1"
                                            {{ $post->status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="post_status"
                                            id="flexRadioDefault2" value="2"
                                            {{ $post->status == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Hoạt động
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" name="submit-form" class="btn btn-primary" value="Cập nhật">
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
