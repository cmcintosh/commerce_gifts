<?php

namespace Drupal\commerce_gift;

use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides #lazy_builder callbacks.
 */
class GiftLazyBuilders {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity form builder.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilderInterface
   */
  protected $entityFormBuilder;

  /**
   * Constructs a new CartLazyBuilders object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entity_form_builder
   *   The entity form builder.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityFormBuilderInterface $entity_form_builder) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityFormBuilder = $entity_form_builder;
  }

  /**
   * Builds the add to cart form.
   *
   * @param string $gift_id
   *   The gift ID.
   * @param string $view_mode
   *   The view mode used to render the gift.
   * @param bool $combine
   *   TRUE to combine order items containing the same gift variation.
   *
   * @return array
   *   A renderable array containing the cart form.
   */
  public function addToCartForm($gift_id, $view_mode, $combine) {
    /** @var \Drupal\commerce_order\OrderItemStorageInterface $order_item_storage */
    $order_item_storage = $this->entityTypeManager->getStorage('commerce_order_item');

    /** @var \Drupal\commerce_gift\Entity\GiftInterface $gift */
    $gift = $this->entityTypeManager->getStorage('commerce_gift')->load($gift_id);
    $variation = $gift->getDefaultVariation();
    $values = [
      'type' => 'gift',
      'title' => $variation->getOrderItemTitle,
      'purchased_entity' => $variation,
      'unit_price' => $variation->getPrice()
    ];
    $order_item = $order_item_storage->create($values);

    $form_state_additions = [
      'product' => $gift,
      'gift' => $gift,
      'view_mode' => $view_mode,
      'settings' => [
        'combine' => $combine,
      ],
    ];
    return $this->entityFormBuilder->getForm($order_item, 'add_to_cart', $form_state_additions);
  }

}
