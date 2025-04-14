<?php
namespace Drupal\current_time\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\CachedStorage;


class SiteLocation extends ConfigFormBase {
  protected function getEditableConfigNames() {
    return [
      'current_time.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sitelocation_form';
  }

  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('current_time.settings');
    $form['country'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('Country'),
      '#required' => TRUE,
    );
    $form['city'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('City'),
      '#required' => TRUE,
    );
    $form['timezone'] = array(
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#default_value' => $config->get('Timezone') ?: '',
      '#options' => [
        '' => $this->t('- Select Timezone -'),
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'), 
        'Europe/London' => $this->t('Europe/London'),
      ],
      '#required' => TRUE,
    );
    

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('current_time.settings')
      ->set('Country', $form_state->getValue('country'))
      ->set('City', $form_state->getValue('city'))
      ->set('Timezone', $form_state->getValue('timezone'))
      ->save();
  }

}

