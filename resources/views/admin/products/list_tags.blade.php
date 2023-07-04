@extends('layouts.admin')
@section('title', 'Danh sách thẻ')
@section('content')
    <div id="content" class="mh-full pt-4">
        <div class="p-16">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-header fs-5 fw-semibold">
                            Thêm Thẻ
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/product/tags/add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="input_text" class="form-label">Tên thẻ</label>
                                    <input type="text" name="tags" id="input_text" class="form-control"
                                        placeholder="VD: Nổi bật, Bán chạy, Sale-off,...">
                                    @error('tags')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="submit" name="add_tags" value="Thêm mới" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header fs-5 fw-semibold">
                            Danh sách thẻ
                        </div>
                        <div class="card-body">
                            <div class="card-status mb-1">
                                <a href="" class="text-decoration-none">Tổng <span>({{count($list_tags)}})</span></a>
                            </div>
                            <table class="table">
                                <thead class="table-light">
                                    <tr class="">
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên thẻ</th>
                                        <th scope="col">Người tạo</th>
                                        <th scope="col">Thời gian</th>
                                        <th scope="col">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($list_tags) > 0)
                                        @php
                                            $k = 0;
                                        @endphp
                                        @foreach ($list_tags as $tag)
                                            @php
                                                $k++;
                                            @endphp
                                            <tr class="vertical-center">
                                                <th scope="row">{{$k}}</th>
                                                <td>{{$tag->tags_name}}</td>
                                                <td>{{$tag->user_create}}</td>
                                                <td>{{$tag->created_at}}</td>
                                                <td>
                                                    <a href="{{ route('admin.product.tags.edit', $tag->id) }}" class="btn btn-success rounded-0 border-0"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Edit"><i
                                                            class="text-white fa-solid fa-pen-to-square"></i></a>
                                                    <a href="{{ route('admin.product.tags.delete', $tag->id) }}" class="btn btn-danger rounded-0 border-0"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="Delete"><i class="text-white fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                <span class="text-danger">Không có thẻ tồn tại</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
