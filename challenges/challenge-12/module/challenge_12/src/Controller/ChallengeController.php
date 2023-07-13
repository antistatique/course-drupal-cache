<?php

namespace Drupal\challenge_12\Controller;

use Drupal\Core\Controller\ControllerBase;

class ChallengeController extends ControllerBase {
  public function index(): array {
    return [
      '#type' => 'item',
      '#attached' => [
        'library' => [
          'challenge_12/weather',
        ],
      ],
    ];
  }

}
