<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 緩存相關
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // 獲取今天的日期
        // $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        // 同一天只有一個 "哈希表" 名稱
        // $hash = $this->hash_prefix . $date;

        // 獲取今日 Redis 哈希表名稱，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 字段名稱，如：user_1
        // $field = $this->field_prefix . $this->id;
        $field = $this->getHashField();

        /*
         用3個不同的帳號登入後，再用 dd(Redis::hGetAll($hash)) 查Redis全部的資料 :
         array[
                "user_1" => "2023-05-08 00:02:59"
                "user_2" => "2023-05-08 00:03:07"
                "user_3" => "2023-05-08 00:03:08"
              ]
        */
        // dd(Redis::hGetAll($hash));

        // 當前時間，如：2017-10-21 08:35:15
        $now = Carbon::now()->toDateTimeString();

        // 數據寫入 Redis ，字段已存在會被更新
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // 獲取昨天的日期，格式如：2017-10-21
        // $yesterday_date = Carbon::now()->toDateString(); //測試: 改為今天日期
        // $yesterday_date = Carbon::yesterday()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        // $hash = $this->hash_prefix . $yesterday_date;

        // 獲取昨日 Redis 哈希表名稱，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());

        // 從 Redis 中獲取所有哈希表裡的數據
        $dates = Redis::hGetAll($hash);

        // 遍歷，並同步到數據庫中
        foreach ($dates as $user_id => $actived_at) {
            // 會將 `user_1` 轉換為 1
            $user_id = str_replace($this->field_prefix, '', $user_id);

            // 只有當用戶存在時才更新到數據庫中
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        
        // 以數據庫為中心的存儲，既已同步，即可刪除
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        // 獲取今天的日期
        // $date = Carbon::now()->toDateString();

        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        // $hash = $this->hash_prefix . $date;

        // 獲取今日 Redis 哈希表名稱，如：larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 字段名稱，如：user_1
        // $field = $this->field_prefix . $this->id;
        $field = $this->getHashField();

        // 三元運算符，優先選擇 Redis 的數據，否則使用數據庫中的資料
        $datetime = Redis::hGet($hash, $field) ? : $value;

        // 如果存在的話，返回時間對應的 Carbon 實體
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否則使用用戶註冊時間
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        // 字段名稱，如：user_1
        return $this->field_prefix . $this->id;
    }
}