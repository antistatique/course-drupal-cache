<?php

namespace Drupal\challenge_02\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Recipes Collection block.
 *
 * @Block(
 *     id="challenge_02_recipes_block",
 *     admin_label=@Translation("Recipes Collection block")
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
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->entityTypeManager = $entity_type_manager;
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
      // Parent Menu block.
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $renderer = [
      '#theme' => 'challenge_02___recipes_collection_block',
      '#recipes' => [],
    ];

    $query = $this->nodeStorage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->sort('created', 'DESC');

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
