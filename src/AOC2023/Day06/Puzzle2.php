<?php

namespace Jelle_S\AOC\AOC2023\Day06;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $lines = explode("\n", file_get_contents($this->input));
    $time = intval(str_replace(' ' , '', explode(':', $lines[0])[1]));
    $distanceToBeat = intval(str_replace(' ' , '', explode(':', $lines[1])[1]));

    for ($i = 1; $i < $time; $i++) {
      $speed = $buttonTime = $i;
      $remainingTime = $time - $buttonTime;
      if ($speed * $remainingTime > $distanceToBeat) {
        $result++;
      }
    }

    return $result;
  }
}
