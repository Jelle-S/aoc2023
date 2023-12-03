<?php

namespace Jelle_S\AOC\AOC2023\Day03;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    $grid = [];
    while (($line = fgets($h)) !== false) {
      $grid[] = $this->parseLine(trim($line));
    }

    $deltas = [[0, 1],[0, -1], [1, 0], [-1, 0], [1, 1], [-1, -1], [1, -1], [-1, 1]];

    foreach ($grid as $y => $row) {
      foreach ($row as $x => $item) {
        if (!is_numeric($item)) {
          continue;
        }

        foreach (range(0, strlen($item) - 1) as $offset) {
          $x2 = $x + $offset;
          foreach ($deltas as $delta) {
            list($dy, $dx) = $delta;
            if (isset($grid[$y + $dy][$x2 + $dx]) && !is_numeric($grid[$y + $dy][$x2 + $dx])) {
              $result += (int) $item;
              continue 3;
            }
          }
        }

      }
    }

    fclose($h);

    return $result;
  }

  protected function parseLine(string $line) {
    $row = [];
    $rowIndex = 0;
    $cell = [];
    $prevNumeric = false;
    foreach (str_split($line) as $index => $char) {
      if ($prevNumeric && is_numeric($char)) {
        $row[$rowIndex] .= $char;
        continue;
      }
      $rowIndex = $index;
      $prevNumeric = is_numeric($char);
      if ($char === '.') {
        continue;
      }
      $row[$index] = $char;
    }

    return $row;
  }
}
