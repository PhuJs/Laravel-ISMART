<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSliderController extends Controller
{
    function __construct(){
        $this->middleware(function($request, $next){
            session(['module_active' => 'sliders']);
            return $next($request);
        });
    }

    /**
     * Thêm Slider 
     * 
     */
    function add()
    {
        return view('admin.sliders.add');
    }

    /**
     * Thêm Slider 
     * - Cập nhật dữ liệu 
     * 
     */
    function store(Request $request)
    {
        $request->validate(
            [
                'slider_title' => ['required'],
                'thumb' => ['required', 'image', 'max:20480']
            ],
            [
                'slider_title.required' => 'Vui lòng nhập tiêu đề Slider',
                'thumb.required' => 'Vui lòng chọn ảnh Slider',
                'thumb.image' => 'Tệp không đúng định dạng hình ảnh',
                'thumb.max' => 'Tệp quá kích thướt quy định',
            ]
        );

        $thumb = $request->file('thumb');
        $folder_file = "public/sliders/";
        $path_file = $folder_file . $thumb->getClientOriginalName();

        $file_name = pathinfo($thumb->getClientOriginalName(), PATHINFO_FILENAME);
        $file_extension = $thumb->getClientOriginalExtension();
        if (file_exists($path_file)) {
            $k = 1;
            while (file_exists($path_file)) {
                $new_file_name = $file_name . "-{$k}." . $file_extension;
                $new_path_file = $folder_file . $new_file_name;
                $path_file = $new_path_file;
                $k++;
            }
            $thumb->move($folder_file, $new_file_name);
        } else {
            $thumb->move($folder_file, $thumb->getClientOriginalName());
        }

        $data_slider = [
            'thumb' => $path_file,
            'title' => $request->input('slider_title'),
            'desc' => $request->input('slider_desc'),
            'url' => $request->input('link'),
            'status' => $request->input('slider_status'),
            'user_create' => Auth::user()->name,
        ];

        Slider::create($data_slider);
        return redirect()->route('admin.slider.show')->with('alert', 'Thêm mới slider thành công');
    }

    /**
     * Thống kê Slider ở mỗi trạng thái
     * 
     */
    function total_slider()
    {
        $total_slider = Slider::count();
        return $total_slider;
    }

    function total_slider_trash()
    {
        $total_slider_trash = Slider::onlyTrashed()->count();
        return $total_slider_trash;
    }

    /**
     * Danh sách sliders
     * 
     */
    function show(Request $request)
    {
        if ($request->input('status') == 'trash') {
            $list_slider = Slider::onlyTrashed()->paginate(15);
        } else {
            $list_slider = Slider::paginate(15);
        }

        $total = [];
        $total['total_slider'] = $this->total_slider();
        $total['total_slider_trash'] = $this->total_slider_trash();
        return view('admin.sliders.show', compact('list_slider', 'total'));
    }

    /**
     * Gọi view cập nhật Slider
     * 
     */
    function edit($id)
    {
        $slider = Slider::find($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Xử lí cập nhật dữ liệu Slider 
     * 
     */
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'slider_title' => ['required'],
                'thumb' => ['image', 'max:20480'],
            ],
            [
                'slider_title.required' => 'Vui lòng nhập tiêu đề Slider',
                'thumb.image' => 'Tệp không đúng định dạng hình ảnh',
                'thumb.max' => 'Tệp quá kích thướt quy định',
            ]
        );

        if ($request->hasFile('thumb')) {
            $thumb = $request->file('thumb');
            $folder_file = "public/sliders/";
            $path_file = $folder_file . $thumb->getClientOriginalName();
            $file_name = pathinfo($thumb->getClientOriginalName(), PATHINFO_FILENAME);
            $file_extension = $thumb->getClientOriginalExtension();
            if (file_exists($path_file)) {
                $k = 1;
                while (file_exists($path_file)) {
                    $new_file_name = $file_name . "-{$k}." . $file_extension;
                    $new_path_file = $folder_file . $new_file_name;
                    $path_file = $new_path_file;
                    $k++;
                }
                $thumb->move($folder_file, $new_file_name);
            } else {
                $thumb->move($folder_file, $thumb->getClientOriginalName());
            }

            $data_slider = [
                'thumb' => $path_file,
                'title' => $request->input('slider_title'),
                'desc' => $request->input('slider_desc'),
                'url' => $request->input('link'),
                'status' => $request->input('slider_status'),
            ];
        } else {
            $data_slider = [
                'title' => $request->input('slider_title'),
                'desc' => $request->input('slider_desc'),
                'url' => $request->input('link'),
                'status' => $request->input('slider_status'),
            ];
        }

        Slider::where('id', $id)->update($data_slider);
        return redirect()->route('admin.slider.show')->with('alert', 'Cập nhật slider thành công');
    }

    /**
     * Vô hiệu hóa Slider
     * 
     */
    function delete($id){
        Slider::destroy($id);
        return redirect()->route('admin.slider.show')->with('alert', 'Slider đã bị xóa tạm thời');
    }

    /**
     * Khôi phục Slider 
     * 
     */
    function restore($id){
        Slider::onlyTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.slider.show', ['status' => 'trash'])->with('alert', 'Slider đã được khôi phục thành công');
    }

    /**
     * Xóa vĩnh viễn Slider 
     * 
     */
    function forceDelete($id){
        Slider::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('admin.slider.show', ['status' => 'trash'])->with('alert', 'Slider đã được xóa ra khỏi hệ thống');
    }
}
