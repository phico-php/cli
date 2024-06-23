<?php

declare(strict_types=1);

namespace Phico\Cli;

class Args
{
    private string $cmd;
    private array $short_flags = [];
    private array $long_flags = [];
    private array $args = [];
    private array $values = [];


    public function __construct(array $args)
    {
        $this->cmd = $args[0] ?? '';
        array_shift($args);
        foreach ($args as $str) {
            if (str_starts_with($str, '--')) {
                $str = substr($str, 2);
                if (str_contains($str, '=')) {
                    $parts = explode('=', $str);
                    $this->long_flags[] = trim($parts[0]);
                    $this->values[trim($parts[0])] = trim($parts[1]);
                } else {
                    $this->long_flags[] = $str;
                }
            } elseif (str_starts_with($str, '-')) {
                $str = substr($str, 1);
                if (str_contains($str, '=')) {
                    $parts = explode('=', $str);
                    $this->short_flags[] = trim($parts[0]);
                    $this->values[trim($parts[0])] = trim($parts[1]);
                } elseif (strlen($str) > 1) {
                    foreach (str_split($str) as $char) {
                        $this->short_flags[] = $char;
                    }
                } else {
                    $this->short_flags[] = $str;
                }
            } else {
                $this->args[] = trim($str);
            }
        }
    }
    // check that a named flag was provided
    public function has(string $str): bool
    {
        // check args first
        if (in_array($str, $this->args)) {
            return true;
        }

        // check flags
        return (strlen($str) === 1)
            ? in_array($str, $this->short_flags)
            : in_array($str, $this->long_flags);
    }
    // returns the command
    public function cmd(): string
    {
        return $this->cmd;
    }
    // returns the argument at the index (0 based)
    public function index(int $index, mixed $default = null): mixed
    {
        return $this->args[$index] ?? $default;
    }
    // returns the number of arguments (excluding flags and the command)
    public function count(): int
    {
        return count($this->args);
    }
    // returns a value
    public function value(string $str, mixed $default = null): mixed
    {
        if (array_key_exists($str, $this->values)) {
            return $this->values[$str];
        }
        return $default;
    }
    // ensure that flags or arguments are provided
    public function require(array $required): void
    {
        // ensures that all elements are in the array
    }
    // swap arguments at position
    public function swap(int $a, int $b): void
    {
        if (count($this->args) >= 2) {
            // Swap the first and second elements using list destructuring
            [$this->args[$a], $this->args[$b]] = [$this->args[$b], $this->args[$a]];
        }
    }
    // remove an argument from the stack at index
    public function remove(int $index): void
    {
        unset($this->args[$index]);
        $this->args = array_values($this->args);
    }
    public function shift(int $count): void
    {
        for ($i = $count; $i > 0; $i--) {
            array_shift($this->args);
        }
    }
}
