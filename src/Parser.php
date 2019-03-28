<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Parser.
 */

namespace Drupal\math_formatter;

/**
 * Implements the expression lexer and parser and builds the Reverse Polish
 * Notation array of tokens.
 */
class Parser {

  /** @var string */
  private $input = '';

  /** @var array */
  private $tokens = [];

  /**
   * Class constructor.
   *
   * @var string $input
   *   The text string to be parsed.
   */
  public function __construct(string $input) {
    $this->input = $input;
    $this->lexer();
  }

  /**
   * Lexer method. Converts input string to array of tokens.
   *
   * Based on a Regex it supports integer and float values and the operators:
   * +, -, *, /.
   */
  public function lexer() {
    preg_match_all('/[[\d\.]+|\+|\-|\*|\/|]+/', $this->input, $matches);
    $this->tokens = $matches[0];
  }

  /**
   * The parser method based in Shunting yard algorithm.
   *
   * It implements a simplified version of the algorithm because the cases to
   * handle here are much more simple.
   *
   * @return array
   *   The Reverse Polish Notation array.
   */
  public function parse() {
    $output = [];
    $operator_stack = [];
    $previous_operator = '';

    foreach ($this->tokens as $token) {
      // Checks if the token is an operand (number) or an operator.
      if (preg_match('/[\d\.]+/', $token)) {
        $output[] = $this->getOperand($token, $previous_operator);
      }
      else {
        $previous_operator = $token;
        if ($this->lowerOperatorPrecedence($token, end($operator_stack))) {
          $output = array_merge($output, array_reverse($operator_stack));
          $operator_stack = [];
        }

        // Simplifies operators. @see getOperand().
        if (in_array($token, ['+', '-'])) {
          $operator_stack[] = '+';
        }
        else {
          $operator_stack[] = '*';
        }
      }
    }

    $output = array_merge($output, array_reverse($operator_stack));

    return $output;
  }

  /**
   * Evaluates if the operand value should be inverted.
   *
   * Due to the simplification of the algorithm the operator precedence for -
   * and / should be checked, as long they have the same precedence than + and
   * * respectively but are inverting the operand value. So given the context
   * of the previous operator evaluates if the operand should be inverted for
   * the given operation. This process leads to a RPN much simple with only +
   * and * operations.
   *
   * @var string $operand
   *   The operand to invert if necessary.
   * @var string $previous_operator
   *   The previous operator in the processing.
   *
   * @return integer|float|string
   *   The operand to add to the final RPN.
   */
  private function getOperand($operand, $previous_operator) {
    if ($previous_operator === '-') {
      return (-1) * $operand;
    }
    elseif ($previous_operator === '/') {
      return 1 / $operand;
    }

    return $operand;
  }

  /**
   * Implements operator precedence.
   *
   * The precedence order is:
   *   * = / > + = -
   *
   * @var string $new
   *   The operator coming from the input array.
   * @var string $last
   *   The operator in the top of the operator stack.
   *
   * @return boolean
   *   TRUE if new operator have lower precedence than the last one.
   */
  private function lowerOperatorPrecedence($new, $last) {
    return in_array($new, ['+', '-']) && in_array($last, ['*', '/']);
  }

}
