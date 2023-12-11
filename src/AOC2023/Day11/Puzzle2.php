<?php

namespace Jelle_S\AOC\AOC2023\Day11;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $galaxies = [];
    $first = true;
    $emptyColumns = [];
    $y = 0;
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      if ($first) {
        $emptyColumns = range(0, strlen($line) - 1);
        $first = false;
      }
      $row = str_split($line);
      foreach($row as $x => $char) {
        if ($char === '#') {
          unset($emptyColumns[$x]);
          $galaxies[] = [$y, $x];
        }
      }
      if (strpos($line, '#') === false) {
        $y+= 999999;
      }
      $y++;
    }
    fclose($h);

    foreach ($galaxies as $key => $coordinates) {
      $galaxies[$key][1] += (count(array_filter($emptyColumns, fn($x) => $x < $coordinates[1])) * 999999);
    }

    for ($i = 0; $i < count($galaxies) - 1; $i++) {
      for ($j = $i+1; $j < count($galaxies); $j++) {
        $result += $this->calculateDistance($galaxies[$i], $galaxies[$j]);
      }
    }

    return $result;
  }
}