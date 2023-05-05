<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            // 當 user_id 對應的 users 表數據被刪除時，刪除此條數據
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('replies', function (Blueprint $table) {
            // 當 user_id 對應的 users 表數據被刪除時，刪除此條數據
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // 當 topic_id 對應的 topics 表數據被刪除時，刪除此條數據
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            // 移除外鍵約束
            $table->dropForeign(['user_id']);
        });

        Schema::table('replies', function (Blueprint $table) {
            // 移除外鍵約束
            $table->dropForeign(['user_id']);
            $table->dropForeign(['topic_id']);
        });
    }
};
