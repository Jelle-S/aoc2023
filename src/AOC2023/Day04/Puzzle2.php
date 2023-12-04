<?php

namespace Jelle_S\AOC\AOC2023\Day04;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $cards = [];
    $cardNum = 0;
    while (($line = fgets($h)) !== false) {
      $cards[$cardNum] = isset($cards[$cardNum]) ? $cards[$cardNum] + 1 : 1;
      $line = trim($line);
      $matches = [];
      preg_match_all('/Card\s+\d+:((\s+(\d+))+)\s+\|((\s+(\d+))+)/', $line, $matches);
      $card = [
        explode(' ', preg_replace('/\s+/', ' ' , trim($matches[1][0]))),
        explode(' ', preg_replace('/\s+/', ' ', trim($matches[4][0]))),
      ];

      $winners = count(array_intersect($card[0], $card[1]));
      for ($i = 1; $i<=$winners; $i++) {
          $cards[$cardNum+$i] = isset($cards[$cardNum+$i]) ? $cards[$cardNum+$i]+$cards[$cardNum] : $cards[$cardNum];
      }
      $cardNum++;
    }
    fclose($h);

    $result = array_sum($cards);

    return $result;
  }
}
