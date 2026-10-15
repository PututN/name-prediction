<?php

namespace App\Services\Nationalize;

use RuntimeException;
use Throwable;

class NationalizeException extends RuntimeException
{
    public static function rateLimited(?Throwable $previous = null): self
    {
        return new self('Batas request harian nationalize.io sudah habis. Coba lagi besok.', 429, $previous);
    }

    public static function unavailable(?Throwable $previous = null): self
    {
        return new self('Gagal menghubungi nationalize.io. Coba lagi beberapa saat lagi.', 503, $previous);
    }
}
