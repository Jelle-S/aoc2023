<?php

namespace Jelle_S\AOC\AOC2023\Day10;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  const DOWN = [1, 0];
  const UP = [-1, 0];
  const LEFT = [0, -1];
  const RIGHT = [0, 1];

  protected $acceptedMovesFrom = [
    '-' => [self::LEFT, self::RIGHT],
    '7' => [self::LEFT, self::DOWN],
    'J' => [self::LEFT, self::UP],
    'L' => [self::RIGHT, self::UP],
    'F' => [self::RIGHT, self::DOWN],
    '|' => [self::UP, self::DOWN],
    'S' => [self::UP, self::DOWN, self::LEFT, self::RIGHT],
  ];

  public function __construct(protected string $input) {
  }

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

    $circle = $this->findCircle($start, $grid);

    $result  = floor(count($circle) / 2);
    return $result;
  }

  protected function findCircle($start, $grid) {
    // Pipes have only one way in, and one way out. Since we're coming from one
    // way, there's only one way we _can_ go, so... Pick a direction, and go?
    $destination = $start;
    $start = $this->nextNeighbour($destination, $grid, []);
    $visited = [];
    $visited[implode('|', $destination)] = true;
    $visited[implode('|', $start)] = true;
    $current = $start;

    while (true) {
      $neighbour = $this->nextNeighbour($current, $grid, $visited);
      // No more neighbours, we reached the end/beginning of the circle.
      if (!$neighbour) {
        return $visited;
      }
      $current = $neighbour;
      $visited[implode('|', $current)] = true;
    }
  }

  protected function nextNeighbour($location, $grid, $visited) {
    $moves = [static::UP, static::RIGHT, static::DOWN, static::LEFT];

    foreach ($moves as $move) {
      if ($this->isMovePossible($move, $location, $grid)) {
        $neighbour = [$location[0] + $move[0], $location[1] + $move[1]];
        if (isset($visited[implode('|', $neighbour)])) {
          continue;
        }
        return $neighbour;
      }
    }
  }

  protected function isMovePossible($move, $location, $grid) {
    if (!in_array($move, $this->acceptedMovesFrom[$grid[$location[0]][$location[1]]])) {
      return false;
    }

    $newLocation = [$location[0] + $move[0], $location[1] + $move[1]];
    $inverseMove = [-$move[0], -$move[1]];

    if (!isset($grid[$newLocation[0]]) || !isset($grid[$newLocation[0]][$newLocation[1]])) {
      return false;
    }

    if ($grid[$newLocation[0]][$newLocation[1]] === '.') {
      return false;
    }

    // If the inverse move is not possible on the new location, we can't go to
    // this location.
    if (!in_array($inverseMove, $this->acceptedMovesFrom[$grid[$newLocation[0]][$newLocation[1]]])) {
      return false;
    }

    return true;
  }

}