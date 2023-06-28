<?php

namespace Drupal\challenge_03\Form;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\taxonomy\TermStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Challenge settings form.
 *
 * @internal
 */
final class ChallengeSettingsForm extends ConfigFormBase {

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The taxonomy term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $settings_factory, StateInterface $state, TermStorageInterface $termStorage) {
    parent::__construct($settings_factory);
    $this->state = $state;
    $this->termStorage = $termStorage;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->state->get('challenge_03.settings');
    $form['#tree'] = TRUE;

    $form['global'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Global'),
    ];

    $form['filtered_collection'] = [
      '#type' => 'details',
      '#group' => 'global',
      '#title' => $this->t('Filtered collection'),
    ];

    $recipe_categories = $this->termStorage->loadTree('recipe_category', 0, 1, TRUE);
    $options = [];

    foreach ($recipe_categories as $recipe_category) {
      $options[$recipe_category->id()] = $recipe_category->getName();
    }

    $form['filtered_collection']['category'] = [
      '#type' => 'select',
      'required' => TRUE,
      '#title' => $this->t('Recipe category'),
      '#options' => $options,
      '#default_value' => $settings['filtered_collection']['category'] ?? NULL,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @psalm-suppress ArgumentTypeCoercion
   * @psalm-suppress PossiblyNullArgument
   * @psalm-suppress MissingReturnType
   * @psalm-suppress PossiblyNullReference
   */
  public static function create(ContainerInterface $container) {
    return new self(
      $container->get('config.factory'),
      $container->get('state'),
      $container->get('entity_type.manager')->getStorage('taxonomy_term')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'challenge_03_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values = $form_state->getValues();
    $settings = $this->state->get('challenge_03.settings');

    $category = Xss::filter($values['filtered_collection']['category'], NULL);
    $settings['filtered_collection']['category'] = $category;

    $this->state->set('challenge_03.settings', $settings);

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['challenge_03.settings'];
  }

}
