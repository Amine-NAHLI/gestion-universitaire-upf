<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\User;
use App\Services\AI\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotService $chatbot) {}

    public function ask(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'history' => ['array', 'max:10'],
        ]);

        $parent = Auth::user();

        $studentEmail = str_replace('+parent', '', $parent->email);
        $studentUser  = User::where('email', $studentEmail)->first();
        $etudiant     = $studentUser?->etudiant;

        if (! $etudiant) {
            return response()->json([
                'reply' => "Aucun étudiant associé à votre compte. Contactez l'administration.",
            ], 403);
        }

        // 20 messages per day rate limit
        $cacheKey = "chatbot_{$parent->id}_count_" . now()->toDateString();
        $count    = Cache::get($cacheKey, 0);

        if ($count >= 20) {
            return response()->json([
                'reply' => 'Vous avez atteint la limite de 20 questions par jour.',
            ], 429);
        }

        Cache::put($cacheKey, $count + 1, now()->endOfDay());

        $history = array_values(array_filter(
            $request->input('history', []),
            fn ($m) => isset($m['role'], $m['content']) &&
                       in_array($m['role'], ['user', 'assistant'], true)
        ));

        ChatbotConversation::create([
            'parent_id'   => $parent->id,
            'etudiant_id' => $etudiant->id,
            'role'        => 'user',
            'content'     => $request->string('message'),
            'is_welcome'  => false,
        ]);

        $reply = $this->chatbot->ask($etudiant, $request->string('message'), $history);

        $assistantMsg = ChatbotConversation::create([
            'parent_id'   => $parent->id,
            'etudiant_id' => $etudiant->id,
            'role'        => 'assistant',
            'content'     => $reply,
            'is_welcome'  => false,
        ]);

        return response()->json(['reply' => $reply, 'message_id' => $assistantMsg->id]);
    }

    public function welcome(): JsonResponse
    {
        $parent       = Auth::user();
        $studentEmail = str_replace('+parent', '', $parent->email);
        $studentUser  = User::where('email', $studentEmail)->first();
        $etudiant     = $studentUser?->etudiant;

        if (! $etudiant) {
            return response()->json([
                'message' => "Bonjour ! Je suis votre Assistant E-UPF. Comment puis-je vous aider concernant la scolarité de votre enfant ?",
                'id'      => null,
            ]);
        }

        $message = $this->chatbot->generateWelcomeMessage($etudiant);

        $saved = ChatbotConversation::create([
            'parent_id'   => $parent->id,
            'etudiant_id' => $etudiant->id,
            'role'        => 'assistant',
            'content'     => $message,
            'is_welcome'  => true,
        ]);

        return response()->json(['message' => $message, 'id' => $saved->id]);
    }

    public function history(): JsonResponse
    {
        $parent = Auth::user();

        $messages = ChatbotConversation::where('parent_id', $parent->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get(['id', 'role', 'content', 'feedback', 'is_welcome'])
            ->reverse()
            ->values();

        return response()->json($messages);
    }

    public function saveFeedback(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => ['required', 'exists:chatbot_conversations,id'],
            'feedback'   => ['required', 'in:up,down'],
        ]);

        $message = ChatbotConversation::findOrFail($request->integer('message_id'));

        if ($message->parent_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->update(['feedback' => $request->string('feedback')]);

        return response()->json(['success' => true]);
    }
}
