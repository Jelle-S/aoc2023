<?php

namespace Jelle_S\AOC\AOC2023\Day14;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $input = file_get_contents($this->input);
    $rows = array_filter(array_map('trim', explode("\n", $input)));
    $numRows = count($rows);
    $tiltedY = array_fill(0, strlen($rows[0]), 0);
    $y = 0;

    foreach ($rows as $row) {
      foreach (str_split($row) as $x => $pos) {
        if ($pos === '#') {
          $tiltedY[$x] = $y + 1;
        }
        if ($pos === 'O') {
          $result += $numRows - $tiltedY[$x];
          $tiltedY[$x]++;
        }
      }
      $y++;
    }

    return $result;
  }
}
