<?php

use Illuminate\Support\Facades\DB;
use League\CommonMark\Normalizer\SlugNormalizer;


function list_product_search(){
    $list_product_search = DB::table('products')->where('status', 2)->get();
    return $list_product_search;
}

function has_child($data, $id)
{
    foreach ($data as $v) {
        if ($v['parent_id'] == $id) {
            return true;
        }
    }
    return false;
}

function render_menu($list_product_cat, $parent_id = 0, $level = 0, $url = '')
{
    if ($level == 0) {
        $result = "<ul id='sidebar_menu'>";
    } else {
        $result = "<ul class='sub_sidebar_menu'>";
    }
    foreach ($list_product_cat as $cat_product) {
        if ($cat_product['parent_id'] == $parent_id) {
            $result .= "<li>";
            $result .= "<a href='san-pham/".Str::slug($cat_product['name'])."/{$cat_product['id']}'>{$cat_product['name']}</a>";
            if (has_child($list_product_cat, $cat_product['id'])) {
                $result .= render_menu($list_product_cat, $cat_product['id'], $level + 1);
            }
            $result .= "</li>";
        }
    }
    $result .= "</ul>";
    return $result;
}

function currency_format($number)
{
    return number_format($number, 0, ',', '.') . "đ";
}


function data_tree_id($data, $id, $level = 0)
{

    $result = "";
    if ($level == 0) {
        $result = $id;
    }

    foreach ($data as $v) {
        if ($v['parent_id'] == $id) {
            $result .= ", " . $v['id'];
            if (has_child($data, $v['id'])) {
                $result .= data_tree_id($data, $v['id'], $level + 1);
            }
        }
    }
    return $result;
}

function show_data($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function render_menu_responsive($list_product_cat, $parent_id = 0, $level = 0)
{
    if ($level != 0) {
        $result = "<ul class='sub_menu_responsive'>";
    } else {
        $result = "";
    }

    foreach ($list_product_cat as $element) {
        if ($element['parent_id'] == $parent_id) {
            $result .= "<li>";
            $result .= "<div class='d-flex align-items-center'>";
            $result .= "<a href='san-pham/".Str::slug($element['name'])."/{$element['id']}'>{$element['name']}</a>";
            $result .= "<span></span>";
            $result .= "</div>";
            if (has_child($list_product_cat, $element['id'])) {
                $result .= render_menu_responsive($list_product_cat, $element['id'], $level + 1);
            }
            $result .= "</li>";
        }
    }

    if ($level != 0) {
        $result .= "</ul>";
    }
    return $result;
}
