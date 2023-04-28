<?php

use Illuminate\Support\Facades\Auth;

return array(

    /*
     * Package URI
     *
     * @type string
     */
    // 後台的 URI 入口
    'uri' => 'admin',

    /*
     *  Domain for routing.
     *
     *  @type string
     */
    // 後台專屬域名，沒有的話可以留空
    'domain' => '',

    /*
     * Page title
     *
     * @type string
     */
    // 'title' => config('app.name'),

    // 應用名稱，在頁面標題和左上角站點名稱處顯示
    'title' => env('APP_NAME', 'Laravel'),

    /*
     * The path to your model config directory
     *
     * @type string
     */
    // 模型配置信息文件存放目錄
    'model_config_path' => config_path('administrator'),

    /*
     * The path to your settings config directory
     *
     * @type string
     */
    // 配置信息文件存放目錄
    'settings_config_path' => config_path('administrator/settings'),

    /*
     * The menu structure of the site. For models, you should either supply the name of a model config file or an array of names of model config
     * files. The same applies to settings config files, except you must prepend 'settings.' to the settings config file name. You can also add
     * custom pages by prepending a view path with 'page.'. By providing an array of names, you can group certain models or settings pages
     * together. Each name needs to either have a config file in your model config path, settings config path with the same name, or a path to a
     * fully-qualified Laravel view. So 'users' would require a 'users.php' file in your model config path, 'settings.site' would require a
     * 'site.php' file in your settings config path, and 'page.foo.test' would require a 'test.php' or 'test.blade.php' file in a 'foo' directory
     * inside your view directory.
     *
     * @type array
     *
     * 	array(
     *		'E-Commerce' => array('collections', 'products', 'product_images', 'orders'),
     *		'homepage_sliders',
     *		'users',
     *		'roles',
     *		'colors',
     *		'Settings' => array('settings.site', 'settings.ecommerce', 'settings.social'),
     * 		'Analytics' => array('E-Commerce' => 'page.ecommerce.analytics'),
     *	)
     */
    // 'menu' => [],

    /*
     * 後台菜單數組，多維數組渲染結果為多級嵌套菜單。
     *
     * 數組裡的值有三種類型：
     * 1. 字符串 —— 子菜單的入口，不可訪問；
     * 2. 模型配置文件 —— 訪問 `model_config_path` 目錄下的模型文件，如 `users` 訪問的是 `users.php` 模型配置文件；
     * 3. 配置信息 —— 必須使用前綴 `settings.`，對應 `settings_config_path` 目錄下的文件，如：默認設置下，
     *              `settings.site` 訪問的是 `administrator/settings/site.php` 文件
     * 4. 頁面文件 —— 必須使用前綴 `page.`，如：`page.pages.analytics` 對應 `administrator/pages/analytics.php`
     *               或者是 `administrator/pages/analytics.blade.php` ，兩種後綴名皆可
     *
     * 示例：
     *     [
     *        'users',
     *        'E-Commerce' => ['collections', 'products', 'product_images', 'orders'],
     *        'Settings'  => ['settings.site', 'settings.ecommerce', 'settings.social'],
     *        'Analytics' => ['E-Commerce' => 'page.pages.analytics'],
     *    ]
     */
    'menu' => [
        '用戶與權限' => [
            'users',
            'roles',
            'permissions',
        ],
        '內容管理' => [
            'categories',
            'topics',
            'replies',
        ],
    ],



    /*
     * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
     * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
     *
     * @type closure
     */
    // 'permission' => function () {
    //     return Auth::check();
    // },

    /*
     * 權限控制的回調函數。
     *
     * 此回調函數需要返回 true 或 false ，用來檢測當前用戶是否有權限訪問後台。
     * `true` 為通過，`false` 會將頁面重定向到 `login_path` 選項定義的 URL 中。
     */
    'permission' => function () {
        // 只要是能管理內容的用戶，就允許訪問後台
        return Auth::check() && Auth::user()->can('manage_contents');
    },


    /*
     * This determines if you will have a dashboard (whose view you provide in the dashboard_view option) or a non-dashboard home
     * page (whose menu item you provide in the home_page option)
     *
     * @type bool
     */
    /*
     * 使用布爾值來設定是否使用後台主頁面。
     *
     * 如值為 `true`，將使用 `dashboard_view` 定義的視圖文件渲染頁面；
     * 如值為 `false`，將使用 `home_page` 定義的菜單條目來作為後台主頁。
     */
    'use_dashboard' => false,

    /*
     * If you want to create a dashboard view, provide the view string here.
     *
     * @type string
     */
    // 設置後台主頁視圖文件，由 `use_dashboard` 選項決定
    'dashboard_view' => '',

    /*
     * The menu item that should be used as the default landing page of the administrative section
     *
     * @type string
     */
    // 用來作為後台主頁的菜單條目，由 `use_dashboard` 選項決定，菜單指的是 `menu` 選項
    'home_page' => 'users',

    /*
     * The route to which the user will be taken when they click the "back to site" button
     *
     * @type string
     */
    // 右上角『返回主站』按鈕的鏈接
    'back_to_site_path' => '/',

    /*
     * The login path is the path where Administrator will send the user if they fail a permission check
     *
     * @type string
     */
    // 當選項 `permission` 權限檢測不通過時，會重定向用戶到此處設置的路徑
    'login_path' => 'login',

    /*
     * The logout path is the path where Administrator will send the user when they click the logout link
     *
     * @type string
     */
    // 'logout_path' => false,

    /*
     * This is the key of the return path that is sent with the redirection to your login_action. Session::get('redirect') will hold the return URL.
     *
     * @type string
     */
    // 允許在登錄成功後使用 Session::get('redirect') 將用戶重定向到原本想要訪問的後台頁面
    'login_redirect_key' => 'redirect',

    /*
     * Global default rows per page
     *
     * @type int
     */
    // 控制模型數據列表頁默認的顯示條目
    'global_rows_per_page' => 20,

    /*
     * An array of available locale strings. This determines which locales are available in the languages menu at the top right of the Administrator
     * interface.
     *
     * @type array
     */
    // 可選的語言，如果不為空，將會在頁面頂部顯示『選擇語言』按鈕
    'locales' => [],

    // 'custom_routes_file' => app_path('Http/routes/administrator.php'),
);
