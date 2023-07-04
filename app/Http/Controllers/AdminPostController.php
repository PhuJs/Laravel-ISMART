<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Post_cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;




class AdminPostController extends Controller
{
    function __construct(){
        $this->middleware(function($request, $next){
            session(['module_active' => 'post']);
            return $next($request);
        });
    }

    /** 
     *  Show Data 
     */
    function show_data($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     * * Hàm sắp xếp danh mục trả về phân cấp
     * 
     */
    function data_tree($list_post_cat, $parent_id = 0, $level = 0)
    {
        $result = [];
        foreach ($list_post_cat as $post_cat) {
            if ($post_cat['parent_id'] == $parent_id) {
                $post_cat['level'] = $level;
                $result[] = $post_cat;
                $result_arr = $this->data_tree($list_post_cat, $post_cat['id'], $level + 1);
                $result = array_merge($result, $result_arr);
            }
        }
        return $result;
    }

    /**
     * Xử lí cho phép xét duyệt danh mục
     * 
     */
    function check_approval_cat($post_cat)
    {
        $flag = TRUE;
        $list_post_cat = Post_cat::withTrashed()->get();
        foreach ($list_post_cat as $element) {
            if ($post_cat->parent_id == $element->id) {
                if ($element->trashed()) {
                    $flag = FALSE;
                } else {
                    $flag = $this->check_approval_cat($element);
                }
            }
        }
        return $flag;
    }

    /** 
     * Thống kê số lượng danh mục chờ xét duyệt
     */
    function total_post_cats_approval()
    {
        $list_post_cat = [];
        $post_cats = Post_cat::where('status', 1)->get();
        foreach ($post_cats as $post_cat) {
            if ($this->check_approval_cat($post_cat)) {
                $list_post_cat[] = $post_cat;
            }
        }
        return count($list_post_cat);
    }

    /**
     * Danh sách danh mục bài viết
     * 
     */
    function list_cat(Request $request)
    {
        if ($request->input('status') == 'trash') {
            $list_post_cat = Post_cat::onlyTrashed()->get();
        } elseif ($request->input('status') == 'approval') {
            $list_post_cat = [];
            $list_post_cat_approval = Post_cat::where('status', 1)->get();
            foreach ($list_post_cat_approval as $post_cat) {
                if ($this->check_approval_cat($post_cat)) {
                    $list_post_cat[] = $post_cat;
                }
            }
        } else {
            $post_cats = Post_cat::where('status', 2)->get();
            $list_post_cat = $this->data_tree($post_cats);
        }

        $post_cats_active = Post_cat::where('status', 2)->count();
        $post_cats_approval = $this->total_post_cats_approval();
        $post_cats_trash = Post_cat::onlyTrashed()->count();
        $count = [
            $post_cats_active,
            $post_cats_approval,
            $post_cats_trash,
        ];

        $list_cat_selected = Post_cat::all();
        return view('admin.posts.list_cat', compact('list_cat_selected', 'list_post_cat', 'count'));
    }


    /**
     * Thêm danh mục bài viết
     * 
     */
    function add_cat(Request $request)
    {
        $request->validate(
            [
                'name' => 'required'
            ],
            [
                'name.required' => 'Vui lòng nhập tên danh mục'
            ]
        );

        $data = [
            'cat_name' => $request->input('name'),
            'parent_id' => $request->input('cat'),
            'user_create' => Auth::user()->name,
        ];

        Post_cat::create($data);
        return redirect('admin/post/cat/list')->with('alert', 'Thêm mới thành công');
    }

    /** 
     * Kiểm tra trạng thái danh mục cha
     * 
     */
    function check_status_parent($post_cat_item)
    {
        $flag = TRUE;
        $list_post_cat = Post_cat::all();
        foreach ($list_post_cat as $element) {
            if ($post_cat_item->parent_id == $element->id) {
                if ($element->status == 1) {
                    $flag = FALSE;
                } else {
                    $flag = $this->check_status_parent($element);
                }
            }
        }
        return $flag;
    }

    /** 
     * Xét duyệt danh mục bài viết
     * 
     */
    function approval_post_cat($id)
    {
        $post_cat_item = Post_cat::where('id', $id)->first();
        if ($post_cat_item->parent_id == 0) {
            $post_cat_item->status = 2;
            $post_cat_item->save();
            return redirect('admin/post/cat/list')->with('alert', 'Xét duyệt thành công');
        } else {
            if ($this->check_status_parent($post_cat_item)) {
                $post_cat_item->status = 2;
                $post_cat_item->save();
                return redirect('admin/post/cat/list')->with('alert', 'Xét duyệt thành công');
            } else {
                return redirect()->route('admin.post.cat.list', ['status' => 'approval'])->with('alert_error', 'Danh mục cha chưa hoạt động vui lòng kiểm tra lại');
            }
        }
    }

    /** 
     * Xóa tạm thời danh mục
     */
    function delete_post_cat(Request $request, $id)
    {
        Post_cat::destroy($id);
        return redirect()->route('admin.post.cat.list', ['status' => $request->input('status')])->with('alert', 'Xóa thành công');
    }

    /** 
     * Cập nhật danh mục
     */
    function edit_post_cat($id)
    {

        $list_cat_selected = Post_cat::all();
        $post_cats = Post_cat::where('status', 2)->get();
        $list_post_cat = $this->data_tree($post_cats);
        $post_cat_item = Post_cat::where('id', $id)->first();

        $post_cats_active = Post_cat::where('status', 2)->count();
        $post_cats_approval = $this->total_post_cats_approval();
        $post_cats_trash = Post_cat::onlyTrashed()->count();
        $count = [
            $post_cats_active,
            $post_cats_approval,
            $post_cats_trash,
        ];

        return view('admin.posts.edit_cat', compact('list_cat_selected', 'list_post_cat', 'post_cats', 'count', 'post_cat_item'));
    }

    /** 
     * Tiếp tục xử lí cập nhật
     */
    function update_post_cat(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Vui lòng nhập tên danh mục',
            ],
        );

        $data = [
            'cat_name' => $request->input('name'),
            'parent_id' => $request->input('cat'),
        ];

        Post_cat::where('id', $id)->update($data);
        return redirect('admin/post/cat/list')->with('alert', 'Cập nhật danh mục thành công');
    }

    /**
     * Khôi phục danh mục 
     */
    function restore_post_cat($id)
    {
        Post_cat::onlyTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.post.cat.list', ['status' => 'trash'])->with('alert', 'Khôi phục thành công');
    }

    /** 
     * Xóa vĩnh viễn danh mục
     * 
     */
    function forceDelete_post_cat($id)
    {
        Post_cat::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('admin.post.cat.list', ['status' => 'trash'])->with('alert', 'Xóa thành công');
    }

    /***
     *  =========== POST ==================
     */

    /** 
     * Thêm bài viết
     * 1. Tạo view
     */
    function add_post()
    {
        $list_post_cat = Post_cat::where('status', 2)->get();

        return view('admin/posts/add_post', compact('list_post_cat'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'post_title' => ['required'],
                'post_desc' => ['required'],
                'post_content' => ['required'],
                'thumbnail' => ['required', 'image', 'max:20480'],
                'img_detail.*' => ['image', 'max:20480'],
                'post_cat' => [Rule::notIn([0])],
            ],
            [
                'required' => ':attribute không được để trống',
                'thumbnail.required' => 'Vui lòng chọn hình ảnh đại diện bài viết',
                'thumbnail.image' => 'Tệp tin không đúng định dạng hình ảnh',
                'thumbnail.max' => 'Tệp tin có dung lượng phải nhỏ hơn :max',
                'img_detail.*.image' => 'Các tệp tin không đúng định dạng hình ảnh',
                'img_detail.*.max' => 'Các tệp tin có dung lượng phải nhỏ hơn :max',
                'post_cat.not_in' => 'Chọn danh mục bài viết cụ thể'
            ],
            [
                'post_title' => 'Tiêu đề bài viết',
                'post_desc' => 'Mô tả bài viết',
                'post_content' => 'Nội dung bài viết',
            ]
        );

        $folder = "public/thumbnails/posts/";
        $thumbnail = $request->file('thumbnail');
        $file_name = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
        $file_extension = $thumbnail->getClientOriginalExtension();

        $path_file = $folder . $thumbnail->getClientOriginalName();
        if (file_exists($path_file)) {
            $dem = 1;
            while (file_exists($path_file)) {
                $new_file_name = $file_name . "-({$dem})." . $file_extension;
                $new_path_file = $folder . $new_file_name;
                $path_file = $new_path_file;
                $dem++;
            }
            $thumbnail->move($folder, $new_file_name);
        } else {
            $thumbnail->move($folder, $thumbnail->getClientOriginalName());
        }

        $data = [
            'post_title' => $request->input('post_title'),
            'post_desc' => $request->input('post_desc'),
            'user_create' => Auth::user()->name,
            'post_content' => $request->input('post_content'),
            'thumbnail' => $path_file,
            'status' => $request->input('post_status'),
            'post_cat' => $request->input('post_cat'),
            'slug' => Str::slug($request->input('slug')),
        ];

        $post = Post::create($data);

        if ($request->hasFile('img_detail')) {
            $folder_files = 'public/img/posts/';
            foreach ($request->file('img_detail') as $file) {
                $files_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $files_extension = $file->getClientOriginalExtension();
                $path_files = $folder_files . $file->getClientOriginalName();

                if (file_exists($path_files)) {
                    $tem = 1;
                    while (file_exists($path_files)) {
                        $new_files_name = $files_name . "-({$tem})." . $files_extension;
                        $new_path_files = $folder_files . $new_files_name;
                        $path_files = $new_path_files;
                        $tem++;
                    }
                    $file->move($folder_files, $new_files_name);
                } else {
                    $file->move($folder_files, $file->getClientOriginalName());
                }
                Image::create([
                    'url' => $path_files,
                    'post_id' => $post->id,
                ]);
            }
        }

        if ($request->input('post_status') == 1) {
            return redirect()->route('admin.post.list', ['status' => 'approval'])->with('status', 'Thêm mới thành công');
        } else {
            return redirect('admin/post/list')->with('status', 'Thêm mới thành công');
        }
    }

    /** 
     * Hiển thị danh sách bài viết
     * 
     */
    function list_post(Request $request)
    {
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
            $list_post = Post::join('post_cats', 'posts.post_cat', '=', 'post_cats.id')
                ->select('posts.*', 'post_cats.cat_name')
                ->where('posts.post_title', 'LIKE', "%{$keyword}%")
                ->orwhere('posts.post_desc', 'LIKE', "%{$keyword}%")
                ->orwhere('posts.user_create', 'LIKE', "%{$keyword}%")
                ->orderBy('posts.created_at', 'desc')
                ->paginate(15);

            $total = Post::where('post_title', 'LIKE', "%{$keyword}%")
                ->orwhere('post_desc', 'LIKE', "%{$keyword}%")
                ->orwhere('user_create', 'LIKE', "%{$keyword}%")
                ->count();

            $list_act = [
                'delete' => 'Vô hiệu hóa',
                'approval' => 'Xét duyệt',
            ];

            $count[] = $total;
        } else {
            if ($request->input('status') == 'trash') {
                $list_post = Post::withTrashed()
                    ->join('post_cats', 'posts.post_cat', '=', 'post_cats.id')
                    ->select('posts.*', 'post_cats.cat_name')
                    ->whereNotNull('posts.deleted_at')
                    ->orderBy('posts.created_at', 'desc')
                    ->paginate(15);

                $list_act = [
                    'restore' => 'Khôi phục',
                    'forceDelete' => 'Xóa vĩnh viễn',
                ];
            } elseif ($request->input('status') == 'approval') {
                $list_post = Post::join('post_cats', 'posts.post_cat', '=', 'post_cats.id')
                    ->select('posts.*', 'post_cats.cat_name')
                    ->orderBy('posts.created_at', 'desc')
                    ->where('posts.status', 1)->paginate(15);
                $list_act = [
                    'approval' => 'Xét duyệt',
                    'delete' => 'Vô hiệu hóa',
                ];
            } else {
                $list_post = Post::join('post_cats', 'posts.post_cat', '=', 'post_cats.id')
                    ->select('posts.*', 'post_cats.cat_name')
                    ->orderBy('posts.created_at', 'desc')
                    ->where('posts.status', 2)->paginate(15);
                $list_act = [
                    'delete' => 'Vô hiệu hóa',
                ];
            }
            $total_post_action = Post::where('status', 2)->count();
            $total_post_approval = Post::where('status', 1)->count();
            $total_post_trash = Post::onlyTrashed()->count();
            $count = [
                $total_post_action,
                $total_post_approval,
                $total_post_trash,
            ];
        }

        return view('admin.posts.list_post', compact('list_post', 'count', 'list_act'));
    }

    /**
     * Xử lí các tác vụ bài viết
     * 
     */
    function action(Request $request)
    {
        $list_status = [
            'action',
            'approval',
            'trash'
        ];
        $status = $request->input('status');
        $page = $request->input('page');
        if (in_array($status, $list_status)) {
            $data_redirect = [
                'status' => $status,
                'page' => $page,
            ];
        } else {
            $data_redirect = [
                'keyword' => $status,
                'page' => $page,
            ];
        }

        $list_check = $request->input('list_check');
        if (empty($list_check)) {
            return redirect()->route('admin.post.list', $data_redirect)->with('status_error', 'Chọn ít nhất 1 bản ghi để thực hiện tác vụ');
        }

        $action = $request->input('action');
        if ($action == 'restore') {
            foreach ($list_check as $item) {
                Post::onlyTrashed()->where('id', $item)->restore();
            }
            return redirect()->route('admin.post.list', $data_redirect)->with('status', 'Khôi phục thành công');
        } elseif ($action == 'forceDelete') {
            foreach ($list_check as $item) {
                Post::withTrashed()->where('id', $item)->forceDelete();
            }
            return redirect()->route('admin.post.list', $data_redirect)->with('status', 'Đã xóa các bài viết ra khỏi hệ thống');
        } elseif ($action == 'delete') {
            foreach ($list_check as $item) {
                Post::destroy($item);
            }
            return redirect()->route('admin.post.list', $data_redirect)->with('status', 'Xóa thành công');
        } elseif ($action == 'approval') {
            foreach ($list_check as $item) {
                Post::where('id', $item)->update([
                    'status' => 2,
                ]);
            }
            return redirect()->route('admin.post.list', $data_redirect)->with('status', "Xét duyệt thàh công");
        } else {
            return redirect()->route('admin.post.list', $data_redirect)->with('status', 'Chọn tác vụ cụ thể');
        }
    }

    /** 
     * Xem chi tiết bài viết
     * 
     */
    function post_detail(Request $request, $id)
    {
        $list_status = [
            'action',
            'approval',
            'trash'
        ];

        $status = $request->input('status');
        $page = $request->input('page');
        if (in_array($status, $list_status)) {
            $data_redirect = [
                'status' => $status,
                'page' => $page,
            ];
        } else {
            $data_redirect = [
                'keyword' => $status,
                'page' => $page,
            ];
        }

        if ($request->input('status') == 'trash') {
            $post = Post::onlyTrashed()->find($id);
        } else {
            $post = Post::find($id);
        }
        return view('admin.posts.post_detail', compact('post', 'data_redirect'));
    }



    /**
     * Xóa bài viết
     */
    function delete_post(Request $request, $id)
    {
        $list_status = [
            'action',
            'approval',
            'trash'
        ];

        $status = $request->input('status');
        $page = $request->input('page');
        if (in_array($status, $list_status)) {
            $data_redirect = [
                'status' => $status,
                'page' => $page,
            ];
        } else {
            $data_redirect = [
                'keyword' => $status,
                'page' => $page,
            ];
        }

        Post::destroy($id);
        return redirect()->route('admin.post.list', $data_redirect)->with('status', 'Xóa bài viết thành công');
    }

    /** 
     * Cập nhật bài viết 
     * 
     */
    function edit(Request $request, $id)
    {
        
        $list_status = [
            'action',
            'approval',
            'trash'
        ];

        $status = $request->input('status');
        $page = $request->input('page');
        if (in_array($status, $list_status)) {
            $data_redirect = [
                'status' => $status,
                'page' => $page,
            ];
        } else {
            $data_redirect = [
                'keyword' => $status,
                'page' => $page,
            ];
        }

        $list_post_cat = Post_cat::where('status', 2)->get();
        $post = Post::find($id);
        return view('admin.posts.edit_post', compact('list_post_cat', 'post', 'data_redirect'));
    }

    function update(Request $request, $id)
    {
        $data_redirect = $request->input('map');
       
        $request->validate(
            [
                'post_title' => 'required',
                'post_desc' => 'required',
                'post_content' => 'required',
                'thumbnail' => 'image', 'max:20480',
                'img_detail.*' => 'image', 'max:20480',
                'post_cat' => [Rule::notIn([0])],
            ],
            [
                'required' => ':attribute không được để trống',
                'thumbnail.required' => 'Vui lòng chọn hình ảnh đại diện bài viết',
                'thumbnail.image' => 'Tệp tin không đúng định dạng hình ảnh',
                'thumbnail.max' => 'Tệp tin có dung lượng phải nhỏ hơn :max',
                'img_detail.*.image' => 'Các tệp tin không đúng định dạng hình ảnh',
                'img_detail.*.max' => 'Các tệp tin có dung lượng phải nhỏ hơn :max',
                'post_cat.not_in' => 'Chọn danh mục bài viết cụ thể'
            ],
            [
                'post_title' => 'Tiêu đề bài viết',
                'post_desc' => 'Mô tả bài viết',
                'post_content' => 'Nội dung bài viết',
            ]
        );

        if ($request->file('thumbnail')) {
            $folder = "public/thumbnails/posts/";
            $thumbnail = $request->file('thumbnail');
            $file_name = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
            $file_extension = $thumbnail->getClientOriginalExtension();

            $path_file = $folder . $thumbnail->getClientOriginalName();
            if (file_exists($path_file)) {
                $dem = 1;
                while (file_exists($path_file)) {
                    $new_file_name = $file_name . "-({$dem})." . $file_extension;
                    $new_path_file = $folder . $new_file_name;
                    $path_file = $new_path_file;
                    $dem++;
                }
                $thumbnail->move($folder, $new_file_name);
            } else {
                $thumbnail->move($folder, $thumbnail->getClientOriginalName());
            }

            $data = [
                'post_title' => $request->input('post_title'),
                'post_desc' => $request->input('post_desc'),
                'user_create' => Auth::user()->name,
                'post_content' => $request->input('post_content'),
                'thumbnail' => $path_file,
                'status' => $request->input('post_status'),
                'post_cat' => $request->input('post_cat'),
                'slug' => Str::slug($request->input('slug')),
            ];
        }else{
            $data = [
                'post_title' => $request->input('post_title'),
                'post_desc' => $request->input('post_desc'),
                'user_create' => Auth::user()->name,
                'post_content' => $request->input('post_content'),
                'status' => $request->input('post_status'),
                'post_cat' => $request->input('post_cat'),
                'slug' => Str::slug($request->input('slug')),
            ];
        }


        $post = Post::find($id);
        $post->update($data);

        if ($request->hasFile('img_detail')) {
            Image::where('post_id', $post->id)->delete();
            $folder_files = 'public/img/posts/';
            foreach ($request->file('img_detail') as $file) {
                $files_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $files_extension = $file->getClientOriginalExtension();
                $path_files = $folder_files . $file->getClientOriginalName();

                if (file_exists($path_files)) {
                    $tem = 1;
                    while (file_exists($path_files)) {
                        $new_files_name = $files_name . "-({$tem})." . $files_extension;
                        $new_path_files = $folder_files . $new_files_name;
                        $path_files = $new_path_files;
                        $tem++;
                    }
                    $file->move($folder_files, $new_files_name);
                } else {
                    $file->move($folder_files, $file->getClientOriginalName());
                }

                Image::create([
                    'url' => $path_files,
                    'post_id' => $post->id,
                ]);
            }
        }

        return redirect()->route('admin.post.list', $data_redirect)->with('status', 'Cập nhật thành công');
    }

    /** 
     * Khôi phục bài viết
     * 
     */
    function restore(Request $request, $id){
        Post::onlyTrashed()->find($id)->restore();
        return redirect()->route('admin.post.list', ['status' => 'trash', 'page' => $request->input('page')])->with('status', 'Khôi phục bài viết thành công');
    }

    /**
     * Xóa vĩnh viễn bài viết 
     * 
     */

     function forceDelete(Request $request, $id){
        Post::onlyTrashed()->find($id)->forceDelete();
        return redirect()->route('admin.post.list', ['status' => 'trash', 'page' => $request->input('page')])->with('status', 'Đã xóa bài viết ra khỏi hệ thống');
     }
}
