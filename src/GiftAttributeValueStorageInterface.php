<?php

namespace Drupal\commerce_gift;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the interface for gift attribute value storage.
 */
interface GiftAttributeValueStorageInterface extends ContentEntityStorageInterface {

  /**
   * Loads gift attribute values for the given gift attribute.
   *
   * @param string $attribute_id
   *   The gift attribute ID.
   *
   * @return \Drupal\commerce_gift\Entity\GiftAttributeValueInterface[]
   *   The gift attribute values, indexed by id, ordered by weight.
   */
  public function loadByAttribute($attribute_id);

}
