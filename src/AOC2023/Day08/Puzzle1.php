<?php

namespace Jelle_S\AOC\AOC2023\Day08;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $steps = str_split(trim(fgets($h)));
    fgets($h);
    $nodes = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list($node, $leftRight) = explode(' = ', $line);
      $nodes[$node] = array_map(fn ($x) => str_replace(['(', ')'], '', $x), explode(', ', $leftRight));
    }
    fclose($h);

    reset($nodes);

    $currentNode = 'AAA';

    while ($currentNode !== 'ZZZ') {
      $step = $steps[$result % count($steps)];
      $currentNode = $nodes[$currentNode][$step === 'L' ? 0 : 1];
      $result++;
    }

    return $result;
  }
}
