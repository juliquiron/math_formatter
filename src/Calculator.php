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
 * Supports * basic operation: +, -, *, /.
 *
 * @see Drupal\math_formatter\Parser
 */
class Calculator {

  /**
   * Evaluates the expression value.
   *
   * Uses a parser to get a tokenized array in Revers Polish Notation to
   * evaluate the expression.
   *
   * @see Drupal\math_formatter\Parser.
   *
   * @var string $input
   *   The expression to be evaluated.
   *
   * @return integer|float
   *   The value of the expression evaluation.
   */
  public function calculate($input) {
    $parser = new Parser($input);
    $tokens = $parser->parse();
    $operands = [];
    foreach ($tokens as $token) {
      // Checks if the token is an operand (number) or an operator.
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

  /**
   * Performs the operation defined by the token.
   *
   * Only considers 2 operations, the only two provided by the parser.
   *
   * @see Drupal\math_formatter\Parser::getOperand()
   *
   * @var integer|float|string $operand1
   *   The first operand for the operation.
   * @var integer|float|string $operand2
   *   The second operand for the operation.
   * @var string $token
   *   The token with the operation to be performed.
   *
   * @returns integer|float
   *   The value result of the operation.
   *
   * @throws Exception if the operand is not one of the expected.
   */
  private function operate($operand1, $operand2, $token) {
    switch ($token) {
      case '+':
        return $operand1 + $operand2;
      case '*':
        return $operand1 * $operand2;
    }

    throw new Exception('Unexpected operand');
  }

}
