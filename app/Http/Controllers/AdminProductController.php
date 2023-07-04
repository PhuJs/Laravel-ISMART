<?php

namespace App\Http\Controllers;

use App\Models\Product_cat as Dmsp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Image;
use App\Models\Product_tag as Tags;

class AdminProductController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }

    /**
     * * *  Hàm xử lí dữ liệu trả về theo dạng đa cấp
     */
    function data_tree($list_cat, $parent = 0, $level =  0)
    {
        $result = [];
        foreach ($list_cat as $item) {
            if ($item['parent_id'] == $parent) {
                $item['level'] = $level;
                $result[] = $item;
                $result_arr = $this->data_tree($list_cat, $item['id'], $level + 1);
                $result = array_merge($result, $result_arr);
            }
        }
        return $result;
    }

    /** 
     * Xử lí xét duyệt danh mục
     */
    function check_approval_cat($product_cat)
    {
        $flag = TRUE;
        $list_product_cat = Dmsp::withTrashed()->get();
        foreach ($list_product_cat as $element) {
            if ($product_cat->parent_id == $element->id) {
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
     * * * Lấy tổng số lượng danh mục đang chờ xác nhận 
     * 
     * 1. Lấy tất cả danh mục ngoại trừ danh mục trong thùng rác
     * 2. Lấy danh sách danh mục trong trạng thái xét duyệt
     * 3. Lọc lại các danh mục có danh mục cha bị vô hiệu hóa
     */
    function get_total_cat_confirm()
    {
        $list_cat = [];
        $list_product_cat = Dmsp::where('status', 1)->get();
        foreach ($list_product_cat as $product_cat) {
            if ($this->check_approval_cat($product_cat)) {
                $list_cat[] = $product_cat;
            }
        }
        return count($list_cat);
    }

    /** 
     * * Xử lí hiển thị danh sách danh mục sản phẩm
     * 
     * 1. Lấy tất cả danh mục ($list_cat_all)
     * 2. Kiểm tra trạng thái yêu cầu ($request) lấy danh mục theo trạng thái
     * 3. Thống kế số lượng danh mục trong các trạng thái ($count)
     */
    function show_cat(Request $request)
    {
        $list_cat_all = Dmsp::query()->whereNull('deleted_at')->get();
        if ($request->input('status') == 'confirm') {
            $list_cat = [];
            $list_product_cat = Dmsp::where('status', 1)->get();
            foreach ($list_product_cat as $product_cat) {
                if ($this->check_approval_cat($product_cat)) {
                    $list_cat[] = $product_cat;
                }
            }
        } elseif ($request->input('status') == 'trash') {
            $list_cat = Dmsp::onlyTrashed()->get();
        } else {
            $list_data = Dmsp::where('status', 2)->get();
            $list_cat = $this->data_tree($list_data);
        }

        $count_confirm = $this->get_total_cat_confirm();
        $count_active = Dmsp::where('status', 2)->count();
        $count_trash = Dmsp::onlyTrashed()->count();

        $count = [
            $count_active,
            $count_confirm,
            $count_trash,
        ];
        $list_cat_active = Dmsp::where('status', 2)->get();
        return view('admin.products.list_cat', compact('list_cat', 'count', 'list_cat_active', 'list_cat_all'));
    }

    /**
     * * * Xử lý thêm danh mục sản phẩm
     */
    function add_cat(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Tên danh mục'
            ]
        );

        $data = [
            'name' => $request->input('name'),
            'user_create' => Auth::user()->name,
            'parent_id' => $request->input('cat'),
        ];

        Dmsp::create($data);

        return redirect('admin/product/cat/list')->with('status', 'Thêm danh mục thành công');
    }

    /**
     * * * Kiểm tra các danh mục cha có đang ở trạng thái hoạt động
     */
    function check_cat_parent($parent_id)
    {
        $flag = TRUE;
        $list_cat_all = Dmsp::query()->whereNull('deleted_at')->get();
        foreach ($list_cat_all as $cat_all) {
            if ($cat_all->id == $parent_id) {
                if ($cat_all->status != 2) {
                    $flag = FALSE;
                    return $flag;
                }
                $flag = $this->check_cat_parent($cat_all->parent_id);
            }
        }
        return $flag;
    }

    /** 
     * * * Xét duyệt danh mục sản phẩm
     * 
     * 1. TH1: Danh mục không có danh mục cha
     * 
     * 2. TH2: Danh mục có danh mục cha 
     * - Chỉ xét duyệt cho danh mục khi danh mục cha đang hoạt động
     */
    function approval($id)
    {
        $cat = Dmsp::find($id);
        if ($cat->parent_id == 0) {
            Dmsp::where('id', $id)->update(['status' => 2]);
            return redirect('admin/product/cat/list?status=confirm')->with('status', 'Xét duyệt thành công');
        }

        if ($this->check_cat_parent($cat->parent_id)) {
            Dmsp::where('id', $id)->update(['status' => 2]);
            return redirect('admin/product/cat/list?status=confirm')->with('status', 'Xét duyệt thành công');
        } else {
            return redirect('admin/product/cat/list?status=confirm')->with('status', 'Xét duyệt không thành công danh mục cha chưa hoạt động');
        }
    }

    /** 
     * * * Cập nhật danh mục sản phẩm
     * 
     * * * Xử lí hiển thị giao diện 
     * * * Lấy dữ liệu cần thiếu lên giao diện
     */
    function edit_cat(Request $request, $id)
    {
        $product_cat = Dmsp::find($id);

        $list_cat_all = Dmsp::query()->whereNull('deleted_at')->get();

        $list_data = Dmsp::where('status', 2)->get();
        $list_cat = $this->data_tree($list_data);
        $list_cat_active = Dmsp::where('status', 2)->get();

        $count_confirm = $this->get_total_cat_confirm();
        $count_active = Dmsp::where('status', 2)->count();
        $count_trash = Dmsp::onlyTrashed()->count();
        $count = [
            $count_active,
            $count_confirm,
            $count_trash,
        ];

        return view('admin.products.edit_cat', compact('list_cat', 'count', 'list_cat_active', 'product_cat'));
    }

    /** 
     * * * Cập nhật danh mục sản phẩm 
     * 
     * -> Xử lí request dữ liệu
     * -> Cập nhật lên CSDL
     */
    function update_cat(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Tên danh mục'
            ]
        );

        $data = [
            'name' => $request->input('name'),
            'parent_id' => $request->input('cat'),
        ];

        Dmsp::where('id', $id)->update($data);
        return redirect('admin/product/cat/list')->with('status', 'Cập nhật thành công');
    }

    /** 
     * * * Xử lí xóa dữ liệu danh mục sản phẩm theo ID 
     */
    function delete_cat(Request $request, $id)
    {
        $status = $request->input('status');
        Dmsp::destroy($id);
        return redirect("admin/product/cat/list/?status={$status}")->with('status', 'Đã xóa thành công');
    }

    /**
     * * * Khôi phục danh mục sản phẩm 
     * 
     */
    function restore_cat($id)
    {
        Dmsp::withTrashed()->where('id', $id)->restore();
        return redirect('admin/product/cat/list?status=trash')->with('status', 'Khôi phục dữ liệu thành công');
    }

    /** 
     * * * Xóa vĩnh viễn 1 bản ghi 
     */
    function forceDelete_cat($id)
    {
        Dmsp::withTrashed()->where('id', $id)->forceDelete();
        return redirect('admin/product/cat/list?status=trash')->with('status', 'Xóa danh mục thành công');
    }

    /** 
     * * * * PRODUCTS 
     * 
     * Thêm sản phẩm
     * - Xử lí gọi view
     * - Vào Database:
     * + Lấy dữ liệu danh mục sản phẩm
     */
    function add()
    {
        $list_tags = Tags::all();
        $list_cat = Dmsp::where('status', 2)->get();
        return view('admin.products.add', compact('list_cat', 'list_tags'));
    }

    /** 
     * -- Xử lí Submit dữ liệu sản phẩm lên Server
     * -
     * - Ràng buộc dữ liệu 
     * - Kiểm tra uploads file 
     * - Thêm dữ liệu vào Server 
     */
    function store(Request $request)
    {
        $request->validate(
            [
                'product_name' => ['required', 'min:2'],
                'price' => ['required'],
                'desc' => ['required'],
                'product_code' => ['required', 'unique:products,product_code'],
                'num_repository' => ['required'],
                'product_detail' => ['required'],
                'file' => ['required', 'image', 'max:20480'],
                'files.*' => ['image', 'max:20480'],
                'product_cat' => [Rule::notIn(['0'])],
            ],
            [
                // Định nghĩa nội dung riêng cho trường product_name khi không nhập dữ liệu
                'product_name.required' => 'Nhập tên sản phẩm',
                'required' => ':attribute không được để trống',
                'string' => ':attribute không phải dạng chuỗi',
                'min' => ':attribute phải có ít nhất :min ký tự',
                'product_code.unique' => 'Mã sản phẩm đã tồn tại vui lòng nhập khác',
                'product_cat.not_in' => 'Vui lòng chọn :attribute cụ thể',
                'image' => 'Tệp tin không phải hình ảnh',
                'files.*.image' => 'Danh sách tệp tin không phải là hình ảnh',
                'files.*.max' => 'Danh sách tệp tin quá kích thướt quy định',
            ],
            [
                'product_name' => 'Tên sản phẩm',
                'price' => 'Giá sản phẩm',
                'discount' => 'Giá sản phẩm',
                'desc' => 'Mô tả sản phẩm',
                'product_code' => 'Mã sản phẩm',
                'num_repository' => 'Số lượng',
                'product_detail' => 'Chi tiết sản phẩm',
                'product_cat' => 'danh mục sản phẩm',
                'file' => 'Tệp tin',
            ]
        );

        // Uploads File
        $file = $request->file('file');
        $folder = 'public/thumbnails/products/';
        $path_file = $folder . $file->getClientOriginalName();
        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file_exten = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        if (file_exists($path_file)) {
            $t = 1;
            while (file_exists($path_file)) {
                // Tạo 1 biến lưu tên file thay đổi 
                $new_file_name = $file_name . "-({$t})." . $file_exten;
                $new_path_file = $folder . $new_file_name;
                $path_file = $new_path_file;
                $t++;
            }

            $file->move($folder, $new_file_name);
        } else {
            $file->move($folder, $file->getClientOriginalName());
        }

        // Thêm dữ liệu vào Database 
        $data = [
            'name' => $request->input('product_name'),
            'product_code' => $request->input('product_code'),
            'desc' => $request->input('desc'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'number_stock' => $request->input('num_repository'),
            'thumbnail' => $path_file,
            'product_detail' => $request->input('product_detail'),
            'status' => $request->input('radio_status'),
            'user_create' => Auth::user()->name,
            'tags' => $request->input('tags'),
            'cat_id' => $request->input('product_cat'),
        ];

        $result = Product::create($data);

        // UPLOAD FILES
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $folder_files = 'public/img/products/';
                $files_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $files_exten = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $path_files = $folder_files . $file->getClientOriginalName();
                if (file_exists($path_files)) {
                    $t = 1;
                    while (file_exists($path_files)) {
                        $new_files_name = $files_name . "-({$t})." . $files_exten;
                        $new_path_files = $folder_files . $new_files_name;
                        $path_files = $new_path_files;
                        $t++;
                    }
                    $file->move($folder_files, $new_files_name);
                } else {
                    $file->move($folder_files, $file->getClientOriginalName());
                }
                Image::create([
                    'url' => $path_files,
                    'product_id' => $result->id,
                ]);
            }
        }
        // Đã xử lí xong thêm sản phẩm
        if ($request->input('radio_status') == 1) {
            return redirect()->route('admin.product.list', ['status' => 'approval'])->with('alert', 'Thêm mới thành công');
        } else {
            return redirect('admin/product/list')->with('alert', 'Thêm mới thành công');
        }
    }

    /**
     * * Hiển Thị Danh Sách Sản Phẩm 
     * 
     * - 4 Trạng thái:
     * . Hoạt động
     * . Chờ duyệt 
     * . Hết hàng 
     * . Vô hiệu hóa
     * - Sản phẩm nào thêm vào sao hiển thị trước
     * - Xử lí phân trang 
     */
    function list(Request $request)
    {
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
            $list_product =  Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')
                ->select('products.*', 'product_cats.name as cat_name')
                ->where('products.name', 'LIKE', "%{$keyword}%")
                ->orwhere('products.desc', 'LIKE', "%{$keyword}%")
                ->orderBy('products.created_at', 'desc')
                ->paginate(15);

            $total =  Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')
                ->select('products.*', 'product_cats.name as cat_name')
                ->where('products.name', 'LIKE', "%{$keyword}%")
                ->orwhere('products.desc', 'LIKE', "%{$keyword}%")
                ->count();
            $count = [$total];
            $list_act = [
                'delete' => 'Vô hiệu hóa',
                'approval' => 'Xét duyệt',
                'empty' => 'Hết hàng'
            ];
        } else {

            if ($request->input('status') == 'approval') {
                $list_product = Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')
                    ->select('products.*', 'product_cats.name as cat_name')
                    ->where('products.status', 1)
                    ->where('products.number_stock', '>', 0)
                    ->orderBy('products.created_at', 'desc')
                    ->paginate(15);

                $list_act = [
                    'delete' => 'Vô hiệu hóa',
                    'approval' => 'Xét duyệt'
                ];
            } elseif ($request->input('status') == 'empty') {
                $list_product = Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')
                    ->select('products.*', 'product_cats.name as cat_name')
                    ->where('products.number_stock', '=', 0)
                    ->paginate(15);

                $list_act = [
                    'delete' => 'Vô hiệu hóa'
                ];
            } elseif ($request->input('status') == 'trash') {
                $list_product = Product::onlyTrashed()->paginate(10);
                $list_cat = Dmsp::all();
                foreach ($list_product as $product) {
                    foreach ($list_cat as $cat) {
                        if ($product->cat_id == $cat->id) {
                            $product['cat_name'] = $cat->name;
                        }
                    }
                }
                $list_act = [
                    'restore' => 'Khôi phục',
                    'forceDelete' => 'Xóa vĩnh viễn',
                ];
            } else {
                $list_product = Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')
                    ->select('products.*', 'product_cats.name as cat_name')
                    ->where('products.status', 2)
                    ->where('products.number_stock', '>', 0)
                    ->orderBy('products.created_at', 'desc')
                    ->paginate(15);

                $list_act = [
                    'delete' => 'Vô hiệu hóa',
                    'empty' => 'Hết hàng',
                ];
            }


            $products_active = Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')->select('products.*', 'product_cats.name as cat_name')->where('products.status', 2)->where('products.number_stock', '>', 0)->orderBy('products.created_at', 'desc')->count();
            $products_empty =  Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')->select('products.*', 'product_cats.name as cat_name')->where('products.number_stock', '=', 0)->count();
            $products_approval = Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')->select('products.*', 'product_cats.name as cat_name')->where('products.status', 1)->where('products.number_stock', '>', 0)->orderBy('products.created_at', 'desc')->count();
            $products_trash =  Product::onlyTrashed()->count();
            $count = [
                $products_active,
                $products_approval,
                $products_empty,
                $products_trash,
            ];
        }

        return view('admin.products.list', compact('list_product', 'count', 'list_act'));
    }

    /**
     * * Xử lí các action
     * - Vô hiệu hóa
     * - Hết hàng
     * - Xét duyệt
     * - Khôi phục
     * - Xóa vĩnh viễn
     */
    function action(Request $request)
    {
        $list_status = [
            'approval',
            'empty',
            'trash',
            'active',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }


        $action = $request->input('action');
        if ($request->input('list_check')) {
            $list_check = $request->input('list_check');
            if ($action == 'delete') {
                foreach ($list_check as $key => $id) {
                    Product::destroy($id);
                }
                return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Đã xóa thành công');
            } elseif ($action == 'empty') {
                foreach ($list_check as $key => $id) {
                    Product::where('id', $id)->update([
                        'number_stock' => 0,
                    ]);
                }
                return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Cập nhật thành công');
            } elseif ($action == 'restore') {
                foreach ($list_check as $key => $id) {
                    Product::onlyTrashed()->where('id', $id)->restore();
                }
                return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Khôi phục thành công');
            } elseif ($action == 'forceDelete') {
                foreach ($list_check as $key => $id) {
                    Product::onlyTrashed()->where('id', $id)->forceDelete();
                }
                return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Đã xóa thành công');
            } elseif ($action == 'approval') {
                foreach ($list_check as $key => $id) {
                    Product::where('id', $id)->update(['status' => 2]);
                }
                return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Xét duyệt thành công');
            } else {
                return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Chọn tác vụ cụ thể');
            }
        } else {
            return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Chọn sản phẩm cụ thể');
        }
    }

    /**
     * * Cập nhật sản phẩm
     * - Gọi view
     */
    function edit(Request $request, $id)
    {
        // return "Cập nhật sản phẩm ".$id."--".$request->input('status')."--".$request->input('page');
        $list_cat = Dmsp::where('status', 2)->get();
        $product = Product::find($id);
        $list_tags = Tags::all();
        // echo "<pre>";
        // print_r($product);
        // echo "</pre>";
        return view('admin.products.edit', compact('list_cat', 'product', 'list_tags'));
    }

    /** 
     * * Cập nhật sản phẩm
     * - Xử lí cập nhật 
     */
    function update(Request $request, $id)
    {
        // Xử lí chuyển hướng trở về theo vị trí đã update
        $list_status = [
            'approval',
            'empty',
            'trash',
            'active',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }


        $request->validate(
            [
                'product_name' => ['required', 'min:2'],
                'price' => ['required'],
                'desc' => ['required'],
                'product_code' => ['required'],
                'num_repository' => ['required'],
                'product_detail' => ['required'],
                'file' => ['image', 'max:20480'],
                'files.*' => ['image', 'max:20480'],
                'product_cat' => [Rule::notIn(['0'])],
            ],
            [
                // Định nghĩa nội dung riêng cho trường product_name khi không nhập dữ liệu
                'product_name.required' => 'Nhập tên sản phẩm',
                'required' => ':attribute không được để trống',
                'string' => ':attribute không phải dạng chuỗi',
                'min' => ':attribute phải có ít nhất :min ký tự',
                'product_code.unique' => 'Mã sản phẩm đã tồn tại vui lòng nhập khác',
                'product_cat.not_in' => 'Vui lòng chọn :attribute cụ thể',
                'image' => 'Tệp tin không phải hình ảnh',
                'files.*.image' => 'Danh sách tệp tin không phải là hình ảnh',
                'files.*.max' => 'Danh sách tệp tin quá kích thướt quy định',
            ],
            [
                'product_name' => 'Tên sản phẩm',
                'price' => 'Giá sản phẩm',
                'discount' => 'Giá sản phẩm',
                'desc' => 'Mô tả sản phẩm',
                'product_code' => 'Mã sản phẩm',
                'num_repository' => 'Số lượng',
                'product_detail' => 'Chi tiết sản phẩm',
                'product_cat' => 'danh mục sản phẩm',
                'file' => 'Tệp tin',
            ]
        );

        // $list_code = Product::whereNot('id', $id)->pluck('product_code')->toArray();
        $list_code = Product::where('id', '!=', $id)->pluck('product_code')->toArray();

        if (in_array($request->input('product_code'), $list_code)) {
            return redirect()->route('admin.product.edit', ['id' => $id, 'status' => $request->input('status'), 'page' => $request->input('page')])->with('error_code', 'Mã sản phẩm đã tồn tại vui lòng chọn khác');
        } else {
            // Uploads File
            if ($request->file('file')) {
                $file = $request->file('file');
                $folder = 'public/thumbnails/products/';
                $path_file = $folder . $file->getClientOriginalName();
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $file_exten = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                if (file_exists($path_file)) {
                    $t = 1;
                    while (file_exists($path_file)) {
                        // Tạo 1 biến lưu tên file thay đổi 
                        $new_file_name = $file_name . "-({$t})." . $file_exten;
                        $new_path_file = $folder . $new_file_name;
                        $path_file = $new_path_file;
                        $t++;
                    }

                    $file->move($folder, $new_file_name);
                } else {
                    $file->move($folder, $file->getClientOriginalName());
                }

                $data = [
                    'name' => $request->input('product_name'),
                    'product_code' => $request->input('product_code'),
                    'desc' => $request->input('desc'),
                    'price' => $request->input('price'),
                    'discount' => $request->input('discount'),
                    'number_stock' => $request->input('num_repository'),
                    'thumbnail' => $path_file,
                    'product_detail' => $request->input('product_detail'),
                    'status' => $request->input('radio_status'),
                    'tags' => $request->input('tags'),
                    'user_create' => Auth::user()->name,
                    'cat_id' => $request->input('product_cat'),
                ];
            } else {
                $data = [
                    'name' => $request->input('product_name'),
                    'product_code' => $request->input('product_code'),
                    'desc' => $request->input('desc'),
                    'price' => $request->input('price'),
                    'discount' => $request->input('discount'),
                    'number_stock' => $request->input('num_repository'),
                    'product_detail' => $request->input('product_detail'),
                    'status' => $request->input('radio_status'),
                    'tags' => $request->input('tags'),
                    'user_create' => Auth::user()->name,
                    'cat_id' => $request->input('product_cat'),
                ];
            }

            // Thêm dữ liệu vào Database 
            // Xử lí kiểm tra mã code sản phẩm 

            $product = Product::where('id', $id)->first();
            $product->update($data);

            // UPLOAD FILES
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach ($files as $file) {
                    $folder_files = 'public/img/products/';
                    $files_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $files_exten = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                    $path_files = $folder_files . $file->getClientOriginalName();
                    if (file_exists($path_files)) {
                        $t = 1;
                        while (file_exists($path_files)) {
                            $new_files_name = $files_name . "-({$t})." . $files_exten;
                            $new_path_files = $folder_files . $new_files_name;
                            $path_files = $new_path_files;
                            $t++;
                        }
                        $file->move($folder_files, $new_files_name);
                    } else {
                        $file->move($folder_files, $file->getClientOriginalName());
                    }
                    Image::create([
                        'url' => $path_files,
                        'product_id' => $product->id,
                    ]);
                }
            }

            return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Cập nhật thành công');
        }
    }

    /**
     * * * Xóa sản phẩm tạm thời 
     */
    function delete(Request $request, $id)
    {
        // Xử lí chuyển hướng trở về theo vị trí đã delete
        $list_status = [
            'approval',
            'empty',
            'trash',
            'active',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }
        Product::destroy($id);
        return redirect()->route('admin.product.list', $data_redirect)->with('alert', 'Đã xóa thành công');
    }

    /** 
     * - Khôi phục dữ liệu đã xóa 
     * 
     */
    function restore(Request $request, $id)
    {
        Product::onlyTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.product.list', ['status' => 'trash', 'page' => $request->input('page')])->with('alert', 'Khôi phục thành công');
    }

    /**
     * - Xóa vĩnh viễn sản phẩm
     */
    function forceDelete(Request $request, $id)
    {
        Product::withTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('admin.product.list', ['status' => 'trash', 'page' => $request->input('page')])->with('alert', 'Đã xóa thành công');
    }

    /**
     * - Chi tiết sản phẩm
     */
    function product_detail(Request $request, $id)
    {
        $list_status = [
            'approval',
            'empty',
            'trash',
            'active',
        ];

        if (in_array($request->input('status'), $list_status)) {
            $data_redirect = [
                'status' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        } else {
            $data_redirect = [
                'keyword' => $request->input('status'),
                'page' => $request->input('page'),
            ];
        }

        $product = Product::join('product_cats', 'products.cat_id', '=', 'product_cats.id')
            ->select('products.*', 'product_cats.name as cat_name')
            ->withTrashed()
            ->where('products.id', $id)
            ->first();
        return view('admin.products.product_detail', compact('product', 'data_redirect'));
    }

    /**
     * Xử lí chức năng phân loại sản phẩm theo từng thẻ
     * - Hiển thị danh sách các thẻ
     * - Thêm các loại thẻ
     */
    // function list_tags()
    // {
    //     $list_tags = Tags::all();
    //     return view('admin.products.list_tags', compact('list_tags'));
    // }

    // function add_tags(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'tags' => ['required'],
    //         ],
    //         [
    //             'tags.required' => 'Vui lòng nhập tên thẻ',
    //         ]
    //     );

    //     $data = [
    //         'tags_name' => $request->input('tags'),
    //         'user_create' => Auth::user()->name,
    //     ];

    //     Tags::create($data);
    //     return redirect()->route('admin.product.tags')->with('status', 'Thêm mới thành công');
    // }

    // function edit_tags($id){
    //     $list_tags = Tags::all();
    //     $tags = Tags::find($id);
    //     return view('admin.products.edit_tags', compact('list_tags', 'tags'));
    // }

    // function update_tags(Request $request, $id){
    //      $request->validate(
    //         [
    //             'tags' => 'required',
    //         ],
    //         [
    //             'tags.required' => 'Không được để trống',
    //         ]
    //      );

    //      $data = [
    //         'tags_name' => $request->input('tags'),
    //      ];

    //      Tags::where('id', $id)->update($data);
    //      return redirect()->route('admin.product.tags')->with('status', 'Cập nhật thành công');
    // }

    // function delete_tags($id){
    //     Tags::destroy($id);
    //     return redirect()->route('admin.product.tags')->with('status', 'Đã xóa thành công');
    // }
}
