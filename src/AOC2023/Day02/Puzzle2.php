<?php

namespace Jelle_S\AOC\AOC2023\Day02;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $parts = explode(': ', $line);
      $gameId = (int) str_replace('Game ', '', $parts[0]);
      $result += $this->calculateGamePower($parts[1]);
    }
    fclose($h);

    return $result;
  }

  protected function calculateGamePower(string $game) {
    $cubeAmounts = [
      'red' => 0,
      'green' => 0,
      'blue' => 0,
    ];
    foreach (explode('; ', $game) as $turn) {
      $cubes = explode(', ', $turn);
      foreach ($cubes as $cube) {
        list($amout, $color) = explode(' ', $cube);
        $cubeAmounts[$color] = max($cubeAmounts[$color], (int)$amout);
      }
    }
    return $cubeAmounts['red'] * $cubeAmounts['green'] * $cubeAmounts['blue'];
  }
}
