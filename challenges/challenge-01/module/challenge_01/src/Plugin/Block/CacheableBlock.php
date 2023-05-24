<?php

namespace Drupal\challenge_01\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\user\Entity\User;

/**
 * Cacheable block.
 *
 * @Block(
 *   id="challenge_01_cacheable_block",
 *   admin_label = @Translation("Cacheable block")
 * )
 */
class CacheableBlock extends BlockBase {
  public function build() {
    return [
      '#markup' => $this->t('%name runs this site!.', [
        '%name' => User::load(1)->getAccountName(),
      ]),
    ];
  }

  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->id() == 1);
  }

  public function getCacheTags() {
    return ['user:1'];
  }

}
