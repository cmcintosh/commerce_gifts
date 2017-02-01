<?php

namespace Drupal\commerce_gift\Form;

use Drupal\commerce_gift\Entity\GiftAttributeInterface;
use Drupal\commerce_gift\Entity\GiftAttributeValueInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides common functionality for the gift attribute translation forms.
 */
trait GiftAttributeTranslationFormTrait {

  /**
   * Gets the entity type manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   *   The entity type manager.
   */
  abstract protected function getEntityTypeManager();

  /**
   * Builds the translation form for gift attribute values.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Drupal\commerce_gift\Entity\GiftAttributeInterface $attribute
   *   The gift attribute.
   *
   * @return array
   *   The translation form.
   */
  protected function buildValuesForm(array $form, FormStateInterface $form_state, GiftAttributeInterface $attribute) {
    $values = $attribute->getValues();
    $has_translatable_values = FALSE;
    foreach ($values as $value) {
      if ($value->isTranslatable()) {
        $has_translatable_values = TRUE;
        break;
      }
    }
    // Don't display the values if there's nothing to translate.
    if (!$has_translatable_values) {
      return $form;
    }

    $language = $form_state->get('config_translation_language');
    $source_language = $form_state->get('config_translation_source_language');
    // Set the keys expected by IEF's TranslationHelper.
    $form_state->set('langcode', $language->getId());
    $form_state->set('entity_default_langcode', $source_language->getId());
    // The IEF form element uses #process to attach the submit handlers, but
    // that only works if the action buttons are added before the IEF elements.
    // That's not the case here, so this workaround triggers the same logic in
    // inline_entity_form_form_alter().
    $form_state->set('inline_entity_form', []);

    $form['values'] = [
      '#type' => 'table',
      '#header' => [$this->t('Value'), $this->t('Value')],
      // #input defaults to TRUE, which breaks file fields in the IEF element.
      // This table is used for visual grouping only, the element itself
      // doesn't have any values of its own that need processing.
      '#input' => FALSE,
    ];
    foreach ($values as $index => $value) {
      $value_form = &$form['values'][$index];
      $value_form['source'] = [
        'value' => $this->renderOriginalValue($value),
        '#wrapper_attributes' => ['style' => 'width: 50%'],
      ];
      $value_form['translation'] = [
        '#type' => 'inline_entity_form',
        '#entity_type' => 'commerce_gift_attribute_value',
        '#bundle' => $attribute->id(),
        '#default_value' => $value,
        '#wrapper_attributes' => ['style' => 'width: 50%'],
      ];
    }

    return $form;
  }

  /**
   * Renders the given gift attribute value in the original language.
   *
   * Skips non-translatable fields. Skips all base fields other than the name.
   *
   * @param \Drupal\commerce_gift\Entity\GiftAttributeValueInterface $value
   *   The gift attribute value.
   *
   * @return array
   *   The render array.
   */
  protected function renderOriginalValue(GiftAttributeValueInterface $value) {
    $value = $value->getUntranslated();
    $view_builder = $this->getEntityTypeManager()->getViewBuilder('commerce_gift_variation');
    $build = [];
    foreach ($value->getFieldDefinitions() as $field_name => $definition) {
      if (!$definition->isTranslatable()) {
        continue;
      }
      if ($definition instanceof BaseFieldDefinition && $field_name != 'name') {
        continue;
      }

      $build[$field_name] = $view_builder->viewField($value->get($field_name), ['label' => 'above']);
    }

    return $build;
  }

}
