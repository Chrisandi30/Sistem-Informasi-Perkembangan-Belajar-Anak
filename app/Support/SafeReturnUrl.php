<?php

namespace App\Support;

use Illuminate\Http\Request;

class SafeReturnUrl
{
    public static function fromRequest(Request $request, string $defaultUrl): string
    {
        $candidate = (string) $request->query('return_to', '');
        $candidateHost = parse_url($candidate, PHP_URL_HOST);
        $candidateScheme = parse_url($candidate, PHP_URL_SCHEME);
        $isSafeRelativeUrl = str_starts_with($candidate, '/') && ! str_starts_with($candidate, '//');
        $isSameHostUrl = in_array($candidateScheme, ['http', 'https'], true)
            && $candidateHost === $request->getHost();

        return $candidate !== '' && ($isSafeRelativeUrl || $isSameHostUrl)
            ? $candidate
            : $defaultUrl;
    }
}