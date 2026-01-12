@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Thêm mới</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.process.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Dịch vụ</label>
                <select name="service_id" class="form-control">
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Thứ tự</label>
                <input type="number" name="order" class="form-control" value="{{ old('order') }}">
            </div>

            <div class="mb-3">
                <label>Tiêu đề</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label>Trang</label>
                <input type="text" name="page" class="form-control" value="{{ old('page') }}">
            </div>

            <div class="mb-3">
                <label>Phần</label>
                <select name="section" class="form-control">
                    <option value="quy_trình" {{ old('section') == 'quy_trình' ? 'selected' : '' }}>Quy trình</option>
                    <option value="lí_do" {{ old('section') == 'lí_do' ? 'selected' : '' }}>Lí do</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Ảnh và thông tin</label>

                <div id="image-container">
                    {{-- Mặc định 1 khối đầu tiên --}}
                    <div class="image-item mb-3 border p-3">
                        <div class="mb-2">
                            <label>Ảnh 1</label>
                            <input type="file" name="images[]" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label>Tiêu đề ảnh</label>
                            <input type="text" name="image_titles[]" class="form-control"
                                value="{{ old('image_titles.0') }}">
                        </div>
                        <div class="mb-2">
                            <label>Mô tả</label>
                            <textarea name="image_descriptions[]" class="form-control">{{ old('image_descriptions.0') }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-image-btn" class="btn btn-primary">+ Thêm ảnh và nội dung</button>
            </div>

            {{-- Script thêm khối ảnh động --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let imageIndex = 1; // đã có 1 khối mặc định
                    const container = document.getElementById('image-container');
                    const addBtn = document.getElementById('add-image-btn');

                    addBtn.addEventListener('click', function() {
                        imageIndex++;

                        const newBlock = document.createElement('div');
                        newBlock.classList.add('image-item', 'mb-3', 'border', 'p-3');
                        newBlock.innerHTML = `
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <label>Ảnh ${imageIndex}</label>
                    <button type="button" class="btn btn-sm btn-danger remove-image">Xóa</button>
                </div>
                <div class="mb-2">
                    <input type="file" name="images[]" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Tiêu đề ảnh</label>
                    <input type="text" name="image_titles[]" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Mô tả</label>
                    <textarea name="image_descriptions[]" class="form-control"></textarea>
                </div>
            `;

                        container.appendChild(newBlock);

                        // Gắn sự kiện xóa
                        newBlock.querySelector('.remove-image').addEventListener('click', function() {
                            newBlock.remove();
                        });
                    });
                });
            </script>


            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.process.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
