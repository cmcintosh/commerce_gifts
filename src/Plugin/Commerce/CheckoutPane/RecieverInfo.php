<?php

namespace Drupal\commerce_gift\Plugin\Commerce\CheckoutPane;

use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneBase;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutPane\CheckoutPaneInterface;
use Drupal\commerce_checkout\Plugin\Commerce\CheckoutFlow\CheckoutFlowInterface;

/**
 * Provides the information pane for sending gifts.
 *
 * @CommerceCheckoutPane(
 *   id = "reciever_information",
 *   label = @Translation("Gift Recipient information"),
 *   default_step = "order_information",
 *   wrapper_element = "fieldset",
 * )
 */
class RecieverInfo extends CheckoutPaneBase implements CheckoutPaneInterface, ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new BillingInformation object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\commerce_checkout\Plugin\Commerce\CheckoutFlow\CheckoutFlowInterface $checkout_flow
   *   The parent checkout flow.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CheckoutFlowInterface $checkout_flow, EntityTypeManagerInterface $entity_type_manager, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $checkout_flow);

    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, CheckoutFlowInterface $checkout_flow = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $checkout_flow,
      $container->get('entity_type.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildPaneSummary() {
    $summary = '';
    $params = Url::fromUri("internal:" . \Drupal::request()->getRequestUri() )->getRouteParameters();
    $default = $this->getDefaultValues($params);

    if ($default) {
      $summary = t('Sending a gift to %name, at %email', [
        '%name' => $default->first_name . ' ' . $default->last_name,
        '%email' => $default->email
      ]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function buildPaneForm(array $pane_form, FormStateInterface $form_state, array &$complete_form) {

    if (!($this->orderHasGift())) {
      // no need to continue
      return $pane_form;
    }

    // We need to find the default values.
    $defaults = $this->getDefaultValues($form_state->getValues());
    $pane_form['#prefix'] = '<div id="gift-getter-wrapper"><div class="gift-message"></div>';
    $pane_form['#suffix'] = '</div>';
    $params = Url::fromUri("internal:" . \Drupal::request()->getRequestUri() )->getRouteParameters();
    $pane_form['commerce_order'] = [
      '#type' => 'value',
      '#value' => $params['commerce_order']
      ];
    $pane_form['email'] = [
      '#type' => 'email',
      '#title' => t('Email'),
      '#required' => TRUE,
      '#default_value' => $defaults->email,
    ];

    $pane_form['first_name'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#required' => TRUE,
      '#default_value' => $defaults->first_name
    ];

    $pane_form['last_name'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#required' => TRUE,
      '#default_value' => $defaults->last_name
    ];

    return $pane_form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitPaneForm(array &$pane_form, FormStateInterface $form_state, array &$complete_form = []) {
    $values = $form_state->getValues()['reciever_information'];
    $data = [
      'order_id'    => $values['commerce_order'],
      'email'       => $values['email'],
      'first_name'  => $values['first_name'],
      'last_name'   => $values['last_name']
    ];
    db_merge('commerce_gift_order')
      ->key(array('order_id' => $data['order_id']))
      ->fields($data)
      ->execute();

  }

  /**
  * perform a query and return results
  */
  private function getDefaultValues($values) {
    $params = Url::fromUri("internal:" . \Drupal::request()->getRequestUri() )->getRouteParameters();

    $defaults = null;
    $default_entity = null;

    if ( $params['commerce_order'] ) {
      $query = \Drupal::database()
        ->select('commerce_gift_order', 'go');
      $query->condition('go.order_id', $params['commerce_order'], '=' );
      $query->fields('go', [
        'order_id',
        'first_name',
        'last_name',
        'email'
      ]);
      $result = $query->execute()->fetchAllAssoc('order_id');
      return isset($result[$params['commerce_order']]) ? $result[$params['commerce_order']] : null;
    }
  }

  /**
  * Returns if the current order contains a gift.
  */
  private function orderHasGift() {
    $params = Url::fromUri("internal:" . \Drupal::request()->getRequestUri() )->getRouteParameters();
    $order = \Drupal::entityTypeManager()
      ->getStorage('commerce_order')
      ->load($params['commerce_order']);

    $items = $order->getItems();
    // Loop throuh and see if one of the purchasable entities is a gift
    foreach($items as $item) {
      $entity = $item->getPurchasedEntity();
      if (is_a($entity, 'Drupal\commerce_gift\Entity\GiftVariation')) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
