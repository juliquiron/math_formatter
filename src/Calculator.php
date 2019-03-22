<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Calculator.
 */

namespace Drupal\math_formatter;

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

  /**
   * Constructs the calculator.
   *
   * @param string $input
   *   The input to evaluate.
   */
  public function __construct() {
  }

  public function calculate($input) {
    return 'TBD';
  }


}
