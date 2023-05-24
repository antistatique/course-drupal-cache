<?php

namespace Drupal\challenge_01\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Personalized per user block.
 *
 * @Block(
 *   id="challenge_01_personalized_per_user_block",
 *   admin_label = @Translation("Personalized per user block")
 * )
 */
class PersonalizedPerUserBlock extends BlockBase {
  public function build() {
    $funny_emojis = ['🤷‍', '🙈', '🐏', '😹'];

    return [
      '#markup' => $this->t('<p>Today\'s funny emoji just for you: @emoji</p>
        <p>(Hand picked at @time!)</p>', [
          '@emoji' => $funny_emojis[array_rand($funny_emojis)],
        '@time' => (int) microtime(TRUE),
      ]),
    ];
  }

  public function getCacheContexts() {
    return ['user'];
  }

  public function getCacheMaxAge() {
    return 86400;
  }

}
