<?php

namespace Drupal\challenge_10\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleExtensionList;

class ChallengeController extends ControllerBase {

  public function index(): array {
    return [
      '#markup' => ''
    ];
  }


}
