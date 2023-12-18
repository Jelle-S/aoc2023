<?php

namespace Jelle_S\AOC\AOC2023\Day16;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected $grid = [];

  const DOWN = 'down';
  const UP = 'up';
  const LEFT = 'left';
  const RIGHT = 'right';

  protected $deltas = [
    self::DOWN => [1, 0],
    self::UP => [-1, 0],
    self::LEFT => [0, -1],
    self::RIGHT => [0, 1],
  ];

  protected $energized = [];

  protected $done = [];


  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $this->grid[] = str_split($line);
    }
    fclose($h);

    $this->shine([0, -1], static::RIGHT);

    $result = count($this->energized) - 1;

    return $result;
  }

  protected function shine($start, $direction) {
    if (isset($this->done[implode('|', $start) . $direction])) {
      return;
    }
    $this->done[implode('|', $start) . $direction] = true;
    list($dy, $dx) = $this->deltas[$direction];
    list($y, $x) = $start;
    while(true) {
      $this->energized[$y . '|' . $x] = true;
      $y += $dy;
      $x += $dx;
      if (!isset($this->grid[$y][$x])) {
        break;
      }
      switch ($this->grid[$y][$x]) {
        case '|':
          if ($direction === static::LEFT || $direction === static::RIGHT) {
            $this->shine([$y, $x], static::UP);
            $this->shine([$y, $x], static::DOWN);
            // Break switch and loop.
            break 2;
          }
          break;
        case '-':
          if ($direction === static::UP || $direction === static::DOWN) {
            $this->shine([$y, $x], static::LEFT);
            $this->shine([$y, $x], static::RIGHT);
            // Break switch and loop.
            break 2;
          }
          break;

        case '/':
          if ($direction === static::RIGHT) {
            $this->shine([$y, $x], static::UP);
            // Break switch and loop.
            break 2;
          }
          if ($direction === static::LEFT) {
            $this->shine([$y, $x], static::DOWN);
            // Break switch and loop.
            break 2;
          }
          if ($direction === static::UP) {
            $this->shine([$y, $x], static::RIGHT);
            // Break switch and loop.
            break 2;
          }
          if ($direction === static::DOWN) {
            $this->shine([$y, $x], static::LEFT);
            // Break switch and loop.
            break 2;
          }
          break;
        case '\\':
          if ($direction === static::RIGHT) {
            $this->shine([$y, $x], static::DOWN);
            // Break switch and loop.
            break 2;
          }
          if ($direction === static::LEFT) {
            $this->shine([$y, $x], static::UP);
            // Break switch and loop.
            break 2;
          }
          if ($direction === static::UP) {
            $this->shine([$y, $x], static::LEFT);
            // Break switch and loop.
            break 2;
          }
          if ($direction === static::DOWN) {
            $this->shine([$y, $x], static::RIGHT);
            // Break switch and loop.
            break 2;
          }
          break;
      }
    }
  }
}