<section id="sidebar_left">
    <!-- 1 -->
    <div class="section">
        <div class="section_head">
            <h3 class="section_title">Danh mục sản phẩm</h3>
        </div>
        <div class="section_content">
            {!! render_menu($list_product_cat) !!}
        </div>
    </div>
    <!-- 2 -->
    <div class="section mt-25">
        <div class="section_head">
            <h3 class="section_title">Bộ lọc</h3>
        </div>
        <div class="section_content">
            <div class="filter_product px-3">
                <form action="" id="form_filter_product" method="GET">
                    <h6 class="py-2">Giá</h6>
                    <div class="form-check mb-3">
                        <input type="radio" name="price" value="1" class="form-check-input" id="checkInput-1" onchange="submitForm()">
                        <label for="checkInput-1" class="form-check-label">
                            Dưới 3.000.000đ
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="radio" name="price" value="2" class="form-check-input" id="checkInput-2" onchange="submitForm()">
                        <label for="checkInput-2" class="form-check-label">
                            3.000.000đ - 8.000.000đ
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="radio" name="price" value="3" class="form-check-input" id="checkInput-3" onchange="submitForm()">
                        <label for="checkInput-3" class="form-check-label">
                            8.000.000đ - 15.000.000đ
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="radio" name="price" value="4" class="form-check-input" id="checkInput-4" onchange="submitForm()">
                        <label for="checkInput-4" name="" class="form-check-label">
                            Trên 15.000.000đ
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="sidebar_banner mt-25">
        <a href="https://unitop.vn/" target="__blank">
            <img src="./public/images/banner-2.png" alt="" class="img-fluid">
        </a>
    </div>
</section>