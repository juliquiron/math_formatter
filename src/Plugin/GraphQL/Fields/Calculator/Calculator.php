<?php

/**
 * @file
 * GraphQL field plugin for Calculator service.
 */

namespace Drupal\math_formatter\Plugin\GraphQL\Fields\Calculator;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;


/**
 * Calculates the result of a given expression.
 *
 * @GraphQLField(
 *   id = "calculator",
 *   secure = true,
 *   name = "calculator",
 *   description = @Translation("The calculated value of a math expression"),
 *   type = "Integer",
 *   arguments = {
 *     "expression" = "String!"
 *   }
 * )
 */
class Calculator extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    $expression = $args['expression'];
    // @TODO Figure out how to inject the service.
    $calculator = \Drupal::service('math_formatter.calculator');
    yield $calculator->calculate($expression);
  }

}
