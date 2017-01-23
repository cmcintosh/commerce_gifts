<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\commerce\Entity\CommerceBundleEntityBase;

/**
 * Defines the gift type entity class.
 *
 * @ConfigEntityType(
 *   id = "commerce_gift_type",
 *   label = @Translation("Gift type"),
 *   label_singular = @Translation("gift type"),
 *   label_plural = @Translation("gift types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gift type",
 *     plural = "@count gift types",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\commerce_gift\GiftTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_gift\Form\GiftTypeForm",
 *       "edit" = "Drupal\commerce_gift\Form\GiftTypeForm",
 *       "delete" = "Drupal\commerce_gift\Form\GiftTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "commerce_gift_type",
 *   admin_permission = "administer commerce_gift_type",
 *   bundle_of = "commerce_gift",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "variationType",
 *     "injectVariationFields",
 *     "traits",
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/config/gift-types/add",
 *     "edit-form" = "/admin/commerce/config/gift-types/{commerce_gift_type}/edit",
 *     "delete-form" = "/admin/commerce/config/gift-types/{commerce_gift_type}/delete",
 *     "collection" = "/admin/commerce/config/gift-types"
 *   }
 * )
 */
class GiftType extends CommerceBundleEntityBase implements GiftTypeInterface {

  /**
   * The gift type description.
   *
   * @var string
   */
  protected $description;

  /**
   * The variation type ID.
   *
   * @var string
   */
  protected $variationType;

  /**
   * Indicates if variation fields should be injected.
   *
   * @var bool
   */
  protected $injectVariationFields = TRUE;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVariationTypeId() {
    return $this->variationType;
  }

  /**
   * {@inheritdoc}
   */
  public function setVariationTypeId($variation_type_id) {
    $this->variationType = $variation_type_id;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldInjectVariationFields() {
    return $this->injectVariationFields;
  }

  /**
   * {@inheritdoc}
   */
  public function setInjectVariationFields($inject) {
    $this->injectVariationFields = (bool) $inject;
    return $this;
  }

}
