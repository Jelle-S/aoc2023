<?php

namespace Jelle_S\AOC\AOC2023\Day01;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');


    $digits = '';
    while (($line = fgets($h)) !== false) {
      $result += (int) ($this->findFirstDigit($line) . $this->findLastDigit($line));
    }
    fclose($h);

    return $result;
  }

  protected function findFirstDigit(string $line) {
    $numbers = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    $word = '';
    $line = str_split(trim($line));
    foreach ($line as $char) {
      if (is_numeric($char)) {
        return $char;
      }
      $word .= $char;
      foreach ($numbers as $intval => $number) {
        if (str_contains($word, $number)) {
          return $intval;
        }
      }
    }
  }

  protected function findLastDigit(string $line) {
    $numbers = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    $word = '';
    $line = str_split(strrev(trim($line)));
    foreach ($line as $char) {
      if (is_numeric($char)) {
        return $char;
      }
      $word = $char . $word;
      foreach ($numbers as $intval => $number) {
        if (str_contains($word, $number)) {
          return $intval;
        }
      }
    }
  }
}
