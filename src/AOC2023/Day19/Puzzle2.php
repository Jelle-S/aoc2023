<?php

namespace Jelle_S\AOC\AOC2023\Day19;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);

    }
    fclose($h);

    return $result;
  }
}