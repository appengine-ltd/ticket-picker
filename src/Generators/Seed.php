<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Generators;

use AppEngine\TicketPicker\Interfaces\SeedGeneratorInterface;
use InvalidArgumentException;
use Random\RandomException;

/**
 * Class Seed
 *
 * This class provides a method to generate a cryptographically secure seed string.
 *
 * @package AppEngine\TicketPicker\Generators
 */
class Seed implements SeedGeneratorInterface
{
    /**
     * Generate a cryptographically secure seed string.
     *
     * The process calculates the number of random bytes required based on the desired seed length,
     * generates the random bytes using random_bytes(), converts them to a hexadecimal string, and
     * returns the seed string truncated to the specified length.
     *
     * @param int $length The desired length of the seed string (in characters). Must be at least 1.
     *
     * @return string The generated seed string.
     *
     * @throws RandomException If the system cannot gather sufficient entropy.
     * @throws InvalidArgumentException If the provided length is less than 1.
     */
    public function generateSeed(int $length = 16): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Seed length must be at least 1.');
        }

        // Calculate the required number of bytes and ensure it's at least 1.
        $byteLength = max(1, (int) ceil($length / 2));
        $randomBytes = random_bytes($byteLength);
        $hexString = bin2hex($randomBytes);

        return substr($hexString, 0, $length);
    }
}
