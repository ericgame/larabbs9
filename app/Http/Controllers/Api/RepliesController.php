<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Resources\ReplyResource;
use App\Http\Requests\Api\ReplyRequest;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic()->associate($topic); // $reply->topic_id = $topic->id
        $reply->user()->associate($request->user()); // $reply->user_id = $request->user()->id
        $reply->save();

        return new ReplyResource($reply);
    }
}