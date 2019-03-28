<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Plugin\Field\FieldFormatter\MathFormatter.
 */

namespace Drupal\math_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\math_formatter\Calculator;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Plugin implementation of math formatter.
 *
 * @FieldFormatter(
 *   id = "math_formatter",
 *   label = @Translation("Calculated value"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *   }
 * )
 */
class MathFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The calculator service
   *
   * @var \Drupal\math_formatter\Calculator
   */
  protected $calculator;


  /**
   * Construct a MyFormatter object
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entityManager
   *   The entity manager service
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, Calculator $calculator) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->calculator = $calculator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('math_formatter.calculator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Evaluates the mathematical expression and shows its result. It only supports +, -, * and / operands');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'math_formatter',
        '#value' => $this->calculator->calculate($item->value),
      ];
    }

    return $elements;
  }

}

