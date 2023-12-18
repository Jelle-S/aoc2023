<?php

namespace Jelle_S\AOC\AOC2023\Day18;

class Puzzle2 extends Puzzle1 {

  protected $puzzle1Map = [
    '0' => 'R',
    '1' => 'D',
    '2' => 'L',
    '3' => 'U',
  ];

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    // Add a starting point
    $this->trenches[0][0] = true;
    $currentPos = [0, 0];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $instruction = explode(' ', $line)[2];
      $direction = $this->puzzle1Map[substr($instruction, -2, -1)];
      $amount = hexdec(substr($instruction, 2, -2));
      $newPos = $this->updatePos($currentPos, $direction, $amount);
      $this->updateRows($currentPos, $newPos);
      $currentPos = $newPos;
    }
    fclose($h);
    ksort($this->trenches);
    foreach ($this->trenches as &$row) {
      ksort($row);
    }
    $this->floodFill();

    $result = array_sum(array_map('count', $this->trenches));

    return $result;
  }
}
