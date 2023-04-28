<?php

use Illuminate\Support\Facades\Route;

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str()->limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    // 獲取數據模型的複數蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前綴
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站點 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 標籤，並返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    // 從實體中獲取完整類名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 獲取基礎類名，例如：傳參 `App\Models\User` 會得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：傳參 `User`  會得到 `user`, `FooBar` 會得到 `foo_bar`
    $snake_case_name = str()->snake($class_name);

    // 獲取字串的複數形式，例如：傳參 `user` 會得到 `users`
    return str()->plural($snake_case_name);
}