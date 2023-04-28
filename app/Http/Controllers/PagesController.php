<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root()
    {
        return view('pages.root');
    }

    public function permissionDenied()
    {
        // 如果當前用戶有權限訪問後台，直接跳轉訪問
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }
        
        // 否則使用視圖
        return view('pages.permission_denied');
    }
}
