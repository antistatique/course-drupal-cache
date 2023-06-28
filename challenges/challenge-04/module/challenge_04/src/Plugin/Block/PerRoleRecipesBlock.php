<?php

namespace Drupal\challenge_04\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Per Role Recipes Collection block.
 *
 * @Block(
 *     id="challenge_04_recipes_block",
 *     admin_label=@Translation("Per Role Recipes Collection block")
 * )
 */
class PerRoleRecipesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, AccountInterface $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->entityTypeManager = $entity_type_manager;
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
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $renderer = [
      '#theme' => 'challenge_04___recipes_collection_block',
      '#recipes' => [],
    ];

    $query = $this->nodeStorage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->sort('created', 'DESC');

    $difficulty = match (TRUE) {
      $this->currentUser->isAuthenticated() && !empty(array_intersect(['administrator'], $this->currentUser->getRoles())) => 'hard',
      $this->currentUser->isAuthenticated() => 'medium',
      $this->currentUser->isAnonymous() => 'easy',
      default => 'easy',
    };

    $query->condition('field_difficulty', $difficulty, '=');

    $nids = $query->execute();

    if (!$nids || !\is_array($nids)) {
      return $renderer;
    }

    $recipes = $this->nodeStorage->loadMultiple($nids);

    foreach ($recipes as $recipe) {
      $recipe_output = $this->entityTypeManager
        ->getViewBuilder('node')
        ->view($recipe, 'teaser');
      $renderer['#recipes'][] = $recipe_output;
    }

    return $renderer;
  }

}
