<?php

namespace Jelle_S\AOC\AOC2023\Day06;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 1;

    $lines = explode("\n", file_get_contents($this->input));
    $times = array_values(array_map('intval', array_filter(explode(' ', explode(':', $lines[0])[1]))));
    $distances = array_values(array_map('intval', array_filter(explode(' ', explode(':', $lines[1])[1]))));

    foreach ($times as $key => $time) {
      $distanceToBeat = $distances[$key];
      $winners = 0;
      for ($i = 1; $i < $time; $i++) {
        $speed = $buttonTime = $i;
        $remainingTime = $time - $buttonTime;
        if ($speed * $remainingTime > $distanceToBeat) {
          $winners++;
        }
      }
      $result *= $winners;
    }

    return $result;
  }
}
