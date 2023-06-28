<?php

namespace Drupal\challenge_11\Controller;

use Drupal\challenge_11\Serializer\RecipeNormalizer;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

class RecipesEndpointController extends ControllerBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * Construct a new RecipiesEndpointController object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function index(Request $request): JsonResponse {
    $q = $request->query->get('q');
    $difficulty = $request->query->get('difficulty');

    $data = [];
    $response = new JsonResponse($data);

    $query = $this->nodeStorage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->sort('created', 'DESC');

    if ($q) {
      $query->condition('title', $q, 'CONTAINS');
    }

    if ($difficulty) {
      $query->condition('field_difficulty', $difficulty, '=');
    }

    $nids = $query->execute();

    if (!$nids || !\is_array($nids)) {
      $response->setStatusCode(500);

      return $response;
    }

    $normalizer = new RecipeNormalizer();
    $serializer = new Serializer([$normalizer], []);

    $recipes = $this->nodeStorage->loadMultiple($nids);

    foreach ($recipes as $recipe) {
      $data[] = $serializer->normalize($recipe);
    }
    $response->setData($data);

    $response->setStatusCode(200);

    return $response;
  }

}
