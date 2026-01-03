<?php

declare(strict_types=1);

namespace App\Classes;

use App\Interfaces\UrlKeyGenerator;
use App\Models\Url;
use Illuminate\Support\Str;

class UlidKeyGenerator implements UrlKeyGenerator
{
    /**
     * The length of the generated URL key.
     */
    private int $keyLength;

    /**
     * Maximum attempts before increasing key length.
     */
    private int $maxAttempts;

    public function __construct(int $keyLength = 7, int $maxAttempts = 5)
    {
        $this->keyLength = $keyLength;
        $this->maxAttempts = $maxAttempts;
    }

    /**
     * Generate a unique and random URL key using ULID.
     * ULIDs are lexicographically sortable and have very low collision probability.
     * We extract a substring of the specified length and verify uniqueness.
     */
    public function generateRandom(): string
    {
        $attempt = 0;
        $currentLength = $this->keyLength;

        do {
            $ulid = (string) Str::ulid();
            $key = substr($ulid, 0, $currentLength);

            $attempt++;

            // If too many collisions, increase key length temporarily
            if ($attempt >= $this->maxAttempts) {
                $currentLength++;
                $attempt = 0;
            }
        } while (Url::where('url_key', $key)->exists());

        return $key;
    }

    /**
     * Generate a key for the short URL. This method allows you to pass a
     * seed value to the key generator. If no seed is passed, a random
     * key will be generated.
     */
    public function generateKeyUsing(?int $seed = null): string
    {
        if ($seed === null) {
            return $this->generateRandom();
        }

        // Generate deterministic key from seed
        $hash = hash('sha256', (string) $seed);

        return substr($hash, 0, $this->keyLength);
    }
}
