<?php

namespace Drupal\commerce_gift\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the gift attribute entity class.
 *
 * @ConfigEntityType(
 *   id = "commerce_gift_attribute",
 *   label = @Translation("Gift attribute"),
 *   label_singular = @Translation("gift attribute"),
 *   label_plural = @Translation("gift attributes"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gift attribute",
 *     plural = "@count gift attributes",
 *   ),
 *   handlers = {
 *     "access" = "Drupal\commerce\EntityAccessControlHandler",
 *     "permission_provider" = "Drupal\commerce\EntityPermissionProvider",
 *     "list_builder" = "Drupal\commerce_gift\GiftAttributeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\commerce_gift\Form\GiftAttributeForm",
 *       "edit" = "Drupal\commerce_gift\Form\GiftAttributeForm",
 *       "delete" = "Drupal\commerce_gift\Form\GiftAttributeDeleteForm",
 *     },
 *     "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "commerce_gift_attribute",
 *   admin_permission = "administer commerce_gift_attribute",
 *   bundle_of = "commerce_gift_attribute_value",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "elementType"
 *   },
 *   links = {
 *     "add-form" = "/admin/commerce/gift-attributes/add",
 *     "edit-form" = "/admin/commerce/gift-attributes/manage/{commerce_gift_attribute}",
 *     "delete-form" = "/admin/commerce/gift-attributes/manage/{commerce_gift_attribute}/delete",
 *     "collection" =  "/admin/commerce/gift-attributes",
 *   }
 * )
 */
class GiftAttribute extends ConfigEntityBundleBase implements GiftAttributeInterface {

  /**
   * The attribute ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The attribute label.
   *
   * @var string
   */
  protected $label;

  /**
   * The attribute element type.
   *
   * @var string
   */
  protected $elementType = 'select';

  /**
   * {@inheritdoc}
   */
  public function getValues() {
    $storage = $this->entityTypeManager()->getStorage('commerce_gift_attribute_value');
    return $storage->loadByAttribute($this->id());
  }

  /**
   * {@inheritdoc}
   */
  public function getElementType() {
    return $this->elementType;
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {
    /** @var \Drupal\commerce_gift\Entity\GiftAttributeInterface[] $entities */
    parent::postDelete($storage, $entities);

    // Delete all associated values.
    $values = [];
    foreach ($entities as $entity) {
      foreach ($entity->getValues() as $value) {
        $values[$value->id()] = $value;
      }
    }
    /** @var \Drupal\Core\Entity\EntityStorageInterface $value_storage */
    $value_storage = \Drupal::service('entity_type.manager')->getStorage('commerce_gift_attribute_value');
    $value_storage->delete($values);
  }

}
