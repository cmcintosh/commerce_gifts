<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\commerce\Entity\CommerceBundleEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Defines the interface for gift types.
 */
interface GiftTypeInterface extends CommerceBundleEntityInterface, EntityDescriptionInterface {

  /**
   * Gets the gift type's matching variation type ID.
   *
   * @return string
   *   The variation type ID.
   */
  public function getVariationTypeId();

  /**
   * Sets the gift type's matching variation type ID.
   *
   * @param string $variation_type_id
   *   The variation type ID.
   *
   * @return $this
   */
  public function setVariationTypeId($variation_type_id);

  /**
   * Gets whether variation fields should be injected into the rendered gift.
   *
   * @return bool
   *   TRUE if the variation fields should be injected into the rendered
   *   gift, FALSE otherwise.
   */
  public function shouldInjectVariationFields();

  /**
   * Sets whether variation fields should be injected into the rendered gift.
   *
   * @param bool $inject
   *   Whether variation fields should be injected into the rendered gift.
   *
   * @return $this
   */
  public function setInjectVariationFields($inject);

}
