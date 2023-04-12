<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, Topic $topic)
    {
        // 讀取與"分類id"關聯的話題，一頁20條訊息
        $topics = $topic->withOrder($request->order)
                        ->where('category_id', $category->id)
                        ->with('user', 'category')   // 預加載防止 N+1 問題
                        ->paginate(20);

        // 傳參變量"話題"和"分類"到模板中
        return view('topics.index', compact('topics', 'category'));
    }
}
