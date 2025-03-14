<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Interfaces;

use Random\RandomException;

/**
 * Interface SeedGeneratorInterface
 *
 * This interface defines the contract for generating cryptographically secure seed strings.
 */
interface SeedGeneratorInterface
{
    /**
     * Generate a cryptographically secure seed string.
     *
     * @param int $length The desired length of the seed string (in characters). Must be at least 1.
     *
     * @return string The generated seed string.
     *
     * @throws RandomException If the system cannot gather sufficient entropy.
     */
    public function generateSeed(int $length = 16): string;
}
