<?php

namespace Drupal\commerce_gift\Event;

use Drupal\commerce_gift\Entity\GiftVariationInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\EventDispatcher\Event;

/**
 * Defines the gift variation ajax change event.
 *
 * @see \Drupal\commerce_gift\Event\GiftEvents
 */
class GiftVariationAjaxChangeEvent extends Event {

  /**
   * The gift variation.
   *
   * @var \Drupal\commerce_gift\Entity\GiftVariationInterface
   */
  protected $giftVariation;

  /**
   * The ajax response.
   *
   * @var \Drupal\Core\Ajax\AjaxResponse
   */
  protected $response;

  /**
   * The view mode.
   *
   * @var string
   */
  protected $viewMode;

  /**
   * Constructs a new GiftVariationAjaxChangeEvent.
   *
   * @param \Drupal\commerce_gift\Entity\GiftVariationInterface $gift_variation
   *   The gift variation.
   * @param \Drupal\Core\Ajax\AjaxResponse $response
   *   The ajax response.
   * @param string $view_mode
   *   The view mode used to render the gift variation.
   */
  public function __construct(GiftVariationInterface $gift_variation, AjaxResponse $response, $view_mode = 'default') {
    $this->giftVariation = $gift_variation;
    $this->response = $response;
    $this->viewMode = $view_mode;
  }

  /**
   * The gift variation.
   *
   * @return \Drupal\commerce_gift\Entity\GiftVariationInterface
   *   The gift variation.
   */
  public function getGiftVariation() {
    return $this->giftVariation;
  }

  /**
   * The ajax response.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The ajax reponse.
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * The view mode used to render the gift variation.
   *
   * @return string
   *   The view mode.
   */
  public function getViewMode() {
    return $this->viewMode;
  }

}
