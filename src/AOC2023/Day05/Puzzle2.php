<?php

namespace Jelle_S\AOC\AOC2023\Day05;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $almanac = [];
    $curMap = [];
    $seedRanges = array_chunk(array_map('intval', explode(' ', str_replace('seeds: ', '', trim(fgets($h))))), 2);
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      if (!$line) {
        continue;
      }
      $matches = [];
      if (preg_match('/([^-]+)-to-([^ ]+)/', $line, $matches)) {
        if ($curMap) {
          $almanac[] = $curMap;
        }
        $curMap = [
        ];
        continue;
      }

      $curMap[] = array_map('intval', explode(' ', $line));
    }
    $almanac[] = $curMap;
    fclose($h);

    // Map the seeds to actual ranges so I can wrap my head around it all. Makes
    // the math a bit easier, hopefully.
    foreach ($seedRanges as &$range) {
      $range[1] = $range[0] + $range[1];
    }

    foreach ($almanac as $rangesMap) {
      $newSeeds = [];
      // Loop over the seeds. We will be manipulating the seeds within the loop,
      // so best not to do this in a foreach.
      while ($seedRanges) {
        list($start, $end) = array_pop($seedRanges);
        $matchFound = false;
        foreach ($rangesMap as list($destinationStart, $sourceStart, $rangeLength)) {
          $overlapStart = max($start, $sourceStart);
          $overlapEnd = min ($end, $sourceStart + $rangeLength);
          // We only have an overlap if...
          if ($overlapStart < $overlapEnd) {
            $matchFound = true;
            // Some seeds might fall out of this overlapping range. Add them to
            // the seeds to check for this set of ranges again, so they are
            // still checked in one of the next iterations.
            if ($end > $overlapEnd) {
              $seedRanges[] = [$overlapEnd, $end];
            }
            if ($overlapStart > $start) {
              $seedRanges[] = [$start, $overlapStart];
            }
            // Actually map the overlap we found, and add them to the new set of
            // seeds.
            $newSeeds[] = [$overlapStart - $sourceStart + $destinationStart, $overlapEnd - $sourceStart + $destinationStart];
            // Since we found an overlap, and mapped that overlap, and re-added
            // the remainder of the seeds to the items to be checked, we can
            // break the loop here.
            break;
          }
        }
        // If no matches were found in the loop above, the entire range is a
        // 1:1 map (numbers do not need to be transformed), so just add them to
        // the new set of seeds.
        if (!$matchFound) {
          $newSeeds[] = [$start, $end];
        }
      }
      // The new seeds are now ready to be mapped by the next ranges.
      $seedRanges = $newSeeds;
    }

    // Get the smallest lower boundary of the ranges, that is the closest
    // location we can get.
    $result = false;
    foreach ($seedRanges as $seed) {
      $result = $result === false
        ? $seed[0]
        : min($result, $seed[0]);
    }
    return $result;
  }
}
