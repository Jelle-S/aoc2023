<?php

namespace Jelle_S\AOC\AOC2023\Day17;

class Puzzle2 extends Puzzle1 {

  protected function shortestDistance($start, $destination) {
    $visited = new \Ds\Set();

    $queue = new \SplPriorityQueue();

    $s = [$start, [0, 0], 0, 0];

    $queue->insert($s, 0);

    $directions = [
      [0, 1],
      [1, 0],
      [0, -1],
      [-1, 0],
    ];

    while(!$queue->isEmpty()) {
      list(list($y, $x), list($dy, $dx), $steps, $distance) = $queue->extract();
      if ($destination === [$y, $x]) {
        return $distance;
      }

      if ($visited->contains([$y, $x, $dx, $dy, $steps])) {
        continue;
      }

      $visited->add([$y, $x, $dx, $dy, $steps]);

      if ($steps < 10 && ($dx !== 0 || $dy !== 0) && isset($this->grid[$y + $dy][$x + $dx])) {
        $newDistance = $distance + $this->grid[$y + $dy][$x + $dx];
        $neighbour = [$y + $dy, $x + $dx];
        $queue->insert([$neighbour, [$dy, $dx], $steps + 1, $newDistance], -$newDistance);
      }

      // Greater than 0 because we need to exclude the starting point.
      if ($steps < 4 && $steps > 0) {
        continue;
      }

      foreach ($directions as $direction) {
        list($ndy, $ndx) = $direction;
        if ($direction === [$dy, $dx] || [-$ndy, -$ndx] === [$dy, $dx]) {
          continue;
        }
        if (!isset($this->grid[$y + $ndy][$x + $ndx])) {
          continue;
        }
        $newDistance = $distance + $this->grid[$y + $ndy][$x + $ndx];
        $neighbour = [$y + $ndy, $x + $ndx];
        $queue->insert([$neighbour, [$ndy, $ndx], 1, $newDistance], -$newDistance);
      }
    }
  }
}