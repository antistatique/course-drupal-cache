<?php

namespace Drupal\challenge_01\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Uncacheable block.
 *
 * @Block(
 *     id="challenge_01_uncacheable_block",
 *     admin_label=@Translation("Uncacheable block")
 * )
 */
class UncacheableBlock extends BlockBase {
  public function build() {
    usleep(500 * 1000); // Pretend we're computing a better forecast 👍

    return [
      '#markup' => $this->t('<p>Weather forecast at @time: ☔️</p>', [
        '@time' => (int) microtime(TRUE),
      ]),
    ];
  }

  public function getCacheMaxAge() {
    return 0;
  }

}
