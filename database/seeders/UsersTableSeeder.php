<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成數據集合
        User::factory()->count(10)->create();

        // 單獨處理第一個用戶的數據
        $user = User::find(1);
        $user->name = 'Eric';
        $user->email = 'ericarc99@gmail.com';
        $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();

        // 初始化用戶角色，將 1 號用戶指派為『站長』
        $user->assignRole('Founder');

        // 將 2 號用戶指派為『管理員』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
