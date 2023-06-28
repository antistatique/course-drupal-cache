<?php

namespace Drupal\challenge_08\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Star Wars block.
 *
 * @Block(
 *     id="challenge_08_starwars_block",
 *     admin_label=@Translation("Star Wars block")
 * )
 */
class StarWarsBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    try {
      $response = \Drupal::httpClient()->get('https://swapi.dev/api/planets');
      // $response = \Drupal::httpClient()->get('https://swapi.dev/planets');
    }
    catch (\Exception $e) {
      return [
        '#markup' => $this->t('<p>The SWAPI API fails.</p>'),
      ];
    }

    $planets = json_decode($response->getBody()->getContents(), TRUE);

    $list = array_map(static function ($element): string {
      return $element['name'];
    }, $planets['results']);

    return [
      '#theme' => 'item_list',
      '#items' => $list,
    ];
  }

}
