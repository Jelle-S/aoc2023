<?php

namespace Jelle_S\AOC\AOC2023\Day15;

class Puzzle2 extends Puzzle1 {

  protected $boxes = [];

  public function solve() {
    $result = 0;

    $line = str_replace(['=', '-'], ':', trim(file_get_contents($this->input)));
    $steps = array_map(fn ($x) => array_filter(explode(':', $x), fn ($x) => $x !== ""), explode(',', $line));

    foreach ($steps as $step) {
      $this->executeStep($step);
    }
    foreach($this->boxes as $boxNum => $box) {
      foreach (array_values($box) as $slotNum => $focalLength) {
        $result += ($boxNum + 1) * ($slotNum + 1) * $focalLength;
      }
    }

    return $result;
  }

  protected function executeStep(array $step) {
    switch(count($step)) {
      case 1:
        unset($this->boxes[$this->hash($step[0])][$step[0]]);
        break;
      case 2:
        $this->boxes[$this->hash($step[0])][$step[0]] = $step[1];
        break;
    }
  }
}
