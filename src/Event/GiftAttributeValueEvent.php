<?php

namespace Drupal\commerce_gift\Event;

use Drupal\commerce_gift\Entity\GiftAttributeValueInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the gift attribute value event.
 *
 * @see \Drupal\commerce_gift\Event\GiftEvents
 */
class GiftAttributeValueEvent extends Event {

  /**
   * The gift attribute value.
   *
   * @var \Drupal\commerce_gift\Entity\GiftAttributeValueInterface
   */
  protected $attributeValue;

  /**
   * Constructs a new GiftAttributeValueEvent.
   *
   * @param \Drupal\commerce_gift\Entity\GiftAttributeValueInterface $attribute_value
   *   The gift attribute value.
   */
  public function __construct(GiftAttributeValueInterface $attribute_value) {
    $this->attributeValue = $attribute_value;
  }

  /**
   * Gets the gift attribute value.
   *
   * @return \Drupal\commerce_gift\Entity\GiftAttributeValueInterface
   *   The gift attribute value.
   */
  public function getAttributeValue() {
    return $this->attributeValue;
  }

}
