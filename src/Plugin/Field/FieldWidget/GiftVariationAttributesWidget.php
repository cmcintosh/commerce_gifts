<?php

namespace Drupal\commerce_gift\Plugin\Field\FieldWidget;

use Drupal\commerce_gift\Entity\GiftVariationInterface;
use Drupal\commerce_gift\GiftAttributeFieldManagerInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'commerce_gift_variation_attributes' widget.
 *
 * @FieldWidget(
 *   id = "commerce_gift_variation_attributes",
 *   label = @Translation("Gift variation attributes"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class GiftVariationAttributesWidget extends GiftVariationWidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The attribute field manager.
   *
   * @var \Drupal\commerce_gift\GiftAttributeFieldManagerInterface
   */
  protected $attributeFieldManager;

  /**
   * The gift attribute storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $attributeStorage;

  /**
   * Constructs a new GiftVariationAttributesWidget object.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\commerce_gift\GiftAttributeFieldManagerInterface $attribute_field_manager
   *   The attribute field manager.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityTypeManagerInterface $entity_type_manager, GiftAttributeFieldManagerInterface $attribute_field_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings, $entity_type_manager);

    $this->attributeFieldManager = $attribute_field_manager;
    $this->attributeStorage = $entity_type_manager->getStorage('commerce_gift_attribute');
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
      $configuration['third_party_settings'],
      $container->get('entity_type.manager'),
      $container->get('commerce_gift.attribute_field_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\commerce_gift\Entity\GiftInterface $gift */
    $gift = $form_state->get('gift');
    $variations = $this->variationStorage->loadEnabled($gift);
    if (count($variations) === 0) {
      // Nothing to purchase, tell the parent form to hide itself.
      $form_state->set('hide_form', TRUE);
      $element['variation'] = [
        '#type' => 'value',
        '#value' => 0,
      ];
      return $element;
    }
    elseif (count($variations) === 1) {
      /** @var \Drupal\commerce_gift\Entity\GiftVariationInterface $selected_variation */
      $selected_variation = reset($variations);
      // If there is 1 variation but there are attribute fields, then the
      // customer should still see the attribute widgets, to know what they're
      // buying (e.g a gift only available in the Small size).
      if (empty($this->attributeFieldManager->getFieldDefinitions($selected_variation->bundle()))) {
        $element['variation'] = [
          '#type' => 'value',
          '#value' => $selected_variation->id(),
        ];
        return $element;
      }
    }

    // Build the full attribute form.
    $wrapper_id = Html::getUniqueId('commerce-gift-add-to-cart-form');
    $form += [
      '#wrapper_id' => $wrapper_id,
      '#prefix' => '<div id="' . $wrapper_id . '">',
      '#suffix' => '</div>',
    ];
    $parents = array_merge($element['#field_parents'], [$items->getName(), $delta]);
    $user_input = (array) NestedArray::getValue($form_state->getUserInput(), $parents);
    if (!empty($user_input)) {
      $selected_variation = $this->selectVariationFromUserInput($variations, $user_input);
    }
    else {
      $selected_variation = $this->variationStorage->loadFromContext($gift);
      // The returned variation must also be enabled.
      if (!in_array($selected_variation, $variations)) {
        $selected_variation = reset($variations);
      }
    }

    $element['variation'] = [
      '#type' => 'value',
      '#value' => $selected_variation->id(),
    ];
    // Set the selected variation in the form state for our AJAX callback.
    $form_state->set('selected_variation', $selected_variation->id());

    $element['attributes'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['attribute-widgets'],
      ],
    ];
    foreach ($this->getAttributeInfo($selected_variation, $variations) as $field_name => $attribute) {
      $element['attributes'][$field_name] = [
        '#type' => $attribute['element_type'],
        '#title' => $attribute['title'],
        '#options' => $attribute['values'],
        '#required' => $attribute['required'],
        '#default_value' => $selected_variation->getAttributeValueId($field_name),
        '#ajax' => [
          'callback' => [get_class($this), 'ajaxRefresh'],
          'wrapper' => $form['#wrapper_id'],
        ],
      ];
      // Convert the _none option into #empty_value.
      if (isset($element['attributes'][$field_name]['#options']['_none'])) {
        if (!$element['attributes'][$field_name]['#required']) {
          $element['attributes'][$field_name]['#empty_value'] = '';
        }
        unset($element['attributes'][$field_name]['#options']['_none']);
      }
      // 1 required value -> Disable the element to skip unneeded ajax calls.
      if ($attribute['required'] && count($attribute['values']) === 1) {
        $element['attributes'][$field_name]['#disabled'] = TRUE;
      }
      // Optimize the UX of optional attributes:
      // - Hide attributes that have no values.
      // - Require attributes that have a value on each variation.
      if (empty($element['attributes'][$field_name]['#options'])) {
        $element['attributes'][$field_name]['#access'] = FALSE;
      }
      if (!isset($element['attributes'][$field_name]['#empty_value'])) {
        $element['attributes'][$field_name]['#required'] = TRUE;
      }
    }

    return $element;
  }

  /**
   * Selects a gift variation from user input.
   *
   * If there's no user input (form viewed for the first time), the default
   * variation is returned.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface[] $variations
   *   An array of gift variations.
   * @param array $user_input
   *   The user input.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface
   *   The selected variation.
   */
  protected function selectVariationFromUserInput(array $variations, array $user_input) {
    $current_variation = reset($variations);
    if (!empty($user_input)) {
      $attributes = $user_input['attributes'];
      foreach ($variations as $variation) {
        $match = TRUE;
        foreach ($attributes as $field_name => $value) {
          if ($variation->getAttributeValueId($field_name) != $value) {
            $match = FALSE;
          }
        }
        if ($match) {
          $current_variation = $variation;
          break;
        }
      }
    }

    return $current_variation;
  }

  /**
   * Gets the attribute information for the selected gift variation.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface $selected_variation
   *   The selected gift variation.
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface[] $variations
   *   The available gift variations.
   *
   * @return array[]
   *   The attribute information, keyed by field name.
   */
  protected function getAttributeInfo(GiftVariationInterface $selected_variation, array $variations) {
    $attributes = [];
    $field_definitions = $this->attributeFieldManager->getFieldDefinitions($selected_variation->bundle());
    $field_map = $this->attributeFieldManager->getFieldMap($selected_variation->bundle());
    $field_names = array_column($field_map, 'field_name');
    $index = 0;
    foreach ($field_names as $field_name) {
      /** @var \Drupal\commerce_gift\Entity\GiftAttributeInterface $attribute_type */
      $attribute_type = $this->attributeStorage->load(substr($field_name, 10));
      $field = $field_definitions[$field_name];
      $attributes[$field_name] = [
        'field_name' => $field_name,
        'title' => $field->getLabel(),
        'required' => $field->isRequired(),
        'element_type' => $attribute_type->getElementType(),
      ];
      // The first attribute gets all values. Every next attribute gets only
      // the values from variations matching the previous attribute value.
      // For 'Color' and 'Size' attributes that means getting the colors of all
      // variations, but only the sizes of variations with the selected color.
      $callback = NULL;
      if ($index > 0) {
        $previous_field_name = $field_names[$index - 1];
        $previous_field_value = $selected_variation->getAttributeValueId($previous_field_name);
        $callback = function ($variation) use ($previous_field_name, $previous_field_value) {
          /** @var \Drupal\commerce_gift\Entity\GiftVariationInterface $variation */
          return $variation->getAttributeValueId($previous_field_name) == $previous_field_value;
        };
      }

      $attributes[$field_name]['values'] = $this->getAttributeValues($variations, $field_name, $callback);
      $index++;
    }
    // Filter out attributes with no values.
    $attributes = array_filter($attributes, function ($attribute) {
      return !empty($attribute['values']);
    });

    return $attributes;
  }

  /**
   * Gets the attribute values of a given set of variations.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface[] $variations
   *   The variations.
   * @param string $field_name
   *   The field name of the attribute.
   * @param callable|null $callback
   *   An optional callback to use for filtering the list.
   *
   * @return array[]
   *   The attribute values, keyed by attribute ID.
   */
  protected function getAttributeValues(array $variations, $field_name, callable $callback = NULL) {
    $values = [];
    foreach ($variations as $variation) {
      if (is_null($callback) || call_user_func($callback, $variation)) {
        $attribute_value = $variation->getAttributeValue($field_name);
        if ($attribute_value) {
          $values[$attribute_value->id()] = $attribute_value->label();
        }
        else {
          $values['_none'] = '';
        }
      }
    }

    return $values;
  }

}
