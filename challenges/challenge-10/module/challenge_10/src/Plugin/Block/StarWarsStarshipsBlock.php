<?php

namespace Drupal\challenge_10\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Star Wars Starships block.
 *
 * @Block(
 *   id="challenge_10_starwars_starships_block",
 *   admin_label = @Translation("Star Wars Starships block")
 * )
 */
class StarWarsStarshipsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    \Drupal::service('page_cache_kill_switch')->trigger();

    try {
      $response = \Drupal::httpClient()->get('https://swapi.dev/api/starships');
      // $response = \Drupal::httpClient()->get('https://swapi.dev/starships');

      $data = $response->getBody()->getContents();
    } catch (\Exception $e) {
      return [
        '#markup' => $this->t('<p>The SWAPI API fails.</p>'),
      ];
    }
    $planets = json_decode($data, TRUE);

    $list = array_map(function($element): string {
      return $element['name'];
    }, $planets['results']);

    $wrapper = [
      '#type' => 'container',
    ];

    $wrapper[] = [
      '#markup' => $this->t('Hand picked at @time!</p>', [
        '@time' => (int) microtime(TRUE),
      ]),
    ];

    $wrapper[] = [
      '#theme' => 'item_list',
      '#items' => $list,
    ];

    return $wrapper;
  }

  /**
   * Mark this block uncacheable by Drupal Dynamic Page Cache.
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
