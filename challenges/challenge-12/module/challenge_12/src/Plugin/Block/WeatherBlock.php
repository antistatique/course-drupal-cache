<?php

namespace Drupal\challenge_12\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Weather block.
 *
 * @Block(
 *     id="challenge_12_weather_block",
 *     admin_label=@Translation("Weather block")
 * )
 */
class WeatherBlock extends BlockBase {
  public function build() {
    usleep(10 * 1000000); // Pretend we're computing a better forecast 👍

    return [
      '#markup' => '<div id="weahter-block">' . $this->t('Hello World, I am just a text.') . '</div>',
    ];
  }

  public function getCacheMaxAge() {
    return 0;
  }

}
