<?php

namespace Jelle_S\AOC\AOC2023\Day13;

class Puzzle2 extends Puzzle1 {

  // A pattern is mirrored when we have exactly one mismatch (smudge).
  protected function isMirroredAt($pattern, $col) {
    $mismatches = 0;
    foreach ($pattern as $row) {
      $mismatches += $this->getRowMirrorMismatches($row, $col);
      if ($mismatches > 1) {
        return false;
      }
    }
    return $mismatches === 1;
  }

  protected function getRowMirrorMismatches($row, $col) {
    $minlenght = min($col, count($row) - $col);
    $left = array_slice($row, $col - $minlenght, $minlenght);
    $right = array_reverse(array_slice(array_slice($row, $col), 0, $minlenght));
    if (!$left || !$right) {
      return false;
    }
    $mismatches = 0;
    foreach($left as $index => $l) {
      if ($l !== $right[$index]) {
        $mismatches++;
        if ($mismatches > 1) {
          return $mismatches;
        }
      }
    }
    return $mismatches;
  }
}