<?php

/**
 * @file
 * Contains \Drupal\Tests\math_formatter\Unit\Calculator.
 */

namespace Drupal\Tests\math_formatter\Unit;

use Drupal\math_formatter\Calculator;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\math_formatter\Calculator
 */
class CalculatorTest extends UnitTestCase {

  /**
   * The calculator under test.
   *
   * @var Drupal\math_formatter\Calculator
   */
  protected $calculator;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    $this->calculator = new Calculator();
  }

  /**
   * Test several operations.
   *
   * Check the calculate method covers also it's helpers, the lexer and the
   * parser.
   *
   * @dataProvider providerTestExpressionEvaluation
   *
   * @covers ::calculate
   */
  public function testExpressionEvaluation($expected_result, $operation) {
    $result = $this->calculator->calculate($operation);
    $this->assertEquals($result, $result);
  }

  /**
   * Data provider for testExpressionEvaluation.
   *
   * @return array
   *   Array of provider data for testExpressionEvaluation.
   */
  public function providerTestExpressionEvaluation() {
    return [
      // Simple case.
      ['12', '10+2'],
      // Task definition case.
      ['75', '10 + 20 - 30 + 15 * 5'],
      // With float values.
      ['10', '2.5*4'],
      // - and / precedence.
      ['-35', '10+4-24-25-3*3/4/5*3'],
      // Complex case.
      ['-20', '10 + 20 - 30 + 15 * 5 - 32 + 1*2 - 2.5 - 7.5 + 5 - 5*2*2 - 100/10*2*4/2'],
    ];
  }

  /**
   * Cleanup the Calculator instance.
   */
  public function tearDown() {
    unset($this->calculator);
  }

}
