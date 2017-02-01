<?php

namespace Drupal\commerce_gift\Event;

use Drupal\commerce_gift\Entity\GiftInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the gift event.
 *
 * @see \Drupal\commerce_gift\Event\GiftEvents
 */
class GiftEvent extends Event {

  /**
   * The gift.
   *
   * @var \Drupal\commerce_gift\Entity\GiftInterface
   */
  protected $gift;

  /**
   * Constructs a new GiftEvent.
   *
   * @param \Drupal\commerce_gift\Entity\GiftInterface $gift
   *   The gift.
   */
  public function __construct(GiftInterface $gift) {
    $this->gift = $gift;
  }

  /**
   * Gets the gift.
   *
   * @return \Drupal\commerce_gift\Entity\GiftInterface
   *   The gift.
   */
  public function getGift() {
    return $this->gift;
  }

}
