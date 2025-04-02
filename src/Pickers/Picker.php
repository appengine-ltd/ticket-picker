<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Pickers;

use AppEngine\TicketPicker\Exceptions\PickerException;
use AppEngine\TicketPicker\Interfaces\PickerInterface;
use Exception;

/**
 * Class Picker
 *
 * This class generates a code from an array of ticket codes.
 * The process involves sanitizing the ticket codes (removing whitespace),
 * validating that they are non-empty and all the same length,
 * converting a seed string to an integer, and then selecting a character
 * from the unique candidates at each position of the ticket codes.
 *
 * @package AppEngine\TicketPicker\Pickers
 */
class Picker implements PickerInterface
{
    /**
     * Generates a code from the provided ticket codes using the given seed.
     *
     * The process is as follows:
     * - Sanitize the ticket codes by removing all whitespace.
     * - Validate that none of the ticket codes are empty and that all codes have equal length.
     * - Convert the seed string into an integer to seed the pseudo-random generator.
     * - For each position in the ticket codes, select a random character from the unique characters at that position.
     *
     * @param array<int, string> $ticketCodes Array of ticket codes.
     * @param string $seed The seed string used for random generation.
     *
     * @return string The generated code.
     *
     * @throws Exception If validation fails or if no characters are available at any position.
     */
    public function generateCode(array $ticketCodes, string $seed): string
    {
        // Sanitize and validate the ticket codes.
        $sanitizedTickets = $this->sanitizeTicketCodes($ticketCodes);
        $this->validateTicketCodes($sanitizedTickets);
        $codeLength = $this->getCodeLength($sanitizedTickets);

        // Seed the random number generator using a deterministic conversion of the seed string.
        mt_srand($this->convertSeedToInteger($seed));

        $generatedCode = '';

        // For each character position, pick a random character from the unique candidates.
        do {
            $pool = array_values(
                array_filter(
                    $sanitizedTickets,
                    static fn (string $code) => str_starts_with($code, $generatedCode),
                ),
            );

            if (count($pool) === 1) {
                $generatedCode = $pool[0];

                break;
            }

            $candidates = $this->getCharactersAtPosition($pool, strlen($generatedCode));
            $selectedChar = $this->getRandomCharacter($candidates);

            $generatedCode .= $selectedChar;
        } while (strlen($generatedCode) < $codeLength);

        return $generatedCode;
    }

    /**
     * Retrieves a random character from the given array of characters.
     *
     * @param array<int, string> $characters Array of single-character strings.
     *
     * @return string A randomly selected character.
     */
    protected function getRandomCharacter(array $characters): string
    {
        return $characters[array_rand($characters)];
    }

    /**
     * Retrieves all unique characters from the specified position in each ticket code.
     *
     * @param array<int, string> $ticketCodes Array of sanitized ticket codes.
     * @param int $pos The position (0-indexed) from which to extract characters.
     *
     * @return array<int, string> An array of unique characters found at the specified position.
     */
    protected function getCharactersAtPosition(array $ticketCodes, int $pos): array
    {
        $characters = [];
        foreach ($ticketCodes as $ticket) {
            if (isset($ticket[$pos])) {
                $characters[] = $ticket[$pos];
            }
        }
        return array_unique($characters);
    }

    /**
     * Converts a seed string into an integer.
     *
     * This is done by hashing the seed using SHA-256 and taking the first 8 characters
     * of the hash (32 bits), which is then converted to an integer.
     *
     * @param string $seed The seed string.
     *
     * @return int The integer representation of the seed.
     */
    protected function convertSeedToInteger(string $seed): int
    {
        return (int) hexdec(substr(hash('sha256', $seed), 0, 8));
    }

    /**
     * Validates the sanitized ticket codes.
     *
     * The validation ensures:
     * - No ticket code is empty.
     * - All ticket codes have the same length.
     *
     * @param array<int, string> $ticketCodes Array of sanitized ticket codes.
     *
     * @return void
     *
     * @throws Exception If any ticket code is empty or if ticket codes have differing lengths.
     */
    protected function validateTicketCodes(array $ticketCodes): void
    {
        foreach ($ticketCodes as $ticket) {
            if ($ticket === '') {
                throw PickerException::whitespaceInTicketCode();
            }
        }

        $lengths = array_map('strlen', $ticketCodes);
        if (count(array_unique($lengths)) > 1) {
            throw PickerException::invalidTicketCodeLength();
        }
    }

    /**
     * Retrieves the length of the ticket codes.
     *
     * Assumes that all ticket codes have been validated to have the same length.
     *
     * @param array<int, string> $ticketCodes Array of sanitized ticket codes.
     *
     * @return int The length of the first ticket code.
     *
     * @throws Exception If the array of ticket codes is empty.
     */
    protected function getCodeLength(array $ticketCodes): int
    {
        if (empty($ticketCodes)) {
            throw PickerException::missingTicketCode();
        }
        return strlen($ticketCodes[0]);
    }

    /**
     * Creates a sanitized copy of the ticket codes by removing all whitespace.
     *
     * This method does not modify the original array; it returns a new array
     * where each ticket code has had all whitespace characters removed.
     *
     * @param array<int, string> $ticketCodes Array of original ticket codes.
     *
     * @return array<int, string> Sanitized ticket codes with whitespace removed.
     */
    protected function sanitizeTicketCodes(array $ticketCodes): array
    {
        return array_map(
            static function (string $ticket): string {
                $result = preg_replace('/\s+/', '', $ticket);
                return $result !== null ? $result : '';
            },
            $ticketCodes
        );
    }
}
