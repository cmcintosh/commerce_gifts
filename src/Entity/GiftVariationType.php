<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\commerce\Entity\CommerceBundleEntityBase;

/**
 * Defines the gift variation type entity class.
 *
 * @ConfigEntityType(
 *   id = "commerce_gift_variation_type",
 *   label = @Translation("Gift variation type"),
 *   label_singular = @Translation("gift variation type"),
 *   label_plural = @Translation("gift variation types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gift variation type",
 *     plural = "@count gift variation types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\commerce_gift\GiftVariationTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_gift\Form\GiftVariationTypeForm",
 *       "edit" = "Drupal\commerce_gift\Form\GiftVariationTypeForm",
 *       "delete" = "Drupal\commerce_gift\Form\GiftVariationTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "commerce_gift_variation_type",
 *   admin_permission = "administer commerce_gift_type",
 *   bundle_of = "commerce_gift_variation",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "orderItemType",
 *     "generateTitle",
 *     "traits",
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/config/gift-variation-types/add",
 *     "edit-form" = "/admin/commerce/config/gift-variation-types/{commerce_gift_variation_type}/edit",
 *     "delete-form" = "/admin/commerce/config/gift-variation-types/{commerce_gift_variation_type}/delete",
 *     "collection" =  "/admin/commerce/config/gift-variation-types"
 *   }
 * )
 */
class GiftVariationType extends CommerceBundleEntityBase implements GiftVariationTypeInterface {

  /**
   * The order item type ID.
   *
   * @var string
   */
  protected $orderItemType;

  /**
   * Whether the gift variation title should be automatically generated.
   *
   * @var bool
   */
  protected $generateTitle;

  /**
   * {@inheritdoc}
   */
  public function getOrderItemTypeId() {
    return $this->orderItemType;
  }

  /**
   * {@inheritdoc}
   */
  public function setOrderItemTypeId($order_item_type_id) {
    $this->orderItemType = $order_item_type_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldGenerateTitle() {
    return (bool) $this->generateTitle;
  }

  /**
   * {@inheritdoc}
   */
  public function setGenerateTitle($generate_title) {
    $this->generateTitle = $generate_title;
  }

}
