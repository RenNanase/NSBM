<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;

class TestMaternityAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-maternity-access {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if a user has access to maternity wards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->argument('username');

        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->error("User with username '{$username}' not found!");
            return 1;
        }

        $this->info("Testing maternity access for user: {$username}");

        // Get user's wards
        $userWards = $user->wards()->get();
        $this->info("User has access to " . $userWards->count() . " wards:");

        foreach ($userWards as $ward) {
            $this->line(" - {$ward->name}");
        }

        // Check for maternity access using the same logic as the middleware
        $userId = $user->id;

        $hasMaternityAccess = DB::table('user_ward')
            ->join('wards', 'user_ward.ward_id', '=', 'wards.id')
            ->where('user_ward.user_id', $userId)
            ->where(function($query) {
                $query->where('wards.name', 'like', '%MATERNITY%')
                    ->orWhere('wards.name', 'like', '%LABOUR%')
                    ->orWhere('wards.name', 'like', '%DELIVERY%')
                    ->orWhere('wards.name', 'like', '%OB-GYN%');
            })
            ->exists();

        if ($hasMaternityAccess) {
            $this->info("✅ User has maternity ward access.");
        } else {
            $this->warn("❌ User does NOT have maternity ward access.");
        }

        // Check admin privileges
        if ($user->username === 'admin') {
            $this->info("✅ User has admin privileges.");
        } else {
            $this->warn("❌ User does NOT have admin privileges.");
        }

        return 0;
    }
}
