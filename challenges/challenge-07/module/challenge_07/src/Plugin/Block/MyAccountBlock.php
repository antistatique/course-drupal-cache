<?php

namespace Drupal\challenge_07\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * My Account block.
 *
 * @Block(
 *     id="challenge_07_myaccount_block",
 *     admin_label=@Translation("My Account block")
 * )
 */
class MyAccountBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $is_anonymous = $this->currentUser->isAuthenticated();
    $display_name = $this->currentUser->getDisplayName();
    $user_id = $this->currentUser->id();

    return [
      '#theme' => 'challenge_07__myaccount_block',
      '#is_anonymous' => $is_anonymous,
      '#display_name' => $display_name,
      '#user_id' => $user_id,
    ];
  }

}
