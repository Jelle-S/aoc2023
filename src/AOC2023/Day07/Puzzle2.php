<?php

namespace Jelle_S\AOC\AOC2023\Day07;

class Puzzle2 extends Puzzle1 {

  public function __construct(protected string $input) {
    $this->cards = array_flip(array_reverse(['A', 'K', 'Q', 'T', '9', '8', '7', '6', '5', '4', '3', '2', 'J']));
    $cardsMatch = implode('|', array_keys($this->cards));
    $this->handTypes = [
      6 => function ($h) use ($cardsMatch) {
        if ((bool)preg_match('/([' . $cardsMatch . '])(\1|J){4}/', $this->sortHand($h))) {
          return true;
        }
        $uniqueCards = array_unique(str_split($h));
        if (count($uniqueCards) === 2 && str_contains($h, 'J')) {
          return true;
        }
        return false;
      },
      5 => function ($h) use ($cardsMatch) {

        if ((bool)preg_match('/([' . $cardsMatch . '])(\1|J){3}/', $this->sortHand($h))) {
          return true;
        }
        // 4 of a kind that doesn't match above regex has 3 different cards, at
        // least one of which is a J.
        $uniqueCards = array_unique(str_split($h));
        if (count($uniqueCards) !== 3 || !str_contains($h, 'J')) {
          return false;
        }

        $nonJCards = array_diff($uniqueCards, ['J']);
        $jCards = substr_count($h, 'J');
        foreach ($nonJCards as $card) {
          if (substr_count($h, $card) + $jCards === 4) {
            return true;
          }
        }

        return false;
      },
      4 => function($h) {
        // Full house = max 2 different cards, 3 if there's a J.
        $uniqueCards = array_unique(str_split($h));
        if (count($uniqueCards) === 2) {
          // Since we're here, it's not 5 of a kind or 4 of a kind, so this
          // _must_ be a full house.
          return true;
        }
        if (count($uniqueCards) === 3 && in_array('J', $uniqueCards)) {
          // Again, not 4 or 5 of a kind. There is no configuration where we
          // can't make a full house of this combo.
          return true;
        }

        return false;
      },
      3 => function($h) use ($cardsMatch)  {
        if ((bool)preg_match('/([' . $cardsMatch . '])(\1|J){2}/', $this->sortHand($h))) {
          return true;
        }

        $uniqueCards = array_unique(str_split($h));
        $nonJCards = array_diff($uniqueCards, ['J']);
        $jCards = substr_count($h, 'J');
        foreach ($nonJCards as $card) {
          if (substr_count($h, $card) + $jCards === 3) {
            return true;
          }
        }

        return false;
      },
      2 => function($h) use ($cardsMatch)  {
        if ((bool)preg_match('/([' . $cardsMatch . '])(\1|J)([' . $cardsMatch . '])?([' . $cardsMatch . '])(\4|J)/', $this->sortHand($h))) {
          return true;
        }
        // At least one pair and one J.
        if ((bool)preg_match('/([' . $cardsMatch . '])\1/', $this->sortHand($h)) && str_contains($h, 'J')) {
          return true;
        }

        // 2 J's -> always at least two pair.
        if (substr_count($h, 'J') === 2) {
          return true;
        }

        return false;

      },
      1 => fn($h) => (bool)preg_match('/([' . $cardsMatch . '])\1/', $this->sortHand($h)) || str_contains($h, 'J'),
    ];
  }
}
