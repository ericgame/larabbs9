<?php

use App\Models\Link;
use Illuminate\Support\Facades\Auth;

return [
    'title'   => '資源推薦',
    'single'  => '資源推薦',

    'model'   => Link::class,

    // 訪問權限判斷
    'permission'=> function()
    {
        // 只允許站長管理資源推薦鏈接
        return Auth::user()->hasRole('Founder');
    },

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'title' => [
            'title'    => '名稱',
            'sortable' => false,
        ],
        'link' => [
            'title'    => '鏈接',
            'sortable' => false,
        ],
        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'title' => [
            'title'    => '名稱',
        ],
        'link' => [
            'title'    => '鏈接',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => '標籤 ID',
        ],
        'title' => [
            'title' => '名稱',
        ],
    ],
];