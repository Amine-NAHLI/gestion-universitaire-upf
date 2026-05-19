<?php

namespace App\Jobs;

use App\Models\NotificationApp;
use App\Models\User;
use App\Services\AI\PromptBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendNightlyAIAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $parents = User::where('role', 'parent')->where('is_active', true)->get();

        foreach ($parents as $parent) {
            try {
                $studentEmail = str_replace('+parent', '', $parent->email);
                $studentUser  = User::where('email', $studentEmail)->first();
                $etudiant     = $studentUser?->etudiant;

                if (! $etudiant) {
                    continue;
                }

                $etudiant->loadMissing([
                    'notes.module',
                    'absences',
                    'user.demandesAdministratives',
                    'groupe.niveau.filiere',
                ]);

                $systemPrompt = PromptBuilder::build($etudiant);

                $apiKey = config('services.groq.key', '');
                $model  = config('services.groq.model', 'llama-3.3-70b-versatile');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => $model,
                    'max_tokens'  => 200,
                    'temperature' => 0.3,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => "Analyse le dossier académique de cet étudiant de façon objective. Y a-t-il quelque chose d'urgent ou d'important à signaler à son parent aujourd'hui ?\nRéponds UNIQUEMENT par un JSON valide sans texte autour :\n{\"alert\": true, \"niveau\": \"info\", \"message\": \"...\"}\nNiveaux possibles : info, warning, urgent. Si tout va bien : {\"alert\": false}"],
                    ],
                ]);

                if (! $response->successful()) {
                    Log::warning("Nightly alert API error for parent {$parent->id}: HTTP {$response->status()}");
                    continue;
                }

                $raw = $response->json('choices.0.message.content', '');

                // Extract JSON block (model may wrap with prose)
                preg_match('/\{[^{}]*\}/s', $raw, $matches);
                $json   = json_decode($matches[0] ?? '{}', true);
                $alert  = (bool) ($json['alert'] ?? false);
                $niveau = in_array($json['niveau'] ?? '', ['info', 'warning', 'urgent']) ? $json['niveau'] : 'info';
                $msg    = trim($json['message'] ?? '');

                Log::info("Alert check for parent {$parent->id}: " . ($alert ? $niveau : 'no alert'));

                if ($alert && $msg) {
                    $titres = [
                        'info'    => 'Information scolaire',
                        'warning' => "Point d'attention",
                        'urgent'  => 'Alerte urgente',
                    ];

                    NotificationApp::create([
                        'user_id' => $parent->id,
                        'titre'   => $titres[$niveau],
                        'message' => $msg,
                        'type'    => $niveau,
                        'lue'     => false,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("Nightly alert exception for parent {$parent->id}: {$e->getMessage()}");
            }
        }
    }
}
