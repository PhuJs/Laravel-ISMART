@extends('layouts.admin')
@section('title', 'Cập nhật Slider')
@section('content')
    <div class="content" class="mh-full pt-4">
        <div class="add_posts p-16">
            <div class="card">
                <div class="card-header fs-5 fw-semibold">
                   Cập nhật Sliders
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('admin.slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="post_title" class="form-label">Tiêu đề</label>
                                    <input type="text" id="post_title" class="form-control" name="slider_title"
                                        aria-label=".form-control-sm example" value="{{ $slider->title }}">
                                    @error('slider_title')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="post_desc" class="form-label">Mô tả</label>
                                    <textarea name="slider_desc" id="post_desc" cols="30" rows="4" class="form-control"
                                        aria-label=".form-control-sm example">{{ $slider->desc }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="slug" class="form-label">URL</label>
                                    <input type="text" id="slug" class="form-control" name="link"
                                        aria-label=".form-control-sm example" value="{{ $slider->url }}">
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Hình ảnh Slider</label>
                                    <input type="file" id="formFile" class="form-control" name="thumb"
                                        aria-label=".form-control-sm example">
                                    <img id="formImage" class="img-fluid img-thumbnail"
                                        src="{{$slider->thumb}}" alt="Ảnh Sliders">
                                    @error('thumb')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="slider_status"
                                            id="flexRadioDefault1" value="1" {{ ($slider->status == 1)?'checked':'' }}>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="slider_status"
                                            id="flexRadioDefault2" value="2" {{ ($slider->status == 2)?'checked':'' }}>
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
                        <div class="col-6">
                            <!-- Trống -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection