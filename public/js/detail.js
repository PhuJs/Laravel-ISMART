$(document).ready(function () {
    $('.menu-dropdown').on('click', function () {
        $(this).next().stop().slideToggle();
        $(this).children('i').toggleClass('rote');
    });

    // $('#sidebar-menu > li').on('click', function(event) {
    //     $(this).children('ul#sub-menu').stop().slideToggle();
    //     $(this).children('span > li').toggleClass('rote');
    //     event.preventDefault();
    // });

    $("input[name='checkall']").click(function () {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });

    $("input[name='checkPostsAll']").click(function () {
        var checked = $(this).is(':checked');
        $(this).closest('.card').find('.card-body input:checkbox').prop('checked', checked);
    });

    $("input[name='checkProductsAll']").click(function () {
        var checked = $(this).is(':checked');
        $(this).closest('.card').find('.card-body input:checkbox').prop('checked', checked);
    });


    // function formatNumber(value) {
    //     return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    // }

    // $("input[type='text']").on('input', function () {
    //     let value = $(this).val();
    //     value = value.replace(/,/g, '');
    //     let formattedValue = formatNumber(value);
    //     $(this).val(formattedValue);
    // });

    // =============================


    $('.toggle_menu_responsive').on('click', function () {
        $('#modal').stop().toggle(500);
    });

    $('#wp_menu_responsive').children('.out_modal').on('click', function () {
        $('#modal').stop().hide(500);
    });

    $(window).resize(function () {
        $('#modal').stop().hide(500);
    });

    $('#menu_responsive li').children('.sub_menu_responsive').prev('div').children('span').addClass('has-child');

    $('#menu_responsive li>div>span').on('click', function () {
        $(this).parent('div').next('.sub_menu_responsive').stop().slideToggle();
        if ($(this).hasClass('has-child')) {
            $(this).removeClass('has-child');
            $(this).addClass('rote');
        } else {
            $(this).removeClass('rote');
            $(this).addClass('has-child');
        }
    });

    $('#sidebar_menu li').children('.sub_sidebar_menu').prev('a').addClass('tick');

    $('#sidebar_menu li').hover(
        function () {
            $(this).children('.sub_sidebar_menu').show();
        },

        function () {
            $(this).children('.sub_sidebar_menu').hide();
        }
    );

    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 12,
        responsiveClass: true,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2,
                nav: true
            },
            600: {
                items: 3,
                nav: true,
            },
            1000: {
                items: 4,
                nav: true,
                loop: true,
            }
        },
        autoplay: true,
        autoplayTimeout: 3000,
        animateOut: 'fadeOut', // Hiệu ứng chuyển đi
        animateIn: 'fadeIn' // Hiệu ứng chuyển đến
    });

    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('#back_top').fadeIn();
        } else {
            $('#back_top').fadeOut();
        }
    });

    $('#back_top').click(function () {
        $('html, body').animate({
            scrollTop: 0,
        }, 100);
    });

    $('.list-thumb .thumb-item').on('click', function () {
        let picture_src = $(this).find('img').attr('src');
        $('.show-picture img').attr('src', picture_src);
        $('.list-thumb .thumb-item').removeClass('active_images');
        $(this).addClass('active_images');
    });

    $('.show-picture .next-btn').on('click', function () {
        if ($('.list-thumb .thumb-item:last-child').hasClass('active_images')) {
            $('.list-thumb .thumb-item:first-child').click();
        } else {
            $('.list-thumb .thumb-item.active_images').next().click();
        }
    });

    $('.show-picture .prev-btn').on('click', function () {
        if ($('.list-thumb .thumb-item:first-child').hasClass('active_images')) {
            $('.list-thumb .thumb-item:last-child').click();
        } else {
            $('.list-thumb .thumb-item.active_images').prev().click();
        }
    });


    $('.list-thumb .thumb-item:first-child').click();


    $('.num-order').on('change', function () {
        let id = $(this).attr('data-id');
        let rowId = $(this).attr('data-rowId');
        let num_order = $(this).val();
        $.ajax({
            url: 'cart/update',
            method: 'GET',
            data: {
                rowId: rowId,
                num_order: num_order,
            },
            dataType: 'json',
            success: function (data) {
                $('.total_product_cart_' + id).text(data.totalPrice);
                $('.result_total_cart').text(data.totalCart);
                $('.total_price .p_last_child').text(data.totalCart);
                $('#dropdown>p.alert>span.text-danger').text(data.countCart);
                $('#cart>span.total_cart').text(data.total);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            },
        });
    });

    $('.btn_minus').click(function () {
        let num_order = $('.input_num_order').val();
        if (num_order > 1) {
            num_order = num_order - 1;
            $('.input_num_order').val(num_order);
        }
    });

    $('.btn_plus').click(function () {
        let num_order = $('.input_num_order').val();
        num_order++;
        $('.input_num_order').val(num_order);
    });

    $('.btn_add_cart').on('click', function () {
        let productId = $(this).attr('data-id');
        $.ajax({
            url: 'cart/add/ajax',
            method: 'GET',
            data: {
                productId: productId,
            },
            dataType: 'text',
            success: function (data) {
                if (data > 0) {
                    $('#popupOverlay').css('display', 'flex').fadeIn(2000);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    });

    $('#continue-shopping-btn').click(function () {
        $('#popupOverlay').css('display', 'none').fadeOut(1000);
        var currentPosition = window.location.hash;
        window.location.reload();
        $(window).on('load', function () {
            window.location.hash = currentPosition;
        });
    });

    // Khi triển khai lên Website cần thay đổi URL này 
    $('#view-cart-btn').click(function () {
        window.location.assign("http://localhost/phpmaster/project/laravel-project/gio-hang");
    });

    // RENDER ADDRESS DISTRICT
    $('#province').on('change', function () {
        let province_id = $(this).val();
        if (province_id > 0) {
            $.ajax({
                url: 'cart/payment/district',
                method: 'GET',
                data: {
                    province_id: province_id,
                },
                dataType: 'json',
                success: function (data) {
                    $('#district').empty();
                    $.each(data, function (index, district) {
                        $('#district').append($('<option>', {
                            value: district.id,
                            text: district.name,
                        }));
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    });

    //  RENDER ADDRESS WARDS
    $('#district').on('change', function () {
        let district_id = $(this).val();
        if (district_id > 0) {
            $.ajax({
                url: 'cart/payment/wards',
                method: 'GET',
                data: {
                    district_id: district_id,
                },
                dataType: 'json',
                success: function (data) {
                    $('#wards').empty();
                    $.each(data, function (index, wards) {
                        $('#wards').append($('<option>', {
                            value: wards.id,
                            text: wards.name,
                        }));
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    })
});
