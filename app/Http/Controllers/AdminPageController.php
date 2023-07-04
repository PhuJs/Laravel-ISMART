<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller
{
    function __construct(){
        $this->middleware(function($request, $next){
            session(['module_active' => 'page']);
            return $next($request);
        });
    }
    /**
     * Thêm trang 
     * 
     */
    function add()
    {
        return view('admin.pages.add');
    }

    /**
     * Submit dữ liệu 
     * 
     */
    function store(Request $request)
    {
        $request->validate(
            [
                'page_title' => ['required'],
                'page_url' => ['required'],
                'page_content' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'page_title' => 'Tiêu đề trang',
                'page_url' => 'Đường Link',
                'page_content' => 'Nội dung',
            ]
        );

        $data_page = [
            'title' => $request->input('page_title'),
            'url' => $request->input('page_url'),
            'content' => $request->input('page_content'),
            'status' => $request->input('page_status'),
            'user_create' => Auth::user()->name,
        ];

        Page::create($data_page);
        return redirect()->route('admin.page.show')->with('alert', 'Thêm trang thành công');
    }

    /**
     * Thống kê số liệu 
     * 
     */
    function total_page()
    {
        $total_page = Page::count();
        return $total_page;
    }

    function total_page_trash()
    {
        $total_page_trash = Page::onlyTrashed()->count();
        return $total_page_trash;
    }

    /**
     * Hiển thị danh sách trang 
     * 
     */
    function show(Request $request)
    {
        $total = [];
        $total['total_page'] = $this->total_page();
        $total['total_page_trash'] = $this->total_page_trash();
        if ($request->input('status') == 'trash') {
            $list_page = Page::onlyTrashed()->get();
        } else {
            $list_page = Page::all();
        }
        return view('admin.pages.show', compact('list_page', 'total'));
    }

    /**
     * Hiển thị nội dung trang 
     * - Tham số ID của trang cần hiển thị
     */
    function content_page(Request $request, $id)
    {
        $status = $request->input('status');
        $page = Page::withTrashed()->where('id', $id)->first();
        return view('admin.pages.content', compact('page', 'status'));
    }

    /**
     * Cập nhật trang 
     * - Gọi giao diện
     */
    function edit($id){
        $page = Page::find($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Cập nhật trang 
     * - Submit dữ liệu 
     */
    function update(Request $request, $id){
        $request->validate(
            [
                'page_title' => ['required'],
                'page_url' => ['required'],
                'page_content' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'page_title' => 'Tiêu đề trang',
                'page_url' => 'Đường Link',
                'page_content' => 'Nội dung',
            ]
        );

        
        $data_page = [
            'title' => $request->input('page_title'),
            'url' => $request->input('page_url'),
            'content' => $request->input('page_content'),
            'status' => $request->input('page_status'),
        ];

        Page::where('id', $id)->update($data_page);
        return redirect()->route('admin.page.show')->with('alert', 'Cập nhật thành công');
    }

    /**
     * Vô hiệu hóa trang 
     * 
     */
    function delete($id){
        Page::destroy($id);
        return redirect()->route('admin.page.show')->with('alert', 'Đã vô hiệu hóa trang');
    }

    /**
     * Khôi phục trang
     * 
     */
    function restore($id){
        Page::onlyTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.page.show', ['status' => 'trash'])->with('alert', 'Đã khôi phục trang hoạt động trở lại');
    }

    /**
     * Xóa vĩnh viễn trang 
     * 
     */
    function forceDelete($id){
        Page::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('admin.page.show', ['status' => 'trash'])->with('alert', 'Đã xóa vĩnh viễn trang ra khỏi hệ thống');
    }
}
