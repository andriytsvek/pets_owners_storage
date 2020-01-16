<?php

namespace Drupal\pets_owners_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Route for our module.
 */
class ShowForm extends FormBase {

  /**
   * Get form.
   */
  public function getFormId() {
  	return 'pets_owners_form';
  }

  /**
   * Build newsletter subscription form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#required' => TRUE,
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => t('Gender:'),
      '#options' => [
        0 => $this
          ->t('Male'),
        1 => $this
          ->t('Female'),
        2 => $this
          ->t('Unknown'),
      ],
    ];
    $form['prefix'] = [
      '#type' => 'select',
      '#title' => $this
        ->t('Prefix:'),
      '#options' => [
        '1' => $this
          ->t('mr'),
        '2' => $this
          ->t('mrs'),
        '3' => $this
          ->t('ms'),
      ],
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => t('Age:'),
      '#required' => TRUE,
    ];
    $form['parents'] = [
      '#type' => 'fieldset',
      '#title' => t('Parents:'),
      '#states' => [
        'visible' => [
          ':input[name="age"]' => [['value' => "1"],['value' => "2"],['value' => "3"],['value' => "4"],],
        ],
      ],
    ];
    $form['parents']['fathers_name'] = [
      '#type' => 'textfield',
      '#title' => t('Father\'s name:'),
    ];
    $form['parents']['mothers_name'] = [
      '#type' => 'textfield',
      '#title' => t('Mother\'s name:'),
    ];
    $form['have_pets'] = [
      '#type' => 'checkbox',
      '#title' => t('Have you some pets?'),
    ];
    $form['pets_names'] = [
      '#type' => 'textfield',
      '#title' => t('Names of your pets:'),
      '#states' => [
        'visible' => [
          ':input[name="have_pets"]' => [
          	'checked' => TRUE
          ],
        ],
      ],
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Email:'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Validate form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  	if (strlen($form_state->getValue('name')) > 100) {
  	  $form_state->setErrorByName('name', $this->t('Name is too large.'));
    }
    if (($form_state->getValue('age') < 0)  || ($form_state->getValue('age') > 120)) {
  	  $form_state->setErrorByName('age', $this->t('The wrong age!'));
    }
   }

  /**
   * Submit form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  	$this->messenger()->addStatus($this->t('Thank you'));
  }
}
