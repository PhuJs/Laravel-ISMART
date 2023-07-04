<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ url('/') . '/' }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/css/all.min.css') }}" />
    <script src="https://cdn.tiny.cloud/1/u44hl1lgvd9j6zwng0cmr7g7vuclvck7bda2jn1q8u1gmrb3/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>

    <link rel="stylesheet" href=" {{ asset('css/bootstrap/bootstrap.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js//bootstrap/bootstrap.bundle.min.js') }}"></script>
    <title>@yield('title', 'ADMIN UNIMART')</title>
</head>

<body>
    <div id="wrapper">
        <div id="header" class="shadow sticky-top z-10 bg-white p-14">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-2">
                        <div class="nav-logo ">
                            <a href="{{ url('/dashboard') }}" id="admin-logo"
                                class="fs-4 fw-bolder text-decoration-none">ADMIN</a>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="nav_right d-flex align-items-center justify-content-between">
                            <div class="nav_add dropdown">
                                <button class="btn-t" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="button">
                                    <i class="text-secondary fa-solid fa-circle-plus fa-2xl"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('admin/product/add') }}">Thêm sản phẩm</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ url('admin/post/add') }}">Thêm bài viết</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ url('admin/user/add') }}">Thêm User</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ url('admin/slider/add') }}">Thêm Slider</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="nav_admin">
                                <div class="dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#"
                                        role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" v-pre>
                                        @if (Auth::user())
                                            {{ Auth::user()->name }}
                                        @else
                                            {{ __('Admin') }}
                                        @endif
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('admin.user.list') }}">Tài khoản</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Thoát') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="wp_content" class="mh-600">
            <div class="row">
                <div class="col-2 bg-transparent">
                    @php
                        $module_active = session('module_active');
                    @endphp
                    <div id="sidebar-left" class="pt-4">
                        <ul id="sidebar-menu" class="ps-0 pt-2">
                            <li class="{{ $module_active == 'dashboard' ? 'text-warning' : '' }}"><a
                                    href="{{ url('dashboard') }}"
                                    class="{{ $module_active == 'dashboard' ? 'text-warning' : '' }}">
                                    <i class="fa-solid fa-house"></i>
                                    Dashboard
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                            </li>
                            <li class="{{ $module_active == 'page' ? 'text-warning' : '' }}"><a
                                    href="{{ url('admin/page/show') }}"
                                    class="{{ $module_active == 'page' ? 'text-warning' : '' }}">
                                    <i class="fa-solid fa-file"></i>
                                    Trang
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ url('admin/page/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/page/show') }}">Danh sách</a></li>
                                </ul>
                            </li>
                            <li class="{{ $module_active == 'post' ? 'text-warning' : '' }}"><a
                                    href="{{ url('admin/post/list') }}"
                                    class="{{ $module_active == 'post' ? 'text-warning' : '' }}">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    Bài viết
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ url('admin/post/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/post/list') }}">Danh sách</a></li>
                                    <li><a href="{{ url('admin/post/cat/list') }}">Danh mục</a></li>
                                </ul>
                            </li>
                            <li class="{{ $module_active == 'product' ? 'text-warning' : '' }}"><a
                                    href="{{ url('admin/product/list') }}"
                                    class="{{ $module_active == 'product' ? 'text-warning' : '' }}">
                                    <i class="fa-solid fa-shop"></i>
                                    Sản phẩm
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ url('admin/product/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/product/list') }}">Danh sách</a></li>
                                    <li><a href="{{ url('admin/product/cat/list') }}">Danh mục</a></li>
                                </ul>
                            </li>
                            <li class="{{ $module_active == 'order' ? 'text-warning' : '' }}"><a
                                    href="{{ url('admin/order/show') }}"
                                    class="{{ $module_active == 'order' ? 'text-warning' : '' }}">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    Bán hàng
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ url('admin/order/show') }}">Danh sách đơn hàng</a></li>
                                </ul>
                            </li>
                            <li class="{{ $module_active == 'sliders' ? 'text-warning' : '' }}"><a
                                    href="{{ url('admin/slider/show') }}"
                                    class="{{ $module_active == 'sliders' ? 'text-warning' : '' }}">
                                    <i class="fa-solid fa-sliders"></i>
                                    Sliders
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ url('admin/slider/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/slider/show') }}">Danh sách Sliders</a></li>
                                </ul>
                            </li>
                            <li class="{{ $module_active == 'user' ? 'text-warning' : '' }}"><a
                                    href="{{ url('admin/user/list') }}"
                                    class="{{ $module_active == 'user' ? 'text-warning' : '' }}">
                                    <i class="fa-regular fa-user"></i>
                                    Users
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ url('admin/user/add') }}">Thêm mới</a></li>
                                    <li><a href="{{ url('admin/user/list') }}">Danh sách</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ request()->url() }}" class="">
                                    <i class="fa-solid fa-users"></i>
                                    Phân quyền
                                </a>
                                <span class="menu-dropdown">
                                    <i class="fa-solid fa-sort-down"></i>
                                </span>
                                <ul id="sub-menu">
                                    <li><a href="{{ request()->url() }}">Quyền</a></li>
                                    <li><a href="{{ request()->url() }}">Thêm vai trò</a></li>
                                    <li><a href="{{ request()->url() }}">Danh sách vai trò </a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-10 bg-body-secondary">
                    @yield('content')
                </div>
            </div>
        </div>
        <div id="footer">
        </div>
    </div>
    <script>
        document.getElementById('formFile').addEventListener('change', function(event) {
            var file = event.target.files[0];

            var reader = new FileReader;
            reader.onload = function(e) {
                document.getElementById('formImage').src = e.target.result;
                // document.getElementById('formImage').style.display = 'block';
            }
            reader.readAsDataURL(file);
        });
    </script>
    <script>
        var editor_config = {
            // Đường dẫn kết nối với thư viện FileManager 
            path_absolute: "http://localhost/phpmaster/project/laravel-project/",
            selector: '#product_detail',
            relative_urls: false,
            height: 700,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };

        tinymce.init(editor_config);

        var editor_config_2 = {
            // Đường dẫn kết nối với thư viện FileManager 
            path_absolute: "http://localhost/phpmaster/project/laravel-project/",
            selector: '#page_content',
            relative_urls: false,
            height: 800,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };

        tinymce.init(editor_config_2);
    </script>
</body>

</html>
