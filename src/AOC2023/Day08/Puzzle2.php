<?php

namespace Jelle_S\AOC\AOC2023\Day08;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $steps = str_split(trim(fgets($h)));
    fgets($h);
    $nodes = [];
    $currentNodes = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list($node, $leftRight) = explode(' = ', $line);
      $nodes[$node] = array_map(fn ($x) => str_replace(['(', ')'], '', $x), explode(', ', $leftRight));
      if (str_ends_with($node, 'A')) {
        $currentNodes[] = $node;
      }
    }
    fclose($h);

    reset($nodes);

    $firstZs = [];
    foreach ($currentNodes as $key => $node) {
      $currentNode = $node;
      $stepCount = 0;
      while (!str_ends_with($currentNode, 'Z')) {
        $step = $steps[$stepCount % count($steps)];
        $currentNode = $nodes[$currentNode][$step === 'L' ? 0 : 1];
        $stepCount++;
      }
      $firstZs[] = $stepCount;
    }

    $result = array_pop($firstZs);
    foreach ($firstZs as $firstZ) {
      $result = $result * $firstZ / gmp_gcd($result, $firstZ);
    }

    return gmp_intval($result);
  }
}
