<?php

namespace Jelle_S\AOC\AOC2023\Day04;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $matches = [];
      preg_match_all('/Card\s+\d+:((\s+(\d+))+)\s+\|((\s+(\d+))+)/', $line, $matches);
      $card = [
        explode(' ', preg_replace('/\s+/', ' ' , trim($matches[1][0]))),
        explode(' ', preg_replace('/\s+/', ' ', trim($matches[4][0]))),
      ];

      $winners = count(array_intersect($card[0], $card[1]));
      $result += $winners ? 2**($winners-1) : 0;
    }
    fclose($h);

    return $result;
  }
}
