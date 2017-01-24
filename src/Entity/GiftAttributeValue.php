<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the gift attribute value entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_gift_attribute_value",
 *   label = @Translation("Gift attribute value"),
 *   label_singular = @Translation("gift attribute value"),
 *   label_plural = @Translation("gift attribute values"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gift attribute value",
 *     plural = "@count gift attribute values",
 *   ),
 *   bundle_label = @Translation("Gift attribute"),
 *   handlers = {
 *     "event" = "Drupal\commerce_gift\Event\GiftAttributeValueEvent",
 *     "storage" = "Drupal\commerce_gift\GiftAttributeValueStorage",
 *     "access" = "Drupal\commerce\EmbeddedEntityAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   admin_permission = "administer commerce_gift_attribute",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   content_translation_ui_skip = TRUE,
 *   base_table = "commerce_gift_attribute_value",
 *   data_table = "commerce_gift_attribute_value_field_data",
 *   entity_keys = {
 *     "id" = "attribute_value_id",
 *     "bundle" = "attribute",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *   },
 *   bundle_entity_type = "commerce_gift_attribute",
 *   field_ui_base_route = "entity.commerce_gift_attribute.edit_form",
 * )
 */
class GiftAttributeValue extends ContentEntityBase implements GiftAttributeValueInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getAttributeId() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->get('weight')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWeight($weight) {
    $this->set('weight', $weight);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    // Override the label for the generated bundle field.
    $fields['attribute']->setLabel(t('Attribute'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The attribute value name.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['weight'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Weight'))
      ->setDescription(t('The weight of this attribute value in relation to others.'))
      ->setDefaultValue(0);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the attribute value was created.'))
      ->setTranslatable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the attribute value was last edited.'))
      ->setTranslatable(TRUE);

    return $fields;
  }

}
