<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, Topic $topic, User $user)
    {
        // 讀取與"分類id"關聯的話題，一頁20條訊息
        $topics = $topic->withOrder($request->order)
                        ->where('category_id', $category->id)
                        ->with('user', 'category')   // 預加載防止 N+1 問題
                        ->paginate(20);

        // 活躍用戶列表
        $active_users = $user->getActiveUsers();

        // 傳參變量"話題"和"分類"到模板中
        return view('topics.index', compact('topics', 'category', 'active_users'));
    }
}
