<?php

namespace Drupal\commerce_gift\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Ensures gift variation SKU uniqueness.
 *
 * @Constraint(
 *   id = "GiftVariationSku",
 *   label = @Translation("The SKU of the gift variation.", context = "Validation")
 * )
 */
class GiftVariationSkuConstraint extends Constraint {

  public $message = 'The SKU %sku is already in use and must be unique.';

}
