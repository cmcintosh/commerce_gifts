<?php

namespace Drupal\commerce_gift\Event;

final class GiftEvents {

  /**
   * Name of the event fired after loading a gift.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_LOAD = 'commerce_gift.commerce_gift.load';

  /**
   * Name of the event fired after creating a new gift.
   *
   * Fired before the gift is saved.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_CREATE = 'commerce_gift.commerce_gift.create';

  /**
   * Name of the event fired before saving a gift.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_PRESAVE = 'commerce_gift.commerce_gift.presave';

  /**
   * Name of the event fired after saving a new gift.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_INSERT = 'commerce_gift.commerce_gift.insert';

  /**
   * Name of the event fired after saving an existing gift.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_UPDATE = 'commerce_gift.commerce_gift.update';

  /**
   * Name of the event fired before deleting a gift.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_PREDELETE = 'commerce_gift.commerce_gift.predelete';

  /**
   * Name of the event fired after deleting a gift.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_DELETE = 'commerce_gift.commerce_gift.delete';

  /**
   * Name of the event fired after saving a new gift translation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_TRANSLATION_INSERT = 'commerce_gift.commerce_gift.translation_insert';

  /**
   * Name of the event fired after deleting a gift translation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftEvent
   */
  const GIFT_TRANSLATION_DELETE = 'commerce_gift.commerce_gift.translation_delete';

  /**
   * Name of the event fired after changing the gift variation via ajax.
   *
   * Allows modules to add arbitrary ajax commands to the response returned by
   * the add to cart form #ajax callback.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationAjaxChangeEvent
   */
  const GIFT_VARIATION_AJAX_CHANGE = 'commerce_gift.commerce_gift_variation.ajax_change';

  /**
   * Name of the event fired after loading a gift variation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_LOAD = 'commerce_gift.commerce_gift_variation.load';

  /**
   * Name of the event fired after creating a new gift variation.
   *
   * Fired before the gift variation is saved.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_CREATE = 'commerce_gift.commerce_gift_variation.create';

  /**
   * Name of the event fired before saving a gift variation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_PRESAVE = 'commerce_gift.commerce_gift_variation.presave';

  /**
   * Name of the event fired after saving a new gift variation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_INSERT = 'commerce_gift.commerce_gift_variation.insert';

  /**
   * Name of the event fired after saving an existing gift variation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_UPDATE = 'commerce_gift.commerce_gift_variation.update';

  /**
   * Name of the event fired before deleting a gift variation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_PREDELETE = 'commerce_gift.commerce_gift_variation.predelete';

  /**
   * Name of the event fired after deleting a gift variation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_DELETE = 'commerce_gift.commerce_gift_variation.delete';

  /**
   * Name of the event fired after saving a new gift variation translation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_TRANSLATION_INSERT = 'commerce_gift.commerce_gift_variation.translation_insert';

  /**
   * Name of the event fired after deleting a gift variation translation.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\GiftVariationEvent
   */
  const GIFT_VARIATION_TRANSLATION_DELETE = 'commerce_gift.commerce_gift_variation.translation_delete';

  /**
   * Name of the event fired when filtering variations.
   *
   * @Event
   *
   * @see \Drupal\commerce_gift\Event\FilterVariationsEvent
   */
  const FILTER_VARIATIONS = "commerce_gift.filter_variations";

}
