<?php

namespace Drupal\challenge_03\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\StateInterface;

/**
 * Recipes Collection block.
 *
 * @Block(
 *   id="challenge_03_recipes_block",
 *   admin_label = @Translation("Recipes Collection block")
 * )
 */
class RecipesCollectionBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->entityTypeManager = $entity_type_manager;
    $this->state = $state;
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
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $renderer = [
      '#theme' => 'challenge_03___recipes_collection_block',
      '#recipes' => [],
    ];

    $query = $this->nodeStorage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->sort('created', 'DESC');

    $settings = $this->state->get('challenge_03.settings');

    if (isset($settings['filtered_collection']['category'])) {
      $query->condition('field_recipe_category', $settings['filtered_collection']['category'], '=');
    }

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
