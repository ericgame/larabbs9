<?php
namespace App\Handlers;

use Illuminate\Support\Str;

class ImageUploadHandler
{
    // 只允許以下後綴名的圖片文件上傳
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix)
    {
        // 構建存儲的文件夾規則，值如：uploads/images/avatars/201709/21/
        // 文件夾切割能讓查找效率更高。
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());


        // 文件具體存儲的物理路徑，`public_path()` 獲取的是 `public` 文件夾的物理路徑。
        // 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatars/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 獲取文件的後綴名，因圖片從剪貼板裡黏貼時後綴名為空，所以此處確保後綴一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前綴是為了增加辨析度，前綴可以是相關數據模型的 ID 
        // 值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' .$extension;

        // 如果上傳的不是圖片將終止操作
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 將圖片移動到我們的目標存儲路徑中
        $file->move($upload_path, $filename);

        return [
            'path' => "/$folder_name/$filename"
        ];

        // return [
        //     'path' => config('app.url') . "/$folder_name/$filename"
        // ];
    }
}