<?php 

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;

class SendEmailReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        // dd($this);
        try {
            Mail::to($this->user->email)->send(new ReminderMail($this->user));
            Log::info("Reminder email successfully sent to: {$this->user->email}");
            
            // Optional: Save to a database activity log table if you have an ActivityLog model
            ActivityLog::create([
                'user_id' => $this->user->id,
                'action' => 'Reminder email sent',
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to send reminder email to {$this->user->email}: " . $e->getMessage());
            throw $e; // Re-throw to let Laravel handle job failure attempts
        }
    }
}
