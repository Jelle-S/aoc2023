<?php

namespace Jelle_S\AOC\AOC2023\Day14;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $grid = [];
    $iterations = 1000000000;
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $grid[] = str_split($line);
    }
    fclose($h);

    // Keep a list of seen configurations, so we can detect recurring ones.
    $firstSeen = [];
    $recursionBetween = [];
    for ($i = 1; $i <= $iterations; $i++) {
      $grid = $this->cycle($grid);
      $key = $this->gridToString($grid);
      if (array_key_exists($key, $firstSeen)) {
        $recursionBetween = [$firstSeen[$key], $i];
        break;
      }
      $firstSeen[$this->gridToString($grid)] = $i;
    }

    $iterationsLeft = ($iterations - $recursionBetween[0]) % ($recursionBetween[1] - $recursionBetween[0]);

    for ($i = 0; $i < $iterationsLeft; $i++) {
      $grid = $this->cycle($grid);
    }
    $result = $this->calculateLoad($grid);
    return $result;
  }

  protected function cycle($grid) {
    for ($j = 0; $j < 4; $j++) {
      $grid =  $this->rotateRight($this->tiltNorth($grid));
    }

    return $grid;
  }

  protected function tiltNorth($grid) {
    $tiltedY = array_fill(0, count($grid[0]), 0);
    foreach ($grid as $y => $row) {
      foreach ($row as $x => $pos) {
        if ($pos === '#') {
          $tiltedY[$x] = $y + 1;
        }
        if ($pos === 'O') {
          if ($y !== $tiltedY[$x]) {
            $grid[$tiltedY[$x]][$x] = 'O';
            $grid[$y][$x] = '.';
          }
          $tiltedY[$x]++;
        }
      }
    }

    return $grid;
  }

  protected function rotateRight($grid) {
    return array_map(null, ...array_reverse($grid));
  }

  protected function gridToString($grid) {
    return implode("\n", array_map(fn($x) => implode('', $x), $grid));
  }

  protected function calculateLoad($grid) {
    $result = 0;

    $numRows = count($grid);

    foreach ($grid as $y => $row) {
      foreach ($row as $x => $pos) {
        if ($pos === 'O') {
          $result += $numRows - $y;
        }
      }
    }

    return $result;
  }
}
