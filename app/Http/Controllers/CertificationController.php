<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CertificationController extends Controller
{
    public function toggleVerification(User $user)
    {
        $user->is_verified = !$user->is_verified;
        $user->save();

        // === ENVOI UNIQUEMENT SI CERTIFIÉ ===
        if ($user->is_verified) {

            // Email
            $this->sendEmailVerification($user->name, $user->email);

            // SMS
            $message = "Félicitations " . $user->name . "! Votre certification LaCasa est validée. Vous êtes désormais partenaire officiel.";
            $number = $user->phone1 ?: $user->phone2;
            $this->sendSms($number, $message);
        }

        return response()->json([
            'status' => true,
            'message' => $user->is_verified ? 'Agence certifiée ✅' : 'Certification retirée ❌',
            'user' => $user
        ]);
    }

    public function revoke(Request $request, User $user)
    {
        $request->validate(['reason' => 'required|string']);

        $user->is_verified = false;
        $user->save();

        // Envoi EMAIL (ton système existant)
        Mail::send('emails.certification.revoked', [
            'user_name' => $user->name,
            'reason'    => $request->reason,
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Retrait de votre certification');
        });

        // SMS
        $message = $user->name . ", nous vous informons que votre certification LaCasa a été retirée. Veuillez consulter votre email pour plus de détails.";
        $number = $user->phone1 ?: $user->phone2;
        $this->sendSms($number, $message);

        return response()->json([
            'status' => true,
            'message' => 'La certification a été retirée.'
        ]);
    }

    public function reject(Request $request, User $user)
    {
        $request->validate([
            'reasons' => 'required|array|min:1',
            'comment' => 'nullable|string'
        ]);

        $motifs = implode("\n - ", $request->reasons);

        // Envoi EMAIL (ton système existant)
        Mail::send('emails.certification.rejected', [
            'user_name' => $user->name,
            'motifs'    => $motifs,
            'comment'   => $request->comment
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Rejet de votre demande de certification');
        });

        $message = $user->name . ", votre demande de certification LaCasa n’a pas été validée. Tous les détails sont disponibles dans votre email.";
        $number = $user->phone1 ?: $user->phone2;
        $this->sendSms($number, $message);

        return response()->json([
            'status' => true,
            'message' => 'La demande de certification a été rejetée.'
        ]);
    }

    public function sendEmailVerification($user_name, $email)
    {
        Mail::send('emails.certification.certify', [
            'user_name' => $user_name,
            'email' => $email,
        ], function($message) use ($email){
            $message->to($email);
            $message->subject('Certification LaCasa est maintenant active');
        });
    }

    public function sendEmailRejection($user_name, $email, $motifs, $comment = null)
    {
        $subject = "Rejet de votre demande de certification";

        Mail::send('emails.certification.rejected', [
            'user_name' => $user_name,
            'motifs' => $motifs,
            'comment' => $comment,
        ], function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    public function sendEmailRevocation($user_name, $email, $reason)
    {
        $subject = "Retrait de votre certification";

        Mail::send('emails.certification.revoked', [
            'user_name' => $user_name,
            'reason' => $reason,
        ], function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

    public function sendSms($number, $message)
    {
        $smsService = new SmsService ();
        $response = $smsService->send($number, $message);
        // Log::info($response);
        return response()->json($response);

        // response example in format json
        // array (
        //     'status' => true,
        //     'message' => 'MESSAGE_SENT_SUCCESSFULLY',
        //     'data' => 
        //     array (
        //         'status' => 1,
        //         'response_token' => 'push_sms_afgrchw6re2bjnr',
        //     ),
        //     'status_code' => 200,
        // )
    }
}