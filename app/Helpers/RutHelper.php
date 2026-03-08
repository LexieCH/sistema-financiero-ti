<?php

namespace App\Helpers;

class RutHelper
{
    public static function normalizar(?string $rut): ?string
    {
        if (!$rut) {
            return null;
        }

        $clean = strtoupper(preg_replace('/[^0-9Kk]/', '', $rut));

        if (strlen($clean) < 2) {
            return null;
        }

        $body = substr($clean, 0, -1);
        $dv = substr($clean, -1);

        return $body . '-' . $dv;
    }

    public static function esValido(?string $rut): bool
    {
        if (!$rut) {
            return false;
        }

        $rutNormalizado = self::normalizar($rut);

        if (!$rutNormalizado) {
            return false;
        }

        [$body, $dv] = explode('-', $rutNormalizado);

        if (!ctype_digit($body) || strlen($body) < 7) {
            return false;
        }

        $sum = 0;
        $multiplier = 2;

        for ($i = strlen($body) - 1; $i >= 0; $i--) {
            $sum += intval($body[$i]) * $multiplier;
            $multiplier = $multiplier === 7 ? 2 : $multiplier + 1;
        }

        $rest = 11 - ($sum % 11);

        if ($rest === 11) {
            $expectedDv = '0';
        } elseif ($rest === 10) {
            $expectedDv = 'K';
        } else {
            $expectedDv = (string) $rest;
        }

        return strtoupper($dv) === $expectedDv;
    }
}
