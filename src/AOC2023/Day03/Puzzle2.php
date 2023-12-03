<?php

namespace Jelle_S\AOC\AOC2023\Day03;

class Puzzle2 extends Puzzle1 {

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
        if ($item !== '*') {
          continue;
        }
        $adjacentNumbers = [];
        foreach ($deltas as $delta) {
          list($dy, $dx) = $delta;
          if (isset($grid[$y + $dy][$x + $dx]) && is_numeric($grid[$y + $dy][$x + $dx])) {
            $adjacentNumbers[] = implode('|', $this->getNumberCoordinates([$y + $dy, $x + $dx], $grid));
          }
        }
        $adjacentNumbers = array_unique($adjacentNumbers);
        if (count($adjacentNumbers) === 2) {
          $result += $this->getGearRatio(array_values($adjacentNumbers), $grid);
        }
      }
    }

    fclose($h);

    return $result;
  }

  protected function parseLine(string $line) {
    $row = [];
    $rowIndex = 0;
    foreach (str_split($line) as $index => $char) {
      $rowIndex = $index;
      $prevNumeric = is_numeric($char);
      if ($char !== '*' && !$prevNumeric) {
        continue;
      }
      $row[$index] = $char;
    }

    return $row;
  }

  protected function getNumberCoordinates($coords, $grid) {
    list ($y, $x) = $coords;

    while (isset($grid[$y][$x-1]) && is_numeric($grid[$y][$x-1])) {
      $x--;
    }

    return [$y, $x];
  }

  protected function getGearRatio($adjacentNumbers, $grid) {
    $ratio1 = $this->getFullNumber($adjacentNumbers[0], $grid);
    $ratio2 = $this->getFullNumber($adjacentNumbers[1], $grid);
    return  $ratio1 * $ratio2;
  }

  protected function getFullNumber($coords, $grid) {
    list($y, $x) = explode('|', $coords);

    $number = $grid[$y][$x];
    while (isset($grid[$y][$x+1]) && is_numeric($grid[$y][$x+1])) {
      $x++;
      $number .= $grid[$y][$x];
    }

    return (int) $number;
  }
}
