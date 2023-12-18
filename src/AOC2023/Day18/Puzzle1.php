<?php

namespace Jelle_S\AOC\AOC2023\Day18;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected $directions = [
    'R' => [0, 1],
    'L' => [0, -1],
    'U' => [-1, 0],
    'D' => [1, 0],
  ];

  protected $trenches = [];

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    // Add a starting point
    $this->trenches[0][0] = true;
    $currentPos = [0, 0];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list($direction, $amount) = explode(' ', $line);
      $amount = intval($amount);
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

  protected function updateRows($currentPos, $newPos) {
    $y1 = min($currentPos[0], $newPos[0]);
    $y2 = max($currentPos[0], $newPos[0]);
    $x1 = min($currentPos[1], $newPos[1]);
    $x2 = max($currentPos[1], $newPos[1]);
    for ($y = $y1; $y <= $y2; $y++) {
      for ($x = $x1; $x <= $x2; $x++) {
        $this->trenches[$y][$x] = true;
      }
    }
  }

  protected function updatePos($pos, $direction, $amount) {
    return [
      $pos[0] + ($amount * $this->directions[$direction][0]),
      $pos[1] + ($amount * $this->directions[$direction][1])
    ];
  }

  protected function floodFill() {
    // We need to find a starting point _within_ the trenches to start flood
    // filling.
    list($y, $x) = $this->findStartingPoint();
    $this->trenches[$y][$x] = true;
    $queue = [[$y, $x]];

    while($queue) {
      list($y, $x) = array_shift($queue);

      foreach ($this->directions as list($dy, $dx)) {
        $ny = $y + $dy;
        $nx = $x + $dx;
        if (!isset($this->trenches[$ny][$nx])) {
          $this->trenches[$ny][$nx] = true;
          $queue[] = [$ny, $nx];
        }
      }
    }
  }

  protected function findStartingPoint() {
    // We "walk" along the trench and keep checking _inward_ for a point that is
    // _not_ a trench and thus part of the trenches itself. Since we start by
    // walking right and we start at the top left, we need to check down for a
    // point. The direction we need to check changes when the trenches make a
    // turn.
    $y = min(array_keys($this->trenches));
    $x = min(array_keys($this->trenches[$y]));
    $walkingDirection = 'R';
    $checkingMap = [
      'R' => 'D',
      'D' => 'L',
      'L' => 'U',
      'U' => 'R',
    ];

    while (isset($this->trenches[$y][$x])) {
      list($wdy, $wdx) = $this->directions[$walkingDirection];
      list($cdy, $cdx) = $this->directions[$checkingMap[$walkingDirection]];
      $cy = $y + $cdy;
      $cx = $x + $cdx;
      if (!isset($this->trenches[$cy][$cx])) {
        return [$cy, $cx];
      }

      $ny = $y + $wdy;
      $nx = $x + $wdx;

      // Next step is no longer a trench? Turn!
      if (!isset($this->trenches[$cy][$cx])) {
        // Turn all directions except the current one or back.
        foreach ($this->directions as $dir => $deltas) {
          if ($dir == $walkingDirection || $deltas === [-$wdy, -$wdx]) {
            continue;
          }
          if (isset($this->trenches[$cy][$cx])) {
            $walkingDirection = $dir;
            list($wdy, $wdx) = $this->directions[$walkingDirection];
            $ny = $y + $wdy;
            $nx = $x + $wdx;
            break;
          }
        }
      }
      $x = $nx;
      $y = $ny;

    }


  }
}
