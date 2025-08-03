<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class VerifyStaffEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify email address for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $this->info("User found: {$user->name} ({$user->email})");
        $this->info("Current status:");
        $this->info("- Email verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
        $this->info("- First login: " . ($user->first_login ? 'Yes' : 'No'));
        $this->info("- Role: " . $user->role);
        
        if ($user->email_verified_at && !$user->first_login) {
            $this->info("User {$email} is already fully verified and ready to use!");
            return 0;
        }
        
        $updates = [];
        
        if (!$user->email_verified_at) {
            $updates['email_verified_at'] = Carbon::now();
            $this->info("Setting email_verified_at to now");
        }
        
        if ($user->first_login) {
            $updates['first_login'] = false;
            $this->info("Setting first_login to false");
        }
        
        if (!empty($updates)) {
            $user->update($updates);
            $this->info("Successfully updated user: {$email}");
        }
        
        return 0;
    }
}
