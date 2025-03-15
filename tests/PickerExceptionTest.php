<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Tests;

use AppEngine\TicketPicker\Exceptions\PickerException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(\AppEngine\TicketPicker\Exceptions\PickerException::class)] class PickerExceptionTest extends TestCase
{
    public function testMissingCharacters(): void
    {
        $this->expectExceptionMessage('No characters available at position 1. Check your ticket codes.');
        throw PickerException::missingCharacters(1);
    }

    public function testWhitespaceInTicketCode(): void
    {
        $this->expectExceptionMessage('Ticket codes must not be empty or consist solely of whitespace.');
        throw PickerException::whitespaceInTicketCode();
    }

    public function testInvalidLTicketCodeLength(): void
    {
        $this->expectExceptionMessage('Ticket codes must all be the same length after removing whitespace.');
        throw PickerException::invalidTicketCodeLength();
    }

    public function testMissingTicketCode(): void
    {
        $this->expectExceptionMessage('No ticket codes provided.');
        throw PickerException::missingTicketCode();
    }
}