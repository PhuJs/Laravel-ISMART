<!-- ==== SIDEBAR LEFT ==== -->
<section id="sidebar_left">
    <!-- 1 -->
    <div class="section">
        <div class="section_head">
            <h3 class="section_title">Danh mục sản phẩm</h3>
        </div>
        <div class="section_content">
            {{-- --- Menu sidebar -- --}}
            {!! render_menu($list_product_cat) !!}
        </div>
    </div>
    <!-- 2 -->
    <div class="section mt-25">
        <div class="section_head">
            <h3 class="section_title">Sản phẩm bán chạy</h3>
        </div>
        <div class="section_content">
            @if ($list_product_hot)
                <ul id="list_product_hot">
                    @foreach ($list_product_hot as $item)
                        <li>
                            <a href="chi-tiet-san-pham/{{ Str::slug($item->name) }}/{{ $item->id }}"
                                class="product_thumb">
                                <img src="{{ $item->thumbnail }}" alt="" class="img-fluid img-thumbnail">
                            </a>
                            <div>
                                <a href="chi-tiet-san-pham/{{ Str::slug($item->name) }}/{{ $item->id }}"
                                    class="title">{{ $item->name }}</a>
                                <p><span class="price">{{ currency_format($item->price) }}</span> <span
                                        class="discount">{{ currency_format($item->discount) }}</span></p>
                                <a href="{{ route('add.cart', ['name' => Str::slug($item->name), 'id' => $item->id]) }}"
                                    class="buy_now">Mua ngay</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <span class="">Không có sản phẩm</span>
            @endif
        </div>
    </div>
    <div class="sidebar_banner mt-25">
        <a href="https://unitop.vn/" target="__blank" >
            <img src="public/images/banner-2.png" alt="" class="img-fluid">
        </a>
    </div>
</section>
