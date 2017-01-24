<?php

namespace Drupal\commerce_gift\Event;

use Drupal\commerce_gift\Entity\GiftInterface;
use Symfony\Component\EventDispatcher\Event;

class FilterVariationsEvent extends Event {

  /**
   * The parent gift.
   *
   * @var \Drupal\commerce_gift\Entity\GiftInterface
   */
  protected $gift;

  /**
   * The enabled variations.
   *
   * @var array
   */
  protected $variations;

  /**
   * Constructs a new FilterVariationsEvent object.
   *
   * @param \Drupal\commerce_gift\Entity\GiftInterface $gift
   *   The gift.
   * @param array $variations
   *   The enabled variations.
   */
  public function __construct(GiftInterface $gift, array $variations) {
    $this->gift = $gift;
    $this->variations = $variations;
  }

  /**
   * Sets the enabled variations.
   *
   * @param array $variations
   *   The enabled variations.
   */
  public function setVariations(array $variations) {
    $this->variations = $variations;
  }

  /**
   * Gets the enabled variations.
   *
   * @return array
   *   The enabled variations.
   */
  public function getVariations() {
    return $this->variations;
  }

}
