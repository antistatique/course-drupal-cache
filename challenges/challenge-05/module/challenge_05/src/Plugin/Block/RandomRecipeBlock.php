<?php

namespace Drupal\challenge_05\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

/**
 * Random Recipe block.
 *
 * @Block(
 *   id="challenge_05_random_recipe_block",
 *   admin_label = @Translation("Random Recipe block")
 * )
 */
class RandomRecipeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, Connection $connection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->entityTypeManager = $entity_type_manager;
    $this->connection = $connection;
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
      $container->get('database')

    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $renderer = [
      '#theme' => 'challenge_05___random_recipe_block',
      '#recipe' => null,
      '#time' => (int) microtime(TRUE),
    ];

    $select = $this->connection->select('node_field_data', 'n')
      ->fields('n', ['nid'])
      ->condition('n.type', 'recipe')
      ->condition('n.status', 1)
      ->range(0, 1)
      ->orderRandom();

    $results = $select->execute();
    $nid = $results->fetchField();

    $recipe = $this->nodeStorage->load($nid);
    $recipe_output = $this->entityTypeManager
      ->getViewBuilder('node')
      ->view($recipe, 'teaser');
    $renderer['#recipe'] = $recipe_output;

    return $renderer;
  }

}
