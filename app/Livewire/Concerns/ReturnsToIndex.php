<?php

// Livewire: app/Livewire/Concerns/ReturnsToIndex.php

namespace App\Livewire\Concerns;

trait ReturnsToIndex
{
    public string $returnTo = '';

    // Simpan alamat halaman asal yang aman.
    protected function initializeReturnTo(string $defaultUrl): void
    {
        $candidate = (string) request()->query('return_to', '');
        $candidateHost = parse_url($candidate, PHP_URL_HOST);
        $candidateScheme = parse_url($candidate, PHP_URL_SCHEME);
        $currentHost = request()->getHost();
        $isSafeRelativeUrl = str_starts_with($candidate, '/') && ! str_starts_with($candidate, '//');
        $isSameHostUrl = in_array($candidateScheme, ['http', 'https'], true)
            && $candidateHost === $currentHost;

        $this->returnTo = $candidate !== '' && ($isSafeRelativeUrl || $isSameHostUrl)
            ? $candidate
            : $defaultUrl;
    }

    // Kembali ke halaman daftar sebelumnya.
    protected function redirectToIndex()
    {
        return $this->redirect($this->returnTo, navigate: true);
    }
}
