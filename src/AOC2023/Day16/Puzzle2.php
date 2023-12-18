<?php

namespace Jelle_S\AOC\AOC2023\Day16;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $this->grid[] = str_split($line);
    }
    fclose($h);
    foreach ($this->getStartingConfigurations() as $direction => $tiles) {
      foreach ($tiles as $tile) {
        $this->energized = [];
        $this->done = [];
        $this->shine($tile, $direction);
        $result = max($result, count($this->energized) - 1);
      }
    }

    return $result;
  }

  protected function getStartingConfigurations() {
    $configs = [];
    $columns = count($this->grid[0]);
    $rows = count($this->grid);
    for ($i = 0; $i < $rows; $i++) {
      $configs[static::RIGHT][] = [$i, -1];
      $configs[static::LEFT][] = [$i, $columns];
    }

    for ($i = 0; $i < $columns; $i++) {
      $configs[static::DOWN][] = [-1, $i];
      $configs[static::UP][] = [$rows, $i];
    }

    return $configs;
  }
}