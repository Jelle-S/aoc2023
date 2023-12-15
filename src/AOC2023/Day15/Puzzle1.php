<?php

namespace Jelle_S\AOC\AOC2023\Day15;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $line = trim(file_get_contents($this->input));

    $result = array_sum(array_map([$this, 'hash'], explode(',', $line)));

    return $result;
  }

  protected function hash(string $str) {
    $result = 0;

    foreach (str_split($str) as $char) {
      $result += ord($char);
      $result *= 17;
      $result %= 256;
    }
    return $result;
  }
}
