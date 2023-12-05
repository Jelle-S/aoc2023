<?php

namespace Jelle_S\AOC\AOC2023\Day05;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $almanac = [];
    $curMap = [];
    $seeds = array_map('intval', explode(' ', str_replace('seeds: ', '', trim(fgets($h)))));
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      if (!$line) {
        continue;
      }
      $matches = [];
      if (preg_match('/([^-]+)-to-([^ ]+)/', $line, $matches)) {
        if ($curMap) {
          $almanac[] = $curMap;
        }
        $curMap = [
        ];
        continue;
      }

      $curMap[] = array_map('intval', explode(' ', $line));
    }
    $almanac[] = $curMap;
    fclose($h);

    $result = $this->closestSeedLocation($seeds, $almanac);

    return $result;
  }

  protected function closestSeedLocation(array $seeds, array $almanac) {
    $location = false;
    foreach ($seeds as $seed) {
      $potential = $this->getDestination($seed, $almanac);
      $location = $location === false
        ? $potential
        : min($location, $potential);
    }
    return $location;
  }

  protected function getDestination(int $item, array $almanac) {
    $destinationMaps = array_shift($almanac);

    foreach ($destinationMaps as $destinationMap) {
      $rangeStart = $destinationMap[1];
      $rangeEnd = $rangeStart + $destinationMap[2] - 1;
      if ($item >= $rangeStart && $item <= $rangeEnd) {
        $item = $item - $destinationMap[1] + $destinationMap[0];
        break;
      }
    }

    return $almanac ?
      $this->getDestination($item, $almanac)
      : $item;
  }
}
