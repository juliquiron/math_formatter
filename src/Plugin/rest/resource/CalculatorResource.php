<?php

/**
 * @file
 * REST API implementation of the Calculator service.
 */

namespace Drupal\math_formatter\Plugin\rest\resource;

use Drupal\Component\Utility\Xss;
use Drupal\math_formatter\Calculator;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;

/**
 * Provides Calculator service connector.
 *
 * @RestResource(
 *   id = "calculator_resource",
 *   label = @Translation("Calculator connector"),
 *   serialization_class = "",
 *   uri_paths = {
 *     "canonical" = "/calculator/evaluate"
 *   }
 * )
 *
 */
class CalculatorResource extends ResourceBase {

  /**
   * The calculator service.
   *
   * @var Drupal\math_formatter\Calculator
   */
  protected $calculator;

  /**
   * The current request object.
   *
   * @var Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    Calculator $calculator,
    Request $request) {
      parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

      $this->calculator = $calculator;
      $this->currentRequest = $request;
    }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('math_formatter'),
      $container->get('math_formatter.calculator'),
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $expression = $this->currentRequest->query->get('expression');
    $expression = Xss::filter($expression);
    // REST response should be different for each expression.
    $cache_control = [
      '#cache' => [
        'tags' => [$expression],
      ],
    ];
    if (!empty($expression)) {
      $response_content = ['value' => $this->calculator->calculate($expression)];
    }
    else {
      $response_content = [
        'message' => $this->t('Missing `expression` GET parameter with the expression to be calculated'),
      ];
    }

    $resource_response = new ResourceResponse($response_content);
    return $resource_response->addCacheableDependency($cache_control);
  }

  /**
   * {@inheritdoc}
   */
  public function post($arg) {
    $response = [ 'message' => 'No POST method implemented' ];
    return new ResourceResponse($response);
  }

  /**
   * {@inheritdoc}
   */
  public function patch($arg) {
    $response = [ 'message' => 'No PATCH method implemented' ];
    return new ResourceResponse($response);
  }
}
