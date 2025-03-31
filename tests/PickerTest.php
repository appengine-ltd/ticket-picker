<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Tests;

use AppEngine\TicketPicker\Exceptions\PickerException;
use AppEngine\TicketPicker\Pickers\Picker;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

#[CoversClass(Picker::class)]
#[CoversClass(PickerException::class)]
class PickerTest extends TestCase
{
    /**
     * Test that generateCode produces a reproducible code when using the same seed.
     *
     * @throws Exception
     */
    public function testGenerateCodeReproducible(): void
    {
        $picker = new Picker();
        $ticketCodes = [
            'ABC123',
            'XYZ789',
            'LMN456',
        ];
        $seed = 'reproducible-seed';

        $code1 = $picker->generateCode($ticketCodes, $seed);
        $code2 = $picker->generateCode($ticketCodes, $seed);

        $this->assertSame($code1, $code2, 'The generated code should be reproducible with the same seed.');
    }

    public function testGeneratedCodePicksCorrectlyNearLimit(): void
    {
        $picker = new Picker();
        $seed = 'random';

        $pool = [
            'ABCDEF',
            'ABCDEG',
            'ABCDEH',
        ];

        $code = $picker->generateCode($pool, $seed);

        $this->assertEquals('ABCDEH', $code);
    }

    /**
     * Test that generateCode correctly sanitizes ticket codes (removes whitespace).
     *
     * @throws Exception
     */
    public function testSanitizationRemovesWhitespace(): void
    {
        $picker = new Picker();
        $ticketCodes = [
            'A B C',
            '1 2 3',
            'X Y Z',
        ];
        $seed = 'seed';

        // After sanitization, codes become "ABC", "123", "XYZ" (length = 3).
        $code = $picker->generateCode($ticketCodes, $seed);
        $this->assertEquals(3, strlen($code), 'The generated code length should equal the sanitized code length.');
    }

    /**
     * Test that generateCode throws an exception if ticket codes have different lengths after sanitization.
     */
    public function testDifferentLengthThrowsException(): void
    {
        $picker = new Picker();
        $ticketCodes = [
            'ABC',
            'DE F', // becomes "DEF" (length = 3)
            'GH',   // length = 2
        ];
        $this->expectException(Exception::class);
        $picker->generateCode($ticketCodes, 'seed');
    }

    /**
     * Test that generateCode throws an exception if any ticket code becomes empty after sanitization.
     */
    public function testEmptyTicketAfterSanitizationThrowsException(): void
    {
        $picker = new Picker();
        $ticketCodes = [
            '   ', // becomes empty
            'ABC',
            'XYZ',
        ];
        $this->expectException(Exception::class);
        $picker->generateCode($ticketCodes, 'seed');
    }

    /**
     * Test that generateCode throws an exception when provided an empty ticket codes array.
     */
    public function testEmptyTicketCodesArrayThrowsException(): void
    {
        $picker = new Picker();
        $this->expectException(Exception::class);
        $picker->generateCode([], 'seed');
    }

    /**
     * Test convertSeedToInteger returns an integer.
     *
     * @throws ReflectionException
     */
    public function testConvertSeedToIntegerReturnsInteger(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'convertSeedToInteger');
        $seedInt = $method->invoke($picker, 'myseed');
        $this->assertIsInt($seedInt, 'convertSeedToInteger() should return an integer.');
    }

    /**
     * Test getRandomCharacter returns one of the provided candidates.
     *
     * @throws ReflectionException
     */
    public function testGetRandomCharacter(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'getRandomCharacter');
        $candidates = ['A', 'B', 'C'];
        $char = $method->invoke($picker, $candidates);
        $this->assertContains($char, $candidates, 'getRandomCharacter() should return one of the candidates.');
    }

    /**
     * Test getCharactersAtPosition returns the unique characters at a given position.
     *
     * @throws ReflectionException
     */
    public function testGetCharactersAtPosition(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'getCharactersAtPosition');
        $ticketCodes = ['ABC', 'DEF', 'GHI'];
        // For position 1, expect: "B", "E", "H"
        $result = $method->invoke($picker, $ticketCodes, 1);
        $expected = ['B', 'E', 'H'];
        $this->assertEqualsCanonicalizing(
            $expected,
            $result,
            'getCharactersAtPosition() should return unique characters from the given position.'
        );
    }

    /**
     * Test getCodeLength returns the length of the ticket codes.
     *
     * @throws ReflectionException
     */
    public function testGetCodeLength(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'getCodeLength');
        $ticketCodes = ['ABC', 'DEF'];
        $length = $method->invoke($picker, $ticketCodes);
        $this->assertEquals(3, $length, 'getCodeLength() should return the length of the first ticket code.');
    }

    /**
     * Test sanitizeTicketCodes correctly removes all whitespace.
     *
     * @throws ReflectionException
     */
    public function testSanitizeTicketCodes(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'sanitizeTicketCodes');
        $ticketCodes = [' A B ', 'C   D', 'E F G'];
        $sanitized = $method->invoke($picker, $ticketCodes);
        $expected = ['AB', 'CD', 'EFG'];
        $this->assertSame(
            $expected,
            $sanitized,
            'sanitizeTicketCodes() should remove all whitespace from ticket codes.'
        );
    }

    /**
     * Test validateTicketCodes with valid input (should not throw an exception).
     *
     * @throws ReflectionException
     */
    public function testValidateTicketCodesValid(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'validateTicketCodes');
        $ticketCodes = ['ABC', 'DEF'];
        // Should not throw an exception.
        $method->invoke($picker, $ticketCodes);
        $this->assertTrue(true);
    }

    /**
     * Test validateTicketCodes with invalid input (different lengths) throws an exception.
     *
     * @throws ReflectionException
     */
    public function testValidateTicketCodesInvalid(): void
    {
        $picker = new Picker();
        $method = new ReflectionMethod($picker, 'validateTicketCodes');
        $ticketCodes = ['ABC', 'DE'];
        $this->expectException(Exception::class);
        $method->invoke($picker, $ticketCodes);
    }
}
