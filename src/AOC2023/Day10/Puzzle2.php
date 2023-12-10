<?php

namespace Jelle_S\AOC\AOC2023\Day10;

class Puzzle2 extends Puzzle1 {

  protected $acceptedMovesFrom = [
    '-' => [self::LEFT, self::RIGHT],
    '7' => [self::LEFT, self::DOWN],
    'J' => [self::LEFT, self::UP],
    'L' => [self::RIGHT, self::UP],
    'F' => [self::RIGHT, self::DOWN],
    '|' => [self::UP, self::DOWN],
    'S' => [self::UP, self::DOWN, self::LEFT, self::RIGHT],
    '.' => [self::UP, self::DOWN, self::LEFT, self::RIGHT],
  ];

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $grid = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $grid[] = str_split($line);
      if (str_contains($line, 'S')) {
        $start = [count($grid) - 1, strpos($line, 'S')];
      }
    }
    fclose($h);

    // Find the circle.
    $circle = $this->findCircle($start, $grid);

    // Get the outside bounds of the circle.
    $bounds = $this->getBounds($circle);

    // Loop over every point within the bounds and see if it's within the circle
    // Starting top left.
    foreach ($this->pointsWithin($bounds) as $point) {
      if ($this->contains($circle, $point, $bounds, $grid)) {
        $result++;
      }
    }

    return $result;
  }

  protected function getBounds($circle) {
    $minx = $miny = PHP_INT_MAX;
    $maxx = $maxy = -1;

    foreach (array_keys($circle) as $coords) {
      list($y, $x) = array_map('intval', explode('|', $coords));
      $minx = min($minx, $x);
      $miny = min($miny, $y);
      $maxx = max($maxx, $x);
      $maxy = max($maxy, $y);
    }

    return [[$miny, $minx], [$maxy, $maxx]];
  }

  protected function pointsWithin($bounds) {
    $points = [];
    for ($y = $bounds[0][0]; $y <= $bounds[1][0]; $y++) {
      for ($x = $bounds[0][1]; $x <= $bounds[1][1]; $x++) {
        $points[] = [$y, $x];
      }
    }

    return $points;
  }

  protected function contains($circle, $point, $bounds, $grid) {
    // If the point is part of the circle, the circle doesn't contain it.
    if (isset($circle[implode('|', $point)])) {
      return false;
    }
    // Draw a line to the edge. Even number of intersections = outside of circle
    $intersections = 0;
    while ($point[0] <= $bounds[1][0]) {
      $previousPoint = $point;
      $point[0]++;
      // If the next point down is not part of the circle, continue;
      if (!isset($circle[implode('|', $point)])) {
        continue;
      }
      // The line intersects if the move from the previous point
      // is not possible.
      if (!$this->isMovePossible(static::DOWN, $previousPoint, $grid)) {
        var_dump($previousPoint);
        $intersections++;
      }
    }
    var_dump($intersections); exit;
    return (bool) $intersections % 2;
  }
}