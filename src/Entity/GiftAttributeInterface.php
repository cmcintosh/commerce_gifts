<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines the interface for gift attributes.
 */
interface GiftAttributeInterface extends ConfigEntityInterface {

  /**
   * Gets the attribute values.
   *
   * @return \Drupal\commerce_gift\Entity\GiftAttributeValueInterface[]
   *   The attribute values.
   */
  public function getValues();

  /**
   * Gets the attribute element type.
   *
   * @return string
   *   The element type name.
   */
  public function getElementType();

}
