<?php

namespace Jelle_S\AOC\AOC2023\Day02;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $max = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
  ];

  public function __construct(protected string $input) {

  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $parts = explode(': ', $line);
      $gameId = (int) str_replace('Game ', '', $parts[0]);
      $result += $this->isGamePossible($parts[1]) ? $gameId : 0;
    }
    fclose($h);

    return $result;
  }

  protected function isGamePossible(string $game) {
    foreach (explode('; ', $game) as $turn) {
      $cubes = explode(', ', $turn);
      foreach ($cubes as $cube) {
        list($amout, $color) = explode(' ', $cube);
        if ($this->max[$color] < (int) $amout) {
          return false;
        }
      }
    }
    return true;
  }
}
