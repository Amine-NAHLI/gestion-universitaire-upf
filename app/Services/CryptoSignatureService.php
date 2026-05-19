<?php

namespace App\Services;

use App\Models\DocumentSignature;
use App\Models\User;
use Illuminate\Support\Str;

class CryptoSignatureService
{
    private string $privateKeyPath;
    private string $publicKeyPath;

    public function __construct()
    {
        $this->privateKeyPath = storage_path('app/keys/upf_private.pem');
        $this->publicKeyPath = storage_path('app/keys/upf_public.pem');
    }

    /**
     * Generate the RSA key pair if they don't exist yet.
     */
    public function generateKeyPair(): void
    {
        $keysDir = storage_path('app/keys');
        if (!is_dir($keysDir)) {
            mkdir($keysDir, 0700, true);
        }

        if (file_exists($this->privateKeyPath) && file_exists($this->publicKeyPath)) {
            return; // Keys already exist
        }

        // Generate a 2048-bit RSA key pair
        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $keyResource = openssl_pkey_new($config);

        // Extract private key
        openssl_pkey_export($keyResource, $privateKey);
        file_put_contents($this->privateKeyPath, $privateKey);
        chmod($this->privateKeyPath, 0600);

        // Extract public key
        $publicKeyDetails = openssl_pkey_get_details($keyResource);
        file_put_contents($this->publicKeyPath, $publicKeyDetails['key']);

        \Illuminate\Support\Facades\Log::info('UPF RSA Key Pair generated successfully.');
    }

    /**
     * Sign a document's data and store the signature in the database.
     *
     * @param User $user The student whose document is being signed
     * @param string $documentType Type of document (e.g., 'releve_notes')
     * @param array $data The data to seal (student info, grades, etc.)
     * @return DocumentSignature
     */
    public function signDocument(User $user, string $documentType, array $data): DocumentSignature
    {
        $this->generateKeyPair(); // Ensure keys exist

        // Step 1: Create a canonical JSON string of the data
        $canonicalData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Step 2: Compute SHA-256 hash of the data
        $dataHash = hash('sha256', $canonicalData);

        // Step 3: Sign the hash with the university's private key
        $privateKey = file_get_contents($this->privateKeyPath);
        $signature = '';
        openssl_sign($dataHash, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        // Step 4: Generate a unique public-facing document ID
        $documentId = 'DOC-' . strtoupper(Str::random(12));

        // Step 5: Store everything in the database
        return DocumentSignature::create([
            'document_id' => $documentId,
            'user_id' => $user->id,
            'document_type' => $documentType,
            'data_hash' => $dataHash,
            'signature' => base64_encode($signature),
            'sealed_data' => $data,
            'issued_at' => now(),
        ]);
    }

    /**
     * Verify a document's cryptographic signature.
     *
     * @param DocumentSignature $doc The document signature record
     * @return bool True if signature is valid, false if tampered
     */
    public function verifySignature(DocumentSignature $doc): bool
    {
        $this->generateKeyPair(); // Ensure keys exist

        // Reconstruct the canonical data from what was sealed
        $canonicalData = json_encode($doc->sealed_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Recompute the hash
        $recomputedHash = hash('sha256', $canonicalData);

        // Verify using the public key
        $publicKey = file_get_contents($this->publicKeyPath);
        $signature = base64_decode($doc->signature);

        $result = openssl_verify($recomputedHash, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        return $result === 1;
    }
}
