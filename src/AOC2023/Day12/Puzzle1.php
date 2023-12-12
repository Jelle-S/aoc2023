<?php

namespace Jelle_S\AOC\AOC2023\Day12;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list($mask, $brokenNums) = explode(' ', $line);
      $brokenNums = array_map('intval', explode(',', $brokenNums));
      $combos = $this->countCombos($mask, $brokenNums);
      $result += $combos;

    }
    fclose($h);

    return $result;
  }

  protected function countCombos(string $mask, array $brokenNums) {
    $totalBroken = array_sum($brokenNums);

    // Early return condition: more broken springs left than we need.
    if ($totalBroken < substr_count($mask, '#')) {
      return 0;
    }

    // Early return condition: Not enough possible broken springs left in this
    // configuration.
    if ($totalBroken > strlen(str_replace('.', '', $mask))) {
      return 0;
    }

    // Early return conditions for when only one contiguous group is left.
    if (count($brokenNums) === 1) {
      $num = reset($brokenNums);

      // Only question marks left: There's a formula for this!
      if (!str_contains($mask, '.') && !str_contains($mask, '#')) {
        return strlen($mask) - $num + 1;
      }
    }

    // No mask left: only valid if no broken springs are left.
    if (empty($mask)) {
      return empty($brokenNums) ? 1 : 0;
    }

    // No broken springs left: only valid if the config doesn't contain any.
    if (empty($brokenNums)) {
      return !str_contains($mask, '#') ? 1 : 0;
    }

    // Try to create the next contiguous set of the remainder of the mask.
    $num = reset($brokenNums);
    $numConfigs = 0;

    if ($mask[0] === '#' || $mask[0] === '?') {
      // In this path we either encountered a '?', or a '#'. Following this code
      // path for '#' is equivalent of replacing the '?' with a '#'.
      // We can only follow this path, if it doesn't create an invalid config,
      // meaning the next $num characters must be a '#' or '?', so we can
      // replace it with a '#' AND the character at position $num must be a '.'
      // or the end of the string (since 4 can never be 2,2).
      if (!str_contains(substr($mask, 0, $num), '.') && (strlen($mask) === $num || $mask[$num] !== '#')) {
        // Substring of $mask, $num **+ 1** because the next spring after this
        // set **cannot** be turned in a '#' if it's a '?', because that would
        // be invalid config.
        $count = $this->countCombos(substr($mask, $num + 1), array_slice($brokenNums, 1));
        $numConfigs += $count;
      }
    }

    if ($mask[0] === '.' || $mask[0] === '?') {
      // In this path we either encountered a '.', or a '?'. Following this code
      // path for '?' is equivalent of replacing the '?' with a '.'. We can
      // always follow this code path since the cases where there aren't enough
      // characters left to create the correct amount of broken springs are
      // handled by the early returns above, and so will be handled by the next
      // iteration.
      $numConfigs += $this->countCombos(substr($mask, 1), $brokenNums);
    }

    return $numConfigs;
  }
}
