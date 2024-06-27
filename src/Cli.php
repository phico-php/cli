<?php

declare(strict_types=1);

namespace Phico\Cli;

abstract class Cli
{
    protected string $help = 'Usage: phico command method [args...]';


    /**
     * Handle unknown commands
     * @param string $method The method called
     * @param array $args The method arguments
     */
    public function __call(string $method, array $args = [])
    {
        $this->warning(sprintf("Unknown command '%s'\n", $method));
        $this->help();
        exit(1);
    }
    /**
     * Displays the contents of the help property.
     */
    public function help(Args $args): void
    {
        $this->write($this->help . "\n");
    }
    /**
     * Displays a prompt and waits for input
     */
    protected function prompt(string $prompt, array $choices = []): string
    {
        $input = readline($prompt);
        if (empty($choices)) {
            return strtolower($input);
        }

        while (!in_array($input, $choices)) {
            $input = strtolower(readline($prompt));
        }

        return $input;
    }
    /**
     * Writes the data as a table to the screen
     * @param array<string,mixed> $data The data to render
     * @param array<string,string> $columns The column names to display
     * @return void
     */
    protected function table(array $data, array $columns = []): void
    {
        // quick return if no data
        if (!isset($data[0])) {
            echo "No data to display\n";
            return;
        }
        // holds column widths
        $widths = [];
        // defines the column alignments
        $align = [];
        // if columns is empty then copy the first row of data keys
        if (empty($columns)) {
            $columns = array_keys($data[0]);
        }
        // set widths of column headings
        foreach ($columns as $k) {
            $widths[$k] = strlen($k);
        }
        // loop each row of data to update column widths
        foreach ($data as $row) {
            foreach ($row as $k => $v) {
                // check the column type to set alignment
                $align[$k] = match (\gettype($v)) {
                    'string' => '-',
                    default => ''
                };
                // check column exists and check the width of this cell
                if (in_array($k, $columns)) {
                    // then check the length of each value
                    if ($widths[$k] < strlen((string) $v)) {
                        $widths[$k] = strlen((string) $v);
                    }
                }
            }
        }
        // create widths mask
        $mask = [];
        // store total length for underline
        $len = 0;
        foreach ($columns as $str) {
            $mask[] = '%' . $align[$str] . $widths[$str] . 's';
            $len += $widths[$str] + 1;
        }
        $mask = join(' ', $mask);

        // output headings
        echo sprintf("$mask\n", ...$columns);
        // draw underline
        echo sprintf("%s\n", \str_repeat('=', $len));
        // output the data
        foreach ($data as $row) {
            // reset line array
            $line = [];
            // filter by columns
            // @TODO set order correctly here
            foreach ($row as $k => $v) {
                if (in_array($k, $columns)) {
                    $line[] = $v;
                }
            }
            // print the filtered values
            if (!empty($line)) {
                echo sprintf("$mask\n", ...$line);
            }
        }

    }
    /**
     * Displays a title with an underline
     * @param string $title The title string
     * @return void
     */
    protected function title(string $str): void
    {
        echo sprintf(
            "\n%s\n%s\n\n",
            $str,
            str_repeat('=', strlen($str))
        );
    }
    /**
     * Writes a string to standard output, optionally coloured, optionaly without newline
     * @param string $str The string to write
     * @param bool $newline If true then a newline will be appended
     * @param string $colour The colour of the text
     * @return void
     */
    protected function write(string $str, bool $newline = true, string $colour = null): void
    {
        $code = match ($colour) {
            'black' => '30m',
            'red' => '31m',
            'green' => '32m',
            'yellow' => '33m',
            'blue' => '34m',
            'magenta' => '35m',
            'cyan' => '36m',
            'white' => '37m',
            default => '39m'
        };

        echo sprintf("\033[%s%s\033[39m%s", $code, $str, ($newline) ? "\n" : "");
    }

    protected function error(string $str, bool $newline = true)
    {
        $this->write("\n$str\n", $newline, 'red');
    }
    protected function info(string $str, bool $newline = true)
    {
        $this->write($str, $newline, 'cyan');
    }
    protected function success(string $str, bool $newline = true)
    {
        $this->write($str, $newline, 'green');
    }
    protected function warning(string $str, bool $newline = true)
    {
        $this->write($str, $newline, 'yellow');
    }
}
