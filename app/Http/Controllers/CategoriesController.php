<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        // 讀取與"分類id"關聯的話題，一頁20條訊息
        $topics = Topic::where('category_id', $category->id)->paginate(20);

        // 傳參變量"話題"和"分類"到模板中
        return view('topics.index', compact('topics', 'category'));
    }
}
