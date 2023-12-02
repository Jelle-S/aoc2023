<?php

namespace Jelle_S\AOC\AOC2023\Day01;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $digits = preg_replace('/([^\d])+/', '', trim($line));
      $result += (int) ($digits[0] . $digits[-1]);
    }
    fclose($h);

    return $result;
  }
}
