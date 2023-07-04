<?php

use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->middleware('auth');

/**
 * Home
 * 
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/** 
 * Products
 */
Route::get('san-pham/{name}/{id}', [ProductController::class, 'list_product'])->name('product.list');

Route::get('chi-tiet-san-pham/{name}/{id}', [ProductController::class, 'detail'])->name('product.detail');

Auth::routes();

/**
 * Cart
 * 
 */
Route::get('them-vao-gio-hang/{name}/{id}', [CartController::class, 'add_cart'])->name('add.cart');

Route::get('cart/add/ajax', [CartController::class, 'add_cart_ajax']);

Route::get('gio-hang', [CartController::class, 'show_cart'])->name('cart.show');

Route::get('cart/delete/{rowId}', [CartController::class, 'delete_cart'])->name('cart.delete');

Route::get('cart/update', [CartController::class, 'update_cart'])->name('cart.update');

Route::get('cart/delete-all', [CartController::class, 'delete_all'])->name('cart.delete.all');

Route::post('cart/adds/{id}', [CartController::class, 'add_carts'])->name('add.carts');

Route::get('gio-hang/thanh-toan', [CartController::class, 'payment'])->name('cart.payment');

Route::post('cart/payment/order', [CartController::class, 'payment_cart'])->name('cart.payment.order');

Route::get('cart/payment/district', [CartController::class, 'get_district']);

Route::get('cart/payment/wards', [CartController::class, 'get_wards']);

/**
 * POST 
 * 
 */
Route::get('tin-cong-nghe', [PostController::class, 'show'])->name('post.show');

Route::get('tin-cong-nghe/{slug}', [PostController::class, 'post_detail'])->name('post.detail');

Route::get('{slug}.html', [PageController::class, 'show'])->name('page.show');



/**
 * * * * DASHBOARD 
 */

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show']);

    Route::get('admin', [DashboardController::class, 'show']);

    Route::get('dashboard/order/confirm/{id}', [DashboardController::class, 'confirm'])->name('dashboard.order.confirm');

    /**
     * * * * * USER 
     */
    Route::get('admin/user/list', [AdminUserController::class, 'list'])->name('admin.user.list');

    Route::get('admin/user/add', [AdminUserController::class, 'add']);

    Route::post('admin/user/store', [AdminUserController::class, 'store']);

    Route::get('admin/user/delete/{id}', [AdminUserController::class, 'delete'])->name('admin.user.delete');

    // Route::match(['get', 'post'], 'admin/user/action', [AdminUserController::class, 'action']);
    Route::post('admin/user/action', [AdminUserController::class, 'action']);

    Route::get('admin/user/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.user.edit');

    Route::post('admin/user/update/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');

    Route::get('admin/user/restore/{id}', [AdminUserController::class, 'restore'])->name('admin.user.restore');

    Route::get('admin/user/forceDelete/{id}', [AdminUserController::class, 'forceDelete'])->name(('admin.user.forceDelete'));

    /**
     * * * * * PRODUCT
     * Product cat 
     */
    Route::get('admin/product/cat/list', [AdminProductController::class, 'show_cat'])->name('admin.product.cat.list');

    Route::match(['get', 'post'], 'admin/product/cat/add', [AdminProductController::class, 'add_cat'])->name('admin.product.cat.add');

    Route::get('admin/product/cat/edit/{id}', [AdminProductController::class, 'edit_cat'])->name('admin.product.cat.edit');

    Route::match(['get', 'post'], 'admin/product/cat/update/{id}', [AdminProductController::class, 'update_cat'])->name('admin.product.cat.update');

    Route::get('admin/product/cat/delete/{id}', [AdminProductController::class, 'delete_cat'])->name('admin.product.cat.delete');

    Route::get('admin/product/cat/approval/{id}', [AdminProductController::class, 'approval'])->name('admin.product.cat.approval');

    Route::get('admin/product/cat/restore/{id}', [AdminProductController::class, 'restore_cat'])->name('admin.product.cat.restore');

    Route::get('admin/product/cat/forceDelete/{id}', [AdminProductController::class, 'forceDelete_cat'])->name('admin.product.cat.forceDelete');
    /** 
     * * * Product 
     */
    Route::get('admin/product/add', [AdminProductController::class, 'add'])->name('admin.product.add');

    Route::group(['prefix' => 'laravel-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    // Route::post('admin/product/store', [AdminProductController::class, 'store'])->name('admin.product.store');
    Route::match(['get', 'post'], 'admin/product/store', [AdminProductController::class, 'store'])->name('admin.product.store');

    Route::get('admin/product/list', [AdminProductController::class, 'list'])->name('admin.product.list');

    Route::match(['get', 'post'], 'admin/product/action', [AdminProductController::class, 'action'])->name('admin.product.action');

    Route::get('admin/product/edit/{id}', [AdminProductController::class, 'edit'])->name('admin.product.edit');

    Route::post('admin/product/update/{id}', [AdminProductController::class, 'update'])->name('admin.product.update');

    Route::get('admin/product/delete/{id}', [AdminProductController::class, 'delete'])->name('admin.product.delete');

    Route::get('admin/product/restore/{id}', [AdminProductController::class, 'restore'])->name('admin.product.restore');

    Route::get('admin/product/forceDelete/{id}', [AdminProductController::class, 'forceDelete'])->name('admin.product.forceDelete');

    Route::get('admin/product/product_detail/{id}', [AdminProductController::class, 'product_detail'])->name('admin.product.product_detail');

    Route::get('admin/product/tags', [AdminProductController::class, 'list_tags'])->name('admin.product.tags');

    Route::post('admin/product/tags/add', [AdminProductController::class, 'add_tags'])->name('admin.product.tags.add');

    Route::get('admin/product/tags/edit/{id}', [AdminProductController::class, 'edit_tags'])->name('admin.product.tags.edit');

    Route::post('admin/product/tags/update/{id}', [AdminProductController::class, 'update_tags'])->name('admin.product.tags.update');

    Route::get('admin/product/tags/delete/{id}', [AdminProductController::class, 'delete_tags'])->name('admin.product.tags.delete');
    /**
     * -------POST----------------
     * **********
     * POST CAT
     */
    Route::get('admin/post/cat/list', [AdminPostController::class, 'list_cat'])->name('admin.post.cat.list');

    Route::post('admin/post/cat/add', [AdminPostController::class, 'add_cat'])->name('admin.post.cat.add');

    Route::get('admin/post/cat/delete/{id}', [AdminPostController::class, 'delete_post_cat'])->name('admin.post.cat.delete');

    Route::get('admin/post/cat/approval/{id}', [AdminPostController::class, 'approval_post_cat'])->name('admin.post.cat.approval');

    Route::get('admin/post/cat/edit/{id}', [AdminPostController::class, 'edit_post_cat'])->name('admin.post.cat.edit');

    Route::post('admin/post/cat/update/{id}', [AdminPostController::class, 'update_post_cat'])->name('admin.post.cat.update');

    Route::get('admin/post/cat/restore/{id}', [AdminPostController::class, 'restore_post_cat'])->name('admin.post.cat.restore');

    Route::get('admin/post/cat/forceDelete/{id}', [AdminPostController::class, 'forceDelete_post_cat'])->name('admin.post.cat.forceDelete');

    /** 
     * POST 
     */
    Route::get('admin/post/add', [AdminPostController::class, 'add_post'])->name('admin.post.add');

    Route::post('admin/post/store', [AdminPostController::class, 'store'])->name('admin.post.store');

    Route::get('admin/post/list', [AdminPostController::class, 'list_post'])->name('admin.post.list');

    Route::post('admin/post/action', [AdminPostController::class, 'action'])->name('admin.post.action');

    Route::get('admin/post/detail/{id}', [AdminPostController::class, 'post_detail'])->name('admin.post.detail');

    Route::get('admin/post/delete/{id}', [AdminPostController::class, 'delete_post'])->name('admin.post.delete');

    Route::get('admin/post/edit/{id}', [AdminPostController::class, 'edit'])->name('admin.post.edit');

    Route::post('admin/post/update/{id}', [AdminPostController::class, 'update'])->name('admin.post.update');

    Route::get('admin/post/restore/{id}', [AdminPostController::class, 'restore'])->name('admin.post.restore');

    Route::get('admin/post/forceDelete/{id}', [AdminPostController::class, 'forceDelete'])->name('admin.post.forceDelete');

    /**
     * Orders
     * 
     */
    Route::get('admin/order/show', [OrdersController::class, 'show'])->name('admin.order.show');

    Route::post('admin/order/action', [OrdersController::class, 'action_order'])->name('admin.order.action');

    Route::get('admin/order/detail/{id}', [OrdersController::class, 'order_detail'])->name('admin.order.detail');

    Route::get('admin/order/edit/{id}', [OrdersController::class, 'edit'])->name('admin.order.edit');

    Route::post('admin/order/update/{id}', [OrdersController::class, 'update'])->name('admin.order.update');

    Route::post('admin/order/update_status/{id}', [OrdersController::class, 'update_status'])->name('admin.order.update_status');

    Route::get('admin/order/delete/{id}', [OrdersController::class, 'delete'])->name('admin.order.delete');

    Route::get('admin/order/restore/{id}', [OrdersController::class, 'restore'])->name('admin.order.restore');

    Route::get('admin/order/forceDelete/{id}', [OrdersController::class, 'force_delete'])->name('admin.order.forceDelete');

    /**
     * Sliders 
     * 
     */
    Route::get('admin/slider/add', [AdminSliderController::class, 'add'])->name('admin.slider.add');

    Route::post('admin/slider/store', [AdminSliderController::class, 'store'])->name('admin.slider.store');

    Route::get('admin/slider/show', [AdminSliderController::class, 'show'])->name('admin.slider.show');

    Route::get('admin/slider/edit/{id}', [AdminSliderController::class, 'edit'])->name('admin.slider.edit');

    Route::post('admin/slider/update/{id}', [AdminSliderController::class, 'update'])->name('admin.slider.update');

    Route::get('admin/slider/destroy/{id}', [AdminSliderController::class, 'delete'])->name('admin.slider.delete');

    Route::get('admin/slider/restore/{id}', [AdminSliderController::class, 'restore'])->name('admin.slider.restore');

    Route::get('admin/slider/forceDelete/{id}', [AdminSliderController::class, 'forceDelete'])->name('admin.slider.forceDelete');

    /**
     * PAGE 
     * 
     */
    Route::get('admin/page/add', [AdminPageController::class, 'add'])->name('admin.page.add');

    Route::post('admin/page/post', [AdminPageController::class, 'store'])->name('admin.page.store');

    Route::get('admin/page/show', [AdminPageController::class, 'show'])->name('admin.page.show');

    Route::get('admin/page/content/{id}', [AdminPageController::class, 'content_page'])->name('admin.page.content');

    Route::get('admin/page/edit/{id}', [AdminPageController::class, 'edit'])->name('admin.page.edit');

    Route::post('admin/page/update/{id}', [AdminPageController::class, 'update'])->name('admin.page.update');

    Route::get('admin/page/delete/{id}', [AdminPageController::class, 'delete'])->name('admin.page.delete');

    Route::get('admin/page/restore/{id}', [AdminPageController::class, 'restore'])->name('admin.page.restore');

    Route::get('admin/page/forceDelete/{id}', [AdminPageController::class, 'forceDelete'])->name('admin.page.forceDelete');
});
