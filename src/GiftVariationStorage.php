<?php

namespace Drupal\commerce_gift;

use Drupal\commerce\CommerceContentEntityStorage;
use Drupal\commerce_gift\Entity\GiftInterface;
use Drupal\commerce_gift\Event\FilterVariationsEvent;
use Drupal\commerce_gift\Event\FilterVariationEvent;
use Drupal\commerce_gift\Event\GiftEvents;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Defines the gift variation storage.
 */
class GiftVariationStorage extends CommerceContentEntityStorage implements GiftVariationStorageInterface {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new GiftVariationStorage object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection to be used.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend to be used.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(EntityTypeInterface $entity_type, Connection $database, EntityManagerInterface $entity_manager, CacheBackendInterface $cache, LanguageManagerInterface $language_manager, EventDispatcherInterface $event_dispatcher, RequestStack $request_stack) {
    parent::__construct($entity_type, $database, $entity_manager, $cache, $language_manager, $event_dispatcher);

    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('database'),
      $container->get('entity.manager'),
      $container->get('cache.entity'),
      $container->get('language_manager'),
      $container->get('event_dispatcher'),
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function loadFromContext(GiftInterface $gift) {
    $current_request = $this->requestStack->getCurrentRequest();
    if ($variation_id = $current_request->query->get('v')) {
      if (in_array($variation_id, $gift->getVariationIds())) {
        /** @var \Drupal\commerce_gift\Entity\GiftVariationInterface $variation */
        $variation = $this->load($variation_id);
        if ($variation->isActive()) {
          return $variation;
        }
      }
    }
    return $gift->getDefaultVariation();
  }

  /**
   * {@inheritdoc}
   */
  public function loadEnabled(GiftInterface $gift) {
    $ids = [];
    foreach ($gift->variations as $variation) {
      $ids[$variation->target_id] = $variation->target_id;
    }
    // Speed up loading by filtering out the IDs of disabled variations.
    $query = $this->getQuery()
      ->condition('status', TRUE)
      ->condition('variation_id', $ids, 'IN');
    $result = $query->execute();
    if (empty($result)) {
      return [];
    }
    // Restore the original sort order.
    $result = array_intersect_key($ids, $result);

    $enabled_variations = $this->loadMultiple($result);
    // Allow modules to apply own filtering (based on date, stock, etc).
    // $event = new FilterVariationsEvent($gift, $enabled_variations);
    // $this->eventDispatcher->dispatch(GiftEvents::FILTER_VARIATIONS, $event);
    // $enabled_variations = $event->getVariations();

    return $enabled_variations;
  }

}
