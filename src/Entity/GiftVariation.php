<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\commerce_price\Price;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Url;
use Drupal\user\UserInterface;

/**
 * Defines the gift variation entity class.
 *
 * @ContentEntityType(
 *   id = "commerce_gift_variation",
 *   label = @Translation("Gift variation"),
 *   label_singular = @Translation("gift variation"),
 *   label_plural = @Translation("gift variations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gift variation",
 *     plural = "@count gift variations",
 *   ),
 *   bundle_label = @Translation("Gift variation type"),
 *   handlers = {
 *     "event" = "Drupal\commerce_gift\Event\GiftVariationEvent",
 *     "storage" = "Drupal\commerce_gift\GiftVariationStorage",
 *     "access" = "Drupal\commerce\EmbeddedEntityAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\Core\Entity\ContentEntityForm",
 *     },
 *     "inline_form" = "Drupal\commerce_gift\Form\GiftVariationInlineForm",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler"
 *   },
 *   admin_permission = "administer commerce_gift",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   content_translation_ui_skip = TRUE,
 *   base_table = "commerce_gift_variation",
 *   data_table = "commerce_gift_variation_field_data",
 *   entity_keys = {
 *     "id" = "variation_id",
 *     "bundle" = "type",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *     "label" = "title",
 *     "status" = "status",
 *   },
 *   bundle_entity_type = "commerce_gift_variation_type",
 *   field_ui_base_route = "entity.commerce_gift_variation_type.edit_form",
 * )
 */
class GiftVariation extends ContentEntityBase implements GiftVariationInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function toUrl($rel = 'canonical', array $options = []) {
    if ($rel == 'canonical') {
      $route_name = 'entity.commerce_gift.canonical';
      $route_parameters = [
        'commerce_gift' => $this->getGiftId(),
      ];
      $options = [
        'query' => [
          'v' => $this->id(),
        ],
        'entity_type' => 'commerce_gift',
        'entity' => $this->getGift(),
        // Display links by default based on the current language.
        'language' => $this->language(),
      ];
      return new Url($route_name, $route_parameters, $options);
    }
    else {
      return parent::toUrl($rel, $options);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getGift() {
    return $this->get('gift_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getGiftId() {
    return $this->get('gift_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getSku() {
    return $this->get('sku')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSku($sku) {
    $this->set('sku', $sku);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrice() {
    if (!$this->get('price')->isEmpty()) {
      return $this->get('price')->first()->toPrice();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setPrice(Price $price) {
    $this->set('price', $price);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isActive() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setActive($active) {
    $this->set('status', (bool) $active);
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
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStores() {
    $gift = $this->getGift();
    return $gift ? $gift->getStores() : [];
  }

  /**
   * {@inheritdoc}
   */
  public function getOrderItemTypeId() {
    // The order item type is a bundle-level setting.
    $type_storage = $this->entityTypeManager()->getStorage('commerce_gift_variation_type');
    $type_entity = $type_storage->load($this->bundle());

    return $type_entity->getOrderItemTypeId();
  }

  /**
   * {@inheritdoc}
   */
  public function getOrderItemTitle() {
    $label = $this->label();
    if (!$label) {
      $label = $this->generateTitle();
    }

    return $label;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValueIds() {
    $attribute_ids = [];
    foreach ($this->getAttributeFieldNames() as $field_name) {
      $field = $this->get($field_name);
      if (!$field->isEmpty()) {
        $attribute_ids[$field_name] = $field->target_id;
      }
    }

    return $attribute_ids;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValueId($field_name) {
    $attribute_field_names = $this->getAttributeFieldNames();
    if (!in_array($field_name, $attribute_field_names)) {
      throw new \InvalidArgumentException(sprintf('Unknown attribute field name "%s".', $field_name));
    }
    $attribute_id = NULL;
    $field = $this->get($field_name);
    if (!$field->isEmpty()) {
      $attribute_id = $field->target_id;
    }

    return $attribute_id;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValues() {
    $attribute_values = [];
    foreach ($this->getAttributeFieldNames() as $field_name) {
      $field = $this->get($field_name);
      if (!$field->isEmpty()) {
        $attribute_values[$field_name] = $field->entity;
      }
    }

    return $attribute_values;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeValue($field_name) {
    $attribute_field_names = $this->getAttributeFieldNames();
    if (!in_array($field_name, $attribute_field_names)) {
      throw new \InvalidArgumentException(sprintf('Unknown attribute field name "%s".', $field_name));
    }
    $attribute_value = NULL;
    $field = $this->get($field_name);
    if (!$field->isEmpty()) {
      $attribute_value = $field->entity;
    }

    return $attribute_value;
  }

  /**
   * Gets the names of the entity's attribute fields.
   *
   * @return string[]
   *   The attribute field names.
   */
  protected function getAttributeFieldNames() {
    $attribute_field_manager = \Drupal::service('commerce_gift.attribute_field_manager');
    $field_map = $attribute_field_manager->getFieldMap($this->bundle());
    return array_column($field_map, 'field_name');
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTagsToInvalidate() {
    $tags = parent::getCacheTagsToInvalidate();
    // Invalidate the variations view builder and gift caches.
    return Cache::mergeTags($tags, [
      'commerce_gift:' . $this->getGiftId(),
      'commerce_gift_variation_view',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    /** @var \Drupal\commerce_gift\Entity\GiftVariationTypeInterface $variation_type */
    $variation_type = $this->entityTypeManager()
      ->getStorage('commerce_gift_variation_type')
      ->load($this->bundle());

    if ($variation_type->shouldGenerateTitle()) {
      $title = $this->generateTitle();
      $this->setTitle($title);
    }
  }

  /**
   * Generates the variation title based on attribute values.
   *
   * @return string
   *   The generated value.
   */
  protected function generateTitle() {
    if (!$this->getGiftId()) {
      // Title generation is not possible before the parent gift is known.
      return '';
    }

    $gift_title = $this->getGift()->getTitle();
    if ($attribute_values = $this->getAttributeValues()) {
      $attribute_labels = array_map(function ($attribute_value) {
        return $attribute_value->label();
      }, $attribute_values);

      $title = $gift_title . ' - ' . implode(', ', $attribute_labels);
    }
    else {
      // When there are no attribute fields, there's only one variation.
      $title = $gift_title;
    }

    return $title;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setDescription(t('The variation author.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\commerce_gift\Entity\GiftVariation::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE);

    // The gift backreference, populated by Gift::postSave().
    $fields['gift_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Gift'))
      ->setDescription(t('The parent gift.'))
      ->setSetting('target_type', 'commerce_gift')
      ->setReadOnly(TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sku'] = BaseFieldDefinition::create('string')
      ->setLabel(t('SKU'))
      ->setDescription(t('The unique, machine-readable identifier for a variation.'))
      ->setRequired(TRUE)
      // ->addConstraint('GiftVariationSku')
      ->setSetting('display_description', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The variation title.'))
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // The price is not required because it's not guaranteed to be used
    // for storage (there might be a price per currency, role, country, etc).
    $fields['price'] = BaseFieldDefinition::create('commerce_price')
      ->setLabel(t('Price'))
      ->setDescription(t('The variation price'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'commerce_price_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'commerce_price_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Active'))
      ->setDescription(t('Whether the variation is active.'))
      ->setDefaultValue(TRUE)
      ->setTranslatable(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 99,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time when the variation was created.'))
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time when the variation was last edited.'))
      ->setTranslatable(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public static function bundleFieldDefinitions(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = [];
    /** @var \Drupal\commerce_gift\Entity\GiftVariationTypeInterface $variation_type */
    $variation_type = GiftVariationType::load($bundle);
    // $variation_type could be NULL if the method is invoked during uninstall.
    if ($variation_type && $variation_type->shouldGenerateTitle()) {
      // The title is always generated, the field needs to be hidden.
      // The widget is hidden in commerce_gift_field_widget_form_alter()
      // since setDisplayOptions() can't affect existing form displays.
      $fields['title'] = clone $base_field_definitions['title'];
      $fields['title']->setRequired(FALSE);
      $fields['title']->setDisplayConfigurable('form', FALSE);
    }

    return $fields;
  }

  /**
   * Default value callback for 'uid' base field definition.
   *
   * @see ::baseFieldDefinitions()
   *
   * @return array
   *   An array of default values.
   */
  public static function getCurrentUserId() {
    return [\Drupal::currentUser()->id()];
  }

}
