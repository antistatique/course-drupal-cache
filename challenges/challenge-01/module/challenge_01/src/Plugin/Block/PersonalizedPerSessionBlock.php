<?php

namespace Drupal\challenge_01\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Personalized per session block.
 *
 * @Block(
 *   id="challenge_01_personalized_per_session_block",
 *   admin_label = @Translation("Personalized per session block")
 * )
 */
class PersonalizedPerSessionBlock extends BlockBase {
  public function build() {
    $funny_emojis = ['🤷‍', '🙈', '🐏', '😹'];

    return [
      '#markup' => $this->t('<p>Funny emoji just for you: @emoji</p>
        <p>(Hand picked at @time!)</p>', [
          '@emoji' => $funny_emojis[array_rand($funny_emojis)],
        '@time' => (int) microtime(TRUE),
      ]),
    ];
  }

  public function getCacheContexts() {
    return ['session'];
  }

}
