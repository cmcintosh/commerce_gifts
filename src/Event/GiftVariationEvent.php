<?php

namespace Drupal\commerce_gift\Event;

use Drupal\commerce_gift\Entity\GiftVariationInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the gift variation event.
 *
 * @see \Drupal\commerce_gift\Event\GiftEvents
 */
class GiftVariationEvent extends Event {

  /**
   * The gift variation.
   *
   * @var \Drupal\commerce_gift\Entity\GiftVariationInterface
   */
  protected $giftVariation;

  /**
   * Constructs a new GiftVariationEvent.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface $gift_variation
   *   The gift variation.
   */
  public function __construct(GiftVariationInterface $gift_variation) {
    $this->giftVariation = $gift_variation;
  }

  /**
   * Gets the gift variation.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface
   *   The gift variation.
   */
  public function getGiftVariation() {
    return $this->giftVariation;
  }

}
