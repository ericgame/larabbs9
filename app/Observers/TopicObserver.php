<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // HTMLPurifier 過濾輸入的資料，避免 XSS 攻擊
        $topic->body = clean($topic->body, 'user_topic_body');

        // 產生"文章摘要"，用於SEO
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段無內容，即使用翻譯器對 title 進行翻譯 (不用隊列)
        // if (! $topic->slug) {
            //不用隊列
            // $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);

            // 推送任務到隊列
            // dispatch(new TranslateSlug($topic));
        // }
    }

    public function saved(Topic $topic)
    {
        // 如 slug 字段無內容，即使用翻譯器對 title 進行翻譯 (用隊列)
        if (! $topic->slug) {
            // 推送任務到隊列
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic)
    {
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}