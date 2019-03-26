<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Parser.
 */

namespace Drupal\math_formatter;

/**
 * Implements the expression parser and builds the operations tree.
 *
 se Drupal\math_formatter\Operator;
 */
class Parser {
  /** @var string */
  private $input = '';

  /** @var array */
  private $tokens = [];

  /**
   * @var array
   */
  private $output = [];

  public function __construct(string $input) {
    $this->input = $input;
    // Regex lexer. Captures digits and +, -, *, / operators.
    preg_match_all('/[[\d\.]+|\+|\-|\*|\/]+/', $this->input, $matches);
    $this->tokens = $matches[0];
  }

  // TODO handle exceptions.
  // https://en.wikipedia.org/wiki/Shunting-yard_algorithm
  public function parse() {
    $this->output = [];
    $operator_stack = [];
    // Generates a RPN array.
    foreach ($this->tokens as $token) {
      if (preg_match('/[\d\.]+/', $token)) {
        $this->output[] = $token;
      }
      else {
        // Operator in the top of the stack have greater precedence.
        if (in_array($token, ['+', '-'], TRUE) && in_array(end($operator_stack), ['*', '/'], TRUE)) {
          $this->output= array_merge($this->output, array_reverse($operator_stack));
          $operator_stack = [];
        }
        $operator_stack[] =  $token;
      }
    }
    $this->output = array_merge($this->output, $operator_stack);
    return $this->output;
  }
}
