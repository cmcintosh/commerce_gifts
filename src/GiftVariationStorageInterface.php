<?php

namespace Drupal\commerce_gift;

use Drupal\commerce_gift\Entity\GiftInterface;

/**
 * Defines the interface for gift variation storage.
 */
interface GiftVariationStorageInterface {

  /**
   * Loads the variation from context.
   *
   * Uses the variation specified in the URL (?v=) if it's active and
   * belongs to the current gift.
   *
   * Note: The returned variation is not guaranteed to be enabled, the caller
   * needs to check it against the list from loadEnabled().
   *
   * @param \Drupal\commerce_gift\Entity\GiftInterface $gift
   *   The current gift.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface
   *   The gift variation.
   */
  public function loadFromContext(GiftInterface $gift);

  /**
   * Loads the enabled variations for the given gift.
   *
   * Enabled variations are active variations that have been filtered through
   * the FILTER_VARIATIONS event.
   *
   * @param \Drupal\commerce_gift\Entity\GiftInterface $gift
   *   The gift.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface[]
   *   The enabled variations.
   */
  public function loadEnabled(GiftInterface $gift);

}
