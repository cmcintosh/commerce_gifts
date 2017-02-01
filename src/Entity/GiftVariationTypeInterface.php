<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\commerce\Entity\CommerceBundleEntityInterface;

/**
 * Defines the interface for gift variation types.
 */
interface GiftVariationTypeInterface extends CommerceBundleEntityInterface {

  /**
   * Gets the gift variation type's order item type ID.
   *
   * Used for finding/creating the appropriate order item when purchasing a
   * gift (adding it to an order).
   *
   * @return string
   *   The order item type ID.
   */
  public function getOrderItemTypeId();

  /**
   * Sets the gift variation type's order item type ID.
   *
   * @param string $order_item_type_id
   *   The order item type ID.
   *
   * @return $this
   */
  public function setOrderItemTypeId($order_item_type_id);

  /**
   * Gets whether the gift variation title should be automatically generated.
   *
   * @return bool
   *   Whether the gift variation title should be automatically generated.
   */
  public function shouldGenerateTitle();

  /**
   * Sets whether the gift variation title should be automatically generated.
   *
   * @param bool $generate_title
   *   Whether the gift variation title should be automatically generated.
   *
   * @return $this
   */
  public function setGenerateTitle($generate_title);

}
