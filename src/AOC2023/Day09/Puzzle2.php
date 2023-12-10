<?php

namespace Jelle_S\AOC\AOC2023\Day09;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $sequence = array_map('intval', explode(' ', $line));
      $result += $this->sequenceStart($sequence);
    }
    fclose($h);

    return $result;
  }

  protected function sequenceStart(array $sequence) {
    $firstNumbers = [reset($sequence)];
    while (true) {
      $diffs = [];
      for ($i = 1; $i < count($sequence); $i++) {
        $diffs[] = $sequence[$i] - $sequence[$i - 1];
      }
      $sequence = $diffs;
      $firstNumbers[] = reset($diffs);
      if (count(array_unique($diffs)) === 1) {
        break;
      }
    }
    for ($i = count($firstNumbers) - 1; $i > 0; $i--) {
      $firstNumbers[$i - 1] = $firstNumbers[$i - 1] - $firstNumbers[$i];
    }
    return $firstNumbers[0];
  }
}