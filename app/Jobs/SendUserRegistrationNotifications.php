<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendUserRegistrationNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $plain_password;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $plain_password)
    {
        $this->user = $user;
        $this->plain_password = $plain_password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // 1️⃣ Envoi email à l'utilisateur
            Mail::send('emails.user.register', [
                'user_name' => $this->user->name,
                'email'     => $this->user->email,
                'password'  => $this->plain_password,
            ], function($message) {
                $message->to($this->user->email);
                $message->subject('Bienvenue sur LaCasa');
            });

            // 2️⃣ Envoi du SMS
            $message = "Bienvenue " . $this->user->name . " sur LaCasa. Votre compte a été créé avec succès. Merci de nous faire confiance!";
            $number = $this->user->phone1 ? $this->user->phone1 : $this->user->phone2;

            // $this->sendSms($number, $message);

            // 3️⃣ Notification aux admins
            $admins = User::where('role', '!=', 3)->get();

            $stats = [
                'total_users'    => User::count(),
                'total_agencies' => User::where('user_type', 2)->count(),
                'total_persons'  => User::where('user_type', 1)->count(),
            ];

            foreach ($admins as $admin) {
                Mail::send('emails.admin.registerAlert', [
                    'user'  => $this->user,
                    'stats' => $stats,
                ], function($message) use ($admin) {
                    $message->to($admin->email);
                    $message->subject('Nouveau utilisateur enregistré');
                });
            }

        } catch (\Exception $e) {
            Log::error("Erreur envoi job inscription: ".$e->getMessage());
        }
    }
    public function sendSms($number, $message)
    {
        $smsService = new SmsService ();
        $smsService->send($number, $message);
    }
}