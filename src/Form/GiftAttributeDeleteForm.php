<?php

namespace Drupal\commerce_gift\Form;

use Drupal\Core\Entity\EntityDeleteForm;

/**
 * Builds the form to delete a gift attribute.
 */
class GiftAttributeDeleteForm extends EntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Deleting a gift attribute will delete all of its values. This action cannot be undone.');
  }

}
