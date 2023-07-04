@extends('layouts.admin')
@section('title', 'Cập nhật thẻ')
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
                            Cập nhật thẻ
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.product.tags.update', $tags->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="input_text" class="form-label">Tên thẻ</label>
                                    <input type="text" name="tags" id="input_text" class="form-control"
                                        placeholder="VD: Nổi bật, Bán chạy, Sale-off,..." value="{{ $tags->tags_name }}">
                                    @error('tags')
                                        <span class="d-block text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="submit" name="add_tags" value="Cập nhật" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card bg-dark-subtle">
                        <div class="card-header fs-5 fw-semibold">
                            Danh sách thẻ
                        </div>
                        <div class="card-body">
                            <div class="card-status mb-1">
                                <a href="" class="text-secondary text-decoration-none" onclick="return false">Tổng
                                    <span>({{ count($list_tags) }})</span></a>
                            </div>
                            <table class="table disabled">
                                <thead class="">
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
                                                <th scope="row">{{ $k }}</th>
                                                <td>{{ $tag->tags_name }}</td>
                                                <td>{{ $tag->user_create }}</td>
                                                <td>{{ $tag->created_at }}</td>
                                                <td>
                                                    <button class="btn btn-success rounded-0 border-0 me-1" type="button"
                                                        title="Edit" disabled><i
                                                            class="text-white fa-solid fa-pen-to-square"></i></a>
                                                        <button class="btn btn-danger rounded-0 border-0" type="button"
                                                            title="Delete" disabled><i
                                                                class="text-white fa-solid fa-trash"></i></a>
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
