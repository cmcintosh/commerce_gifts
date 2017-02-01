<?php

namespace Drupal\commerce_gift;

use Drupal\commerce\CommerceContentEntityStorage;

/**
 * Defines the gift attribute value storage.
 */
class GiftAttributeValueStorage extends CommerceContentEntityStorage implements GiftAttributeValueStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function loadByAttribute($attribute_id) {
    $entity_query = $this->getQuery();
    $entity_query->condition('attribute', $attribute_id);
    $entity_query->sort('weight');
    $entity_query->sort('name');
    $result = $entity_query->execute();
    return $result ? $this->loadMultiple($result) : [];
  }

}
