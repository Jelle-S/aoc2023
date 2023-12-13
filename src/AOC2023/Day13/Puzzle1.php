<?php

namespace Jelle_S\AOC\AOC2023\Day13;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $pattern = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      if (!$line) {
        $result += $this->reflectionFactor($pattern);
        $pattern = [];
        continue;
      }
      $pattern[] = str_split($line);
    }
    fclose($h);
    $result += $this->reflectionFactor($pattern);
    return $result;
  }

  protected function reflectionFactor(array $pattern) {
    return $this->reflectionColumnsLeft($pattern) + (100 * $this->reflectionRowsAbove($pattern));
  }

  protected function reflectionColumnsLeft(array $pattern) {
    $middle = count($pattern[0]) / 2;
    $deltas = [1, -1];
    for ($i = 0; $i <= $middle; $i++) {
      foreach ($deltas as $delta) {
        $col = ($delta ? ceil($middle) : floor($middle)) + ($delta * $i);
        if ($this->isMirroredAt($pattern, $col)) {
          return $col;
        }
      }
    }

    return 0;
  }

  protected function reflectionRowsAbove(array $pattern) {
    $transformed = [];
    foreach (array_keys($pattern[0]) as $col) {
      $transformed[] = array_column($pattern, $col);
    }
    return $this->reflectionColumnsLeft($transformed);
  }

  protected function isMirroredAt($pattern, $col) {
    foreach ($pattern as $row) {
      if (!$this->isRowMirroredAt($row, $col)) {
        return false;
      }
    }
    return true;
  }

  protected function isRowMirroredAt($row, $col) {
    $minlenght = min($col, count($row) - $col);
    $left = array_slice($row, $col - $minlenght, $minlenght);
    $right = array_reverse(array_slice(array_slice($row, $col), 0, $minlenght));
    if (!$left || !$right) {
      return false;
    }
    $mirrored = true;
    foreach($left as $index => $l) {
      if ($l !== $right[$index]) {
        return false;
      }
    }
    return true;
  }
}