<?php

namespace Jelle_S\AOC\AOC2023\Day07;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $cards = [];
  protected array $handTypes = [];

  public function __construct(protected string $input) {
    $this->cards = array_flip(array_reverse(['A', 'K', 'Q', 'J', 'T', '9', '8', '7', '6', '5', '4', '3', '2']));
    $cardsMatch = implode('|', array_keys($this->cards));
    $this->handTypes = [
      6 => fn($h) => (bool)preg_match('/([' . $cardsMatch . '])\1{4}/', $this->sortHand($h)),
      5 => fn($h) => (bool)preg_match('/([' . $cardsMatch . '])\1{3}/', $this->sortHand($h)),
      4 => fn($h) =>
        (bool)preg_match('/([' . $cardsMatch . '])\1{2}([' . $cardsMatch . '])\2{1}/', $this->sortHand($h))
        || (bool)preg_match('/([' . $cardsMatch . '])\1{1}([' . $cardsMatch . '])\2{2}/', $this->sortHand($h)),
      3 => fn($h) => (bool)preg_match('/([' . $cardsMatch . '])\1{2}/', $this->sortHand($h)),
      2 => fn($h) => (bool)preg_match('/([' . $cardsMatch . '])\1([' . $cardsMatch . '])?([' . $cardsMatch . '])\3/', $this->sortHand($h)),
      1 => fn($h) => (bool)preg_match('/([' . $cardsMatch . '])\1/', $this->sortHand($h)),
    ];
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $game = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list($hand, $bid) = explode(' ', $line);
      $game[$hand] = $bid;
    }
    fclose($h);

    uksort($game, [$this, 'compareHands']);

    $rank = 0;
    foreach ($game as $bid) {
      $rank++;
      $result += $bid * $rank;
    }

    return $result;
  }

  protected function compareHands(string $a, string $b) {
    $typeA = $this->getHandType($a);
    $typeB = $this->getHandType($b);

    if ($typeA !== $typeB) {
      return $typeA - $typeB;
    }

    foreach (str_split($a) as $pos => $card) {
      if ($card !== $b[$pos]) {
        return $this->cards[$card] - $this->cards[$b[$pos]];
      }
    }

    return 0;
  }

  protected function sortHand(string $hand) {
    $cards = str_split($hand);
    sort($cards);
    return implode('', $cards);
  }

  protected function getHandType(string $hand): int {
    foreach ($this->handTypes as $weight => $callback) {
      if ($callback($hand)) {
        return $weight;
      }
    }

    return 0;
  }
}
