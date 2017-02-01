<?php

namespace Drupal\commerce_gift\ConfigTranslation;

use Drupal\config_translation\ConfigEntityMapper;

/**
 * Provides a configuration mapper for gift attributes.
 */
class GiftAttributeMapper extends ConfigEntityMapper {

  /**
   * {@inheritdoc}
   */
  public function getAddRoute() {
    $route = parent::getAddRoute();
    $route->setDefault('_form', '\Drupal\commerce_gift\Form\GiftAttributeTranslationAddForm');
    return $route;
  }

  /**
   * {@inheritdoc}
   */
  public function getEditRoute() {
    $route = parent::getEditRoute();
    $route->setDefault('_form', '\Drupal\commerce_gift\Form\GiftAttributeTranslationEditForm');
    return $route;
  }

}
