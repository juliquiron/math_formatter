<?php

/**
 * @file
 * Contains \Drupal\math_formatter\Plugin\Field\FieldFormatter\MathFormatter.
 */

namespace Drupal\math_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
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
 *     "string",
 *     "string_long"
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
  public static function defaultSettings() {
    return [
      'evaluate' => 'sync',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['evaluate'] = [
      '#title' => $this->t('Evaluate the math expression on page load or asynchronously'),
      '#type' => 'select',
      '#options' => [
        'sync' => $this->t('On page load'),
        'async' => $this->t('Asynchronously'),
      ],
      '#default_value' => $this->getSetting('evaluate'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Evaluates the mathematical expression and shows its result. It only supports +, -, * and / operands');
    if ($this->getSetting('evaluate') === 'sync') {
      $summary[] = $this->t('The mathematical expression will be evaluated on the page load.');
    }
    elseif ($this->getSetting('evaluate') === 'async') {
      $summary[] = $this->t('The mathematical expression will be evaluated asynchronously and showed on mouse over the expression.');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $evaluate = $this->getSetting('evaluate');
    $elements = [];

    foreach ($items as $delta => $item) {
      $value = $evaluate === 'async' ? $item->value : $this->calculator->calculate($item->value);
      $elements[$delta] = [
        '#theme' => 'math_formatter',
        '#value' => $value,
        '#evaluate' => $evaluate,
      ];
    }

    if ($evaluate === 'async') {
      $elements['#attached']['library'][] = 'math_formatter/async';
    }

    return $elements;
  }

}

