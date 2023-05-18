<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速為用戶生成 token';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->ask('輸入用戶 id');

        $user = User::find($userId);

        if (!$user) {
            return $this->error('用戶不存在');
        }

        // 一年以後過期，單位分鐘
        $ttl = 365*24*60;
        $this->info(auth('api')->setTTL($ttl)->login($user));
    }
}
