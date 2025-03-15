<?php

declare(strict_types=1);

namespace AppEngine\TicketPicker\Tests;

use AppEngine\TicketPicker\Generators\Seed;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Random\RandomException;

/**
 * Class SeedTest
 *
 * This class contains tests for the Seed class.
 *
 */
#[CoversClass(Seed::class)] class SeedTest extends TestCase
{
    private const SHOULD_BE_STRING = 'The seed should be a string.';

    /**
     * Test that generateSeed returns a string of default length (16).
     *
     * @throws RandomException
     */
    public function testGenerateSeedDefaultLength(): void
    {
        $seedGenerator = new Seed();
        $seed = $seedGenerator->generateSeed();
        $this->assertIsString($seed, self::SHOULD_BE_STRING);
        $this->assertEquals(16, strlen($seed), 'The default seed length should be 16 characters.');
    }

    /**
     * Test that generateSeed returns a string of a custom length.
     *
     * @throws RandomException
     */
    public function testGenerateSeedCustomLength(): void
    {
        $seedGenerator = new Seed();
        $length = 10;
        $seed = $seedGenerator->generateSeed($length);
        $this->assertIsString($seed, self::SHOULD_BE_STRING);
        $this->assertEquals($length, strlen($seed), 'The seed length should match the requested length.');
    }

    /**
     * Test that generateSeed works with the minimum length (1).
     *
     * @throws RandomException
     */
    public function testGenerateSeedMinimumLength(): void
    {
        $seedGenerator = new Seed();
        $seed = $seedGenerator->generateSeed(1);
        $this->assertIsString($seed, self::SHOULD_BE_STRING);
        $this->assertEquals(1, strlen($seed), 'The seed length should be 1.');
    }

    /**
     * Test that generateSeed throws an exception if the length is less than 1.
     *
     * @throws RandomException
     */
    public function testGenerateSeedInvalidLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $seedGenerator = new Seed();
        $seedGenerator->generateSeed(0);
    }

    /**
     * Test that multiple calls to generateSeed produce different values.
     *
     * @throws RandomException
     */
    public function testGenerateSeedMultipleCallsDifferent(): void
    {
        $seedGenerator = new Seed();
        $seed1 = $seedGenerator->generateSeed(16);
        $seed2 = $seedGenerator->generateSeed(16);
        $this->assertNotEquals($seed1, $seed2, 'Two different calls to generateSeed should produce different seeds.');
    }
}
