<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Calculator.
 */

namespace Drupal\math_formatter;

use Drupal\math_formatter\Parser;

/**
 * Performs calculation given a input.
 *
 * It implements the lexer, the parser and the expression evaluation. Supports
 * basic operation: +, -, *, /.
 */
class Calculator {

  /**
   * The input to evaluate.
   *
   * @var string
   */
  protected $input;

  protected $tokens = [];

  /**
   * Constructs the calculator.
   *
   * @param string $input
   *   The input to evaluate.
   */
  public function __construct() {
  }

  public function calculate($input) {
    $this->input = $input;
    $parser = new Parser($input);
    $this->tokens = $parser->parse();
    $operands = [];
    foreach ($this->tokens as $token) {
      if (preg_match('/[\d\.]+/', $token)) {
        $operands[] = $token;
      }
      else {
        $operand2 = array_pop($operands);
        $operand1 = array_pop($operands);
        $operands[] = $this->operate($operand1, $operand2, $token);
      }
    }
    return array_pop($operands);
  }

  private function operate($operand1, $operand2, $token) {
    switch ($token) {
      case '+':
        return $operand1 + $operand2;
        break;
      case '-':
        return $operand1 - $operand2;
        break;
      case '*':
        return $operand1 * $operand2;
        break;
      case '/':
        return $operand1 / $operand2;
        break;
    }
    // TBD throw exception
    return 0;
  }


}
