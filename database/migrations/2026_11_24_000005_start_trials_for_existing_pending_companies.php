<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('entreprises')
            ->where('verification_status', 'pending')
            ->where('subscription_status', 'pending')
            ->update([
                'verification_status' => 'approved',
                'verified_at' => $now,
                'subscription_status' => 'trial',
                'trial_ends_at' => $now->copy()->addMonth(),
            ]);
    }

    public function down(): void {}
};
