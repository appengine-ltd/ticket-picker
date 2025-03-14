<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Interfaces;

use Exception;

interface PickerInterface
{
    /**
     * Generate a code from the provided ticket codes using the given seed.
     *
     * @param array<int, string> $ticketCodes Array of ticket codes.
     * @param string $seed The seed string used for random generation.
     *
     * @return string The generated code.
     *
     * @throws Exception If validation fails or if no characters are available at any position.
     */
    public function generateCode(array $ticketCodes, string $seed): string;
}
