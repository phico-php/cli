# CLI

Lightweight terminal support for [Phico](https://github.com/phico-php/phico)

## Installation

Using composer

```sh
composer require phico/cli
```

## Usage

CLI provides a handful of useful methods for terminal interaction.

It is useful when creating your own commands.

### Cli

#### Write a line to the terminal

```php
$input = write('Hello world');
```

##### Continue on the same line

```php
write(', this is Phico', $newline = false);
```

##### Colour support

Use error(), info(), success(), warning() to use different highlight colours.

#### Request user input

```php
$input = prompt('What is your name? ');
```

##### Limit choices in response

```php
// only one of red, blue or green is accepted
$input = prompt('What is your favourite colour? ', [
    'red',
    'blue',
    'green
]);
```

#### Write a title

```php
$input = title('This will be underlined');

// This will be underlined
// =======================
```

#### Draw a table

```php

$data = [
    ['Kermit', 'Green'],
    ['Fozzy Bear', 'Brown'],
    ['Miss Piggy', 'Pink'],
    ['Gonzo', 'Blue'],
];
$headings = [ 'Name', 'Colour' ];

$input = table($data, $headings);
```

## Args

Args handles terminal inout by organising it into flags (short or long), arguments and values.

### Flags

Specify single character flags with a single dash

```sh
phico -v
```

Multiple single flags can be set with a single dash

```sh
phico -vrt
```

Use has() to check if a flag is set

```sh
phico -vrt
```

```php
$args->has('v'); // true
$args->has('r'); // true
$args->has('t'); // true

$args->has('a'); // false
```

## Issues

CLI is considered feature complete, however if you discover any bugs or issues in it's behaviour or performance please create an issue, and if you are able a pull request with a fix.

Please make sure to update tests as appropriate.

For major changes, please open an issue first to discuss what you would like to change.

## License

[BSD-3-Clause](https://choosealicense.com/licenses/bsd-3-clause/)
