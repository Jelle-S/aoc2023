<?php

namespace Jelle_S\AOC\AOC2023\Day12;

class Puzzle2 extends Puzzle1 {

  protected $cache = [];

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list($mask, $brokenNums) = explode(' ', $line);
      $brokenNums = array_map('intval', explode(',', implode(',', array_fill(0, 5, $brokenNums))));
      $mask = implode('?', array_fill(0, 5, $mask));
      $combos = $this->countCombos($mask, $brokenNums);
      $result += $combos;

    }
    fclose($h);

    return $result;
  }

  protected function countCombos(string $mask, array $brokenNums) {
    $key = $mask . '&' . implode('|', $brokenNums);
    if (!array_key_exists($key, $this->cache)) {
      $this->cache[$key] = parent::countCombos($mask, $brokenNums);
    }
    return $this->cache[$key];
  }
}