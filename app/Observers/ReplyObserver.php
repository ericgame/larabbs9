<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        // $reply->topic->increment('reply_count', 1);

        // $reply->topic->reply_count = $reply->topic->replies->count();
        // $reply->topic->save();

        $reply->topic->updateReplyCount();

        // 通知話題作者有新的評論
        $reply->topic->user->notify(new TopicReplied($reply));
    }

    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function deleted(Reply $reply)
    {
        // $reply->topic->reply_count = $reply->topic->replies->count();
        // $reply->topic->save();

        $reply->topic->updateReplyCount();
    }
}