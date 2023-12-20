<?php

namespace Jelle_S\AOC\AOC2023\Day19;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    list($s_workflows, $s_gears) = explode("\n\n", file_get_contents($this->input));

    $workflows = [];
    foreach (explode("\n", $s_workflows) as $workflow) {
      $name = substr($workflow, 0, strpos($workflow, '{'));
      $workflows[$name] = $this->parseWorkflow(substr($workflow, strpos($workflow, '{') + 1, - 1));
    }

    $gears = array_map(fn ($x) => json_decode(str_replace(['=', 'x', 'm', 'a', 's'], [':', '"x"', '"m"', '"a"', '"s"'], $x), true), explode("\n", trim($s_gears)));

    foreach ($gears as $gear) {
      if ($this->isAccepted($gear, $workflows)) {
        $result += array_sum($gear);
      }
    }

    return $result;
  }

  protected function isAccepted($gear, $workflows) {
    $wfname = 'in';
    while (true) {
      $workflow = $workflows[$wfname];
      $wfname = $this->workflowResult($gear, $workflow);
      if ($wfname === 'A') {
        return true;
      }
      if ($wfname === 'R') {
        return false;
      }
    }
  }

  protected function workflowResult($gear, $workflow) {
    foreach ($workflow as $rule) {
      if (count($rule) === 1) {
        return $rule[0];
      }
      if ($rule[0][1] === '>' && $gear[$rule[0][0]] > $rule[0][2]) {
        return $rule[1];
      }
      if ($rule[0][1] === '<' && $gear[$rule[0][0]] < $rule[0][2]) {
        return $rule[1];
      }
    }

    throw new \InvalidArgumentException('a workflow should always have a result');
  }

  protected function parseWorkflow(string $workflow) {
    $rules = explode(',', $workflow);

    foreach ($rules as &$rule) {
      $rule = array_filter(explode(':', $rule), fn($x) => $x !== "");
      if (count($rule) === 2) {
        $rule[0] = [
          $rule[0][0],
          $rule[0][1],
          intval(substr($rule[0], 2)),
        ];
      }
    }

    return $rules;
  }
}
