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
        throw new PickerException('No characters available at position 1. Check your ticket codes.');
    }

    public function testWhitespaceInTicketCode(): void
    {
        $this->expectExceptionMessage('Ticket codes must not be empty or consist solely of whitespace.');
        throw new PickerException('Ticket codes must not be empty or consist solely of whitespace.');
    }

    public function testInvalidLTicketCodeLength(): void
    {
        $this->expectExceptionMessage('Ticket codes must all be the same length after removing whitespace.');
        throw new PickerException('Ticket codes must all be the same length after removing whitespace.');
    }

    public function testMissingTicketCode(): void
    {
        $this->expectExceptionMessage('No ticket codes provided.');
        throw new PickerException('No ticket codes provided.');
    }
}