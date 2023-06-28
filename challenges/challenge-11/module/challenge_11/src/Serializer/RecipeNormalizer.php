<?php

namespace Drupal\challenge_11\Serializer;

use Drupal\node\NodeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Turns Node Recipe objects into arrays (normalize).
 */
class RecipeNormalizer implements NormalizerInterface {

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = []) {
    /** @var \Drupal\node\NodeInterface $object */

    return [
      'nid' => (int) $object->id(),
      'title' => $object->get('title')->getString(),
      'url' => $object->toUrl('canonical', ['absolute' => TRUE])->toString(TRUE),
      'isPublished' => $object->isPublished(),
      'difficulty' => $object->field_difficulty->value,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, $format = NULL) {
    return $data instanceof NodeInterface && $data->bundle() === 'recipe';
  }

}
