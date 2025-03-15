<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Exceptions;

class PickerException extends \RuntimeException
{
    public static function missingCharacters(int $pos): self
    {
        return new self("No characters available at position $pos. Check your ticket codes.");
    }

    public static function whitespaceInTicketCode(): self
    {
        return new self('Ticket codes must not be empty or consist solely of whitespace.');
    }

    public static function invalidTicketCodeLength(): self
    {
        return new self('Ticket codes must all be the same length after removing whitespace.');
    }

    public static function missingTicketCode(): self
    {
        return new self('No ticket codes provided.');
    }
}