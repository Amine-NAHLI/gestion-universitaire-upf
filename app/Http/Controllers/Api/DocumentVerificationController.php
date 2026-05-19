<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentSignature;
use App\Services\CryptoSignatureService;
use Illuminate\Http\JsonResponse;

class DocumentVerificationController extends Controller
{
    /**
     * Verify a document's cryptographic signature.
     * Called by the React frontend when a QR code is scanned.
     *
     * GET /api/verify/{documentId}
     */
    public function verify(string $documentId, CryptoSignatureService $cryptoService): JsonResponse
    {
        // Find the document signature record
        $doc = DocumentSignature::where('document_id', $documentId)->first();

        if (!$doc) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Document introuvable. Ce code ne correspond à aucun document certifié par l\'UPF.',
            ], 404);
        }

        // Verify the cryptographic signature
        $isValid = $cryptoService->verifySignature($doc);

        if (!$isValid) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'ALERTE FRAUDE : La signature cryptographique est invalide. Ce document a potentiellement été altéré.',
            ], 400);
        }

        // Signature is valid — return the sealed data
        return response()->json([
            'status' => 'valid',
            'data' => $doc->sealed_data,
        ], 200);
    }
}
