<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // 供我們調用命令
    protected $signature = 'larabbs:calculate-active-user';

    /**
     * The console command description.
     *
     * @var string
     */
    // 命令的描述
    protected $description = '生成活躍用戶';

    /**
     * Execute the console command.
     *
     * @return int
     */
    // 最終執行的方法
    public function handle(User $user)
    {
        // 在命令行打印一行信息
        $this->info("開始計算...");

        $user->calculateAndCacheActiveUsers();

        $this->info("成功生成！");
    }
}
