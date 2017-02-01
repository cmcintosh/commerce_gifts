<?php

namespace Drupal\commerce_gift;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the entity view builder for gifts.
 */
class GiftViewBuilder extends EntityViewBuilder {

  /**
   * The gift field variation renderer.
   *
   * @var \Drupal\commerce_gift\GiftVariationFieldRenderer
   */
  protected $variationFieldRenderer;

  /**
   * Constructs a new GiftViewBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\commerce_gift\GiftVariationFieldRenderer $variation_field_renderer
   *   The gift variation field renderer.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityManagerInterface $entity_manager, LanguageManagerInterface $language_manager, GiftVariationFieldRenderer $variation_field_renderer) {
    parent::__construct($entity_type, $entity_manager, $language_manager);
    $this->variationFieldRenderer = $variation_field_renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager'),
      $container->get('language_manager'),
      $container->get('commerce_gift.variation_field_renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    $gift_type_storage = $this->entityManager->getStorage('commerce_gift_type');
    $variation_storage = $this->entityManager->getStorage('commerce_gift_variation');

    /** @var \Drupal\commerce_gift\Entity\GiftTypeInterface $gift_type */
    $gift_type = $gift_type_storage->load($entity->bundle());
    if ($gift_type->shouldInjectVariationFields() && $entity->getDefaultVariation()) {
      $variation = $variation_storage->loadFromContext($entity);
      $rendered_fields = $this->variationFieldRenderer->renderFields($variation, $view_mode);
      foreach ($rendered_fields as $field_name => $rendered_field) {
        // Group attribute fields to allow them to be excluded together.
        if (strpos($field_name, 'attribute_') !== FALSE) {
          $build['variation_attributes']['variation_' . $field_name] = $rendered_field;
        }
        else {
          $build['variation_' . $field_name] = $rendered_field;
        }
      }
    }
  }

}
