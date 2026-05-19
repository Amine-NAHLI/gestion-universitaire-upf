<?php

namespace App\Services\AI;

use App\Models\Etudiant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key', '');
        $this->model  = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    public function ask(Etudiant $etudiant, string $message, array $history): string
    {
        $systemPrompt = PromptBuilder::build($etudiant);

        $validHistory = array_values(array_filter($history, fn ($m) =>
            isset($m['role'], $m['content']) &&
            in_array($m['role'], ['user', 'assistant'], true) &&
            is_string($m['content'])
        ));

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $validHistory,
            [['role' => 'user', 'content' => $message]]
        );

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => $this->model,
                'max_tokens'  => 1024,
                'temperature' => 0.4,
                'messages'    => $messages,
            ]);

            if (! $response->successful()) {
                Log::error('Groq API returned non-2xx', ['status' => $response->status()]);
                return "L'assistant est temporairement indisponible. Veuillez réessayer.";
            }

            return $response->json('choices.0.message.content')
                ?? "L'assistant est temporairement indisponible. Veuillez réessayer.";
        } catch (\Throwable $e) {
            Log::error('ChatbotService HTTP error', ['message' => $e->getMessage()]);
            return "L'assistant est temporairement indisponible. Veuillez réessayer.";
        }
    }

    public function generateWelcomeMessage(Etudiant $etudiant): string
    {
        $systemPrompt = PromptBuilder::build($etudiant);

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user',   'content' => "Génère un message de bienvenue court et personnalisé pour le parent de l'étudiant. Mentionne son prénom, un point positif ou un point d'attention basé sur ses données (notes, absences). Maximum 5 lignes. Utilise des emojis avec parcimonie."],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(20)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => $this->model,
                'max_tokens'  => 300,
                'temperature' => 0.6,
                'messages'    => $messages,
            ]);

            if (! $response->successful()) {
                return "Bonjour ! Je suis votre Assistant E-UPF. Comment puis-je vous aider concernant la scolarité de votre enfant ?";
            }

            return $response->json('choices.0.message.content')
                ?? "Bonjour ! Je suis votre Assistant E-UPF. Comment puis-je vous aider concernant la scolarité de votre enfant ?";
        } catch (\Throwable $e) {
            return "Bonjour ! Je suis votre Assistant E-UPF. Comment puis-je vous aider concernant la scolarité de votre enfant ?";
        }
    }
}
