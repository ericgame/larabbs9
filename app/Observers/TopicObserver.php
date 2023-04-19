<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

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

        // 如 slug 字段無內容，即使用翻譯器對 title 進行翻譯
        if (! $topic->slug) {
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}