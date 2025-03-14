# TicketPicker for DrawEngine

TicketPicker is an open source PHP library used to pick winning ticket codes for the DrawEngine system. It ensures fairness and transparency through deterministic code generation using a cryptographically secure seed, along with robust input validation and sanitization. Every step of the process is fully auditable, allowing anyone to verify that the draw was conducted fairly.

## Features

- **Deterministic Code Generation:** Generates a winning code from a set of ticket codes and a seed string, ensuring reproducibility.
- **Cryptographically Secure Seed Generation:** Uses secure methods to generate random seed strings.
- **Input Sanitization and Validation:** Automatically removes whitespace and checks that all ticket codes are consistent.
- **Transparency & Auditability:** Fully open source, so the process can be independently verified.
- **Extensible:** Provides clear interfaces for future extensions.

## Requirements

- PHP 7.4 to PHP 8.3 (tested up to PHP 8.3; if using higher versions, you may need to bypass certain environment checks)
- Composer

## Installation

You can install the package via Composer:

```bash
composer require appengine/ticketpicker
```

Or clone the repository directly:

```bash
git clone https://github.com/yourusername/ticketpicker.git
cd ticketpicker
composer install
```

## Usage

### Generating a Winning Code

The main functionality is provided by the `Picker` class. Given an array of ticket codes and a seed string, the `Picker` will generate an 8-character winning code by selecting one character from each position of the sanitized ticket codes.

```php
<?php

use AppEngine\TicketPicker\Pickers\Picker;

require 'vendor/autoload.php';

$picker = new Picker();

$ticketCodes = [
    'ABCDEF12',
    'GHIJKL34',
    'MNOPQR56',
    'STUVWX78'
];

$seed = 'my-secure-seed'; // You can also generate a secure seed using the Seed class

$winningCode = $picker->generateCode($ticketCodes, $seed);

echo "Winning Code: " . $winningCode . PHP_EOL;
```

### Generating a Secure Seed

The `Seed` class provides a method to generate a cryptographically secure seed string.

```php
<?php

use AppEngine\TicketPicker\Generators\Seed;

require 'vendor/autoload.php';

$seedGenerator = new Seed();
$seed = $seedGenerator->generateSeed(16);

echo "Generated Seed: " . $seed . PHP_EOL;
```

## Testing

This project includes a comprehensive PHPUnit test suite to ensure 100% coverage. To run the tests:

```bash
composer test
```

Other useful commands include:

- **Static Analysis:**
  ```bash
  composer phpstan
  ```
- **Code Style Checks (Dry Run):**
  ```bash
  composer cs-check
  ```
- **Linting:**
  ```bash
  composer lint
  ```
- **Run All Checks:**
  ```bash
  composer run-script check-all
  ```

## Contributing

Contributions are welcome! Please feel free to open issues or submit pull requests. When contributing, make sure your changes follow our coding standards and that you include appropriate tests.

## License

This project is open source and available for non-commercial use. See the [LICENSE](LICENSE) file for more details.