<?php

namespace App\Services\AI;

interface AIServiceInterface
{
    /**
     * Extract actor information from description
     *
     * @param string $description
     * @return array|null
     */
    public function extractActorInfo(string $description): ?array;

    /**
     * Check if the service is properly configured
     *
     * @return bool
     */
    public function isConfigured(): bool;
}
