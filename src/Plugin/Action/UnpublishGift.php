<?php

namespace Drupal\commerce_gift\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Unpublishes a gift.
 *
 * @Action(
 *   id = "commerce_unpublish_gift",
 *   label = @Translation("Unpublish selected gift"),
 *   type = "commerce_gift"
 * )
 */
class UnpublishGift extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /** @var \Drupal\commerce_gift\Entity\GiftInterface $entity */
    $entity->setPublished(FALSE);
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\commerce_gift\Entity\GiftInterface $object */
    $access = $object
      ->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $access : $access->isAllowed();
  }

}
