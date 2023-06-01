<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Topic;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    // use RefreshDatabase; // 可以重整資料表，避免測試資料不斷累積。注意:這會刪除所有資料，不要用在正式環境。
    use ActingJWTUser;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(); //建立一個帳號
    }

    public function testStoreTopic()
    {
        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];

        // $token = auth('api')->fromUser($this->user);
        // $response = $this->withHeaders(['Authorization' => 'Bearer'.$token])->json('POST', '/api/v1/topics', $data);
        $response = $this->JWTActingAs($this->user)->json('POST', '/api/v1/topics', $data);

        $assertData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body', 'user_topic_body'),
        ];

        $response->assertStatus(201)->assertJsonFragment($assertData);
    }

    public function testUpdateTopic()
    {
        $topic = $this->makeTopic();

        $editData = ['category_id' => 2, 'body' => 'edit body', 'title' => 'edit title'];

        $response = $this->JWTActingAs($this->user)->json('PATCH', '/api/v1/topics/'.$topic->id, $editData);

        $assertData = [
            'category_id' => 2,
            'user_id' => $this->user->id,
            'title' => 'edit title',
            'body' => clean('edit body', 'user_topic_body'),
        ];

        $response->assertStatus(200)->assertJsonFragment($assertData);
    }

    protected function makeTopic()
    {
        return Topic::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => 1,
        ]);
    }

    public function testShowTopic()
    {
        $topic = $this->makeTopic();

        $response = $this->json('GET', '/api/v1/topics/'.$topic->id);

        $assertData = [
            'category_id' => $topic->category_id,
            'user_id' => $topic->user_id,
            'title' => $topic->title,
            'body' => $topic->body,
        ];

        $response->assertStatus(200)->assertJsonFragment($assertData);
    }

    public function testIndexTopic()
    {
        $response = $this->json('GET', '/api/v1/topics');

        $response->assertStatus(200)->assertJsonStructure(['data', 'meta']);
    }

    public function testDeleteTopic()
    {
        $topic = $this->makeTopic();
        $response = $this->JWTActingAs($this->user)->json('DELETE', '/api/v1/topics/'.$topic->id);
        $response->assertStatus(204);

        $response = $this->json('GET', '/api/v1/topics/'.$topic->id);
        $response->assertStatus(404);
    }
}
