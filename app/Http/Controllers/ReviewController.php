<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    // Liste publique, paginate 10 par page
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'comments.user'])
            ->where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    // Stocker un avis (utilisateur connecté requis)
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $review = Review::create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_visible' => true,
        ]);

        // Préparer données pour mail
        $data = [
            'review' => $review,
            'author' => $user,
        ];

        // Envoyer un mail à tous les admins et super-admins (role != 3) - ta façon:
        $admins = User::where('role', '!=', 3)->get();
        foreach ($admins as $admin) {
            Mail::send('emails.admin.reviewAlert', $data, function($message) use ($admin) {
                $message->to($admin->email);
                $message->subject('Nouveau avis déposé');
            });
        }

        return response()->json([
            'status' => true,
            'message' => 'Merci pour votre avis !',
            'review' => $review->load('user')
        ]);
    }

    // Admin répond à un avis
    public function comment(Request $request, Review $review)
    {
        $user = Auth::user();

        // Vérifie rôle admin (1 ou 2)
        if (!in_array($user->role, [1,2])) {
            return response()->json(['status'=>false,'message'=>'Autorisation refusée'], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message'=>$validator->errors()->first()], 422);
        }

        $comment = ReviewComment::create([
            'review_id' => $review->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'is_admin_comment' => true,
        ]);

        // Envoyer email à l'auteur de l'avis pour le prévenir de la réponse
        $author = $review->user;
        if ($author && $author->email) {
            $data = [
                'review' => $review,
                'comment' => $comment,
                'user' => $user,
                'userName' => $user->role <= 2 ? 'LaCasa' : $user->name
            ];

            Mail::send('emails.user.reviewReply', $data, function($message) use ($author) {
                $message->to($author->email);
                $message->subject('Réponse à votre avis');
            });
        }

        return response()->json([
            'status' => true,
            'message' => 'Réponse ajoutée',
            'comment' => $comment->load('user')
        ]);
    }

    // Supprimer un avis (admin)
    public function destroy(Review $review)
    {
        $user = Auth::user();
        if ($user->role <= 2 || $user->id === $review->user_id) {
            return response()->json(['status'=>false,'message'=>'Autorisation refusée'], 403);
        }

        $review->delete();
        return response()->json(['status'=>true,'message'=>'Avis supprimé']);
    }

    // Supprimer un commentaire (admin)
    public function deleteComment(ReviewComment $comment)
    {
        $user = Auth::user();
        if (!in_array($user->role, [1,2])) {
            return response()->json(['status'=>false,'message'=>'Autorisation refusée'], 403);
        }

        $comment->delete();
        return response()->json(['status'=>true,'message'=>'Commentaire supprimé']);
    }

    // Optionnel : show single review
    public function show(Review $review)
    {
        return response()->json($review->load('user','comments.user'));
    }
}
