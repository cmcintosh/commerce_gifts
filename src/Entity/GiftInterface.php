<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\commerce_store\Entity\EntityStoresInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\commerce_product\Entity\ProductInterface;

/**
 * Defines the interface for gifts.
 */
interface GiftInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface, EntityStoresInterface {

  /**
   * Gets the gift title.
   *
   * @return string
   *   The gift title
   */
  public function getTitle();

  /**
   * Sets the gift title.
   *
   * @param string $title
   *   The gift title.
   *
   * @return $this
   */
  public function setTitle($title);

  /**
   * Get whether the gift is published.
   *
   * Unpublished gifts are only visible to their authors and administrators.
   *
   * @return bool
   *   TRUE if the gift is published, FALSE otherwise.
   */
  public function isPublished();

  /**
   * Sets whether the gift is published.
   *
   * @param bool $published
   *   Whether the gift is published.
   *
   * @return $this
   */
  public function setPublished($published);

  /**
   * Gets the gift creation timestamp.
   *
   * @return int
   *   The gift creation timestamp.
   */
  public function getCreatedTime();

  /**
   * Sets the gift creation timestamp.
   *
   * @param int $timestamp
   *   The gift creation timestamp.
   *
   * @return $this
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the variation IDs.
   *
   * @return int[]
   *   The variation IDs.
   */
  public function getVariationIds();

  /**
   * Gets the variations.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface[]
   *   The variations.
   */
  public function getVariations();

  /**
   * Sets the variations.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface[] $variations
   *   The variations.
   *
   * @return $this
   */
  public function setVariations(array $variations);

  /**
   * Gets whether the gift has variations.
   *
   * A gift must always have at least one variation, but a newly initialized
   * (or invalid) gift entity might not have any.
   *
   * @return bool
   *   TRUE if the gift has variations, FALSE otherwise.
   */
  public function hasVariations();

  /**
   * Adds a variation.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface $variation
   *   The variation.
   *
   * @return $this
   */
  public function addVariation(GiftVariationInterface $variation);

  /**
   * Removes a variation.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface $variation
   *   The variation.
   *
   * @return $this
   */
  public function removeVariation(GiftVariationInterface $variation);

  /**
   * Checks whether the gift has a given variation.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface $variation
   *   The variation.
   *
   * @return bool
   *   TRUE if the variation was found, FALSE otherwise.
   */
  public function hasVariation(GiftVariationInterface $variation);

  /**
   * Gets the default variation.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface
   *   The default variation.
   */
  public function getDefaultVariation();

}
