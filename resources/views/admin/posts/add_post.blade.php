@extends('layouts.admin')
@section('title', 'Thêm bài viết')
@section('content')
    <div class="content" class="mh-full pt-4">
        <div class="add_posts p-16">
            <div class="card">
                <div class="card-header fs-5 fw-semibold">
                    Thêm bài viết
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="post_title" class="form-label">Tiêu đề bài viết</label>
                                    <input type="text" id="post_title" class="form-control" name="post_title"
                                        aria-label=".form-control-sm example" value="{{ old('post_title') }}">
                                    @error('post_title')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="post_desc" class="form-label">Mô tả bài viết</label>
                                    <textarea name="post_desc" id="post_desc" cols="30" rows="8" class="form-control"
                                        aria-label=".form-control-sm example">{{old('post_desc')}}</textarea>
                                    @error('post_desc')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug thân thiện</label>
                                    <input type="text" id="slug" class="form-control" name="slug"
                                        aria-label=".form-control-sm example"
                                        placeholder="Tạo link thân thiện với người dùng" value="{{old('slug')}}">
                                </div>
                                <div class="mb-3">
                                    <label for="product_detail" class="form-label">Nội dung bài viết</label>
                                    <textarea name="post_content" id="product_detail" cols="30" rows="20" class="form-control"
                                        aria-label=".form-control-sm example">{{old('post_content')}}</textarea>
                                    @error('post_content')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Hình ảnh bài viết</label>
                                    <input type="file" id="formFile" class="form-control" name="thumbnail"
                                        aria-label=".form-control-sm example">
                                        <img id="formImage" class="img-fluid img-thumbnail" src="public/thumbnails/img-thumb.png" alt="Ảnh Lukaku">
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
                                            <option value="{{ $post_cat->id }}">{{ $post_cat->cat_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('post_cat')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="post_status"
                                            id="flexRadioDefault1" value="1" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="post_status"
                                            id="flexRadioDefault2" value="2">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Hoạt động
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="submit" name="submit-form" class="btn btn-primary" value="Thêm bài viết">
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
