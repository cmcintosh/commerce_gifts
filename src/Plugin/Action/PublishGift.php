<?php

namespace Drupal\commerce_gift\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Publishes a gift.
 *
 * @Action(
 *   id = "commerce_publish_gift",
 *   label = @Translation("Publish selected gift"),
 *   type = "commerce_gift"
 * )
 */
class PublishGift extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /** @var \Drupal\commerce_gift\Entity\GiftInterface $entity */
    $entity->setPublished(TRUE);
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\commerce_gift\Entity\GiftInterface $object */
    $result = $object
      ->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
