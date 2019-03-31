<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Plugin\Field\FieldFormatter\ReactMathFormatter.
 */

namespace Drupal\math_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of math formatter in React.
 *
 * @FieldFormatter(
 *   id = "react_math_formatter",
 *   label = @Translation("Calculated value (react)"),
 *   field_types = {
 *     "string",
 *     "string_long"
 *   }
 * )
 */
class ReactMathFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
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
      $configuration['third_party_settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $nid = $items->getEntity()->id();
    $field_name = $items->getName();
    $bundle = $items->getEntity()->bundle();

    $element = [
      '#theme' => 'react_math_formatter',
      '#nid' => $nid,
      '#field_name' => $field_name,
      '#bundle' => $bundle,
    ];

    $element['#attached']['library'][] = 'math_formatter/react_field';

    return $element;
  }

}
