<?php

namespace Drupal\challenge_09\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Recipes by Country block.
 *
 * @Block(
 *     id="challenge_09_recipes_by_country_block",
 *     admin_label=@Translation("Recipes by Country block")
 * )
 */
class RecipesByCountryBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
      '#theme' => 'item_list',
      '#items' => [],
      '#cache' => [
        'tags' => [
          'node_list:recipe',
        ],
      ],
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
      $renderer['#items'][] = $recipe_output;
    }

    return $renderer;
  }

}
