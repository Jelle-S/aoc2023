<?php

namespace Jelle_S\AOC\AOC2023\Day09;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $sequence = array_map('intval', explode(' ', $line));
      $result += $this->sequenceEnd($sequence);
    }
    fclose($h);

    return $result;
  }

  protected function sequenceEnd(array $sequence) {
    $lastNumbers = [end($sequence)];
    while (true) {
      $diffs = [];
      for ($i = 1; $i < count($sequence); $i++) {
        $diffs[] = $sequence[$i] - $sequence[$i - 1];
      }
      $sequence = $diffs;
      $lastNumbers[] = end($diffs);
      if (count(array_unique($diffs)) === 1) {
        break;
      }
    }
    for ($i = count($lastNumbers) - 1; $i > 0; $i--) {
      $lastNumbers[$i - 1] = $lastNumbers[$i] + $lastNumbers[$i - 1];
    }

    return $lastNumbers[0];
  }
}