<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\pets_owners_form\Form\ShowForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Route for our module.
 */
class ShowStorageForm extends ShowForm {

  /**
   * Get form.
   */
  public function getFormId() {
    return 'pets_owners_storage';
  }

  /**
   * Build form. Add data to the form if the record already exist or user Edit the record
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    if (isset($_GET['id'])) {
      $query = \Drupal::database()
        ->select('pets_owners_storage', 'e')
        ->fields('e', [
          'name',
          'gender',
          'prefix',
          'age',
          'father_name',
          'mother_name',
          'pets_bool',
          'pets_names',
          'email',
        ])
        ->condition('e.id', $_GET['id']);

        $data = $query->execute()->fetchAssoc();
    }

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($data['name']) && $_GET['id']) ? $data['name'] : '',
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
      '#default_value' => (isset($data['gender']) && $_GET['id']) ? $data['gender'] : '',
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
      '#default_value' => (isset($data['prefix']) && $_GET['id']) ? $data['prefix'] : '',
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => t('Age:'),
      '#required' => TRUE,
      '#default_value' => (isset($data['age']) && $_GET['id']) ? $data['age'] : '',
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
      '#default_value' => (isset($data['father_name']) && $_GET['id']) ? $data['father_name'] : '',
    ];
    $form['parents']['mothers_name'] = [
      '#type' => 'textfield',
      '#title' => t('Mother\'s name:'),
      '#default_value' => (isset($data['mother_name']) && $_GET['id']) ? $data['mother_name'] : '',
    ];
    $form['have_pets'] = [
      '#type' => 'checkbox',
      '#title' => t('Have you some pets?'),
      '#default_value' => (isset($data['pets_bool']) && $_GET['id']) ? $data['pets_bool'] : '',
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
      '#default_value' => (isset($data['pets_names']) && $_GET['id']) ? $data['pets_names'] : '',
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Email:'),
      '#required' => TRUE,
      '#default_value' => (isset($data['email']) && $_GET['id']) ? $data['email'] : '',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['text_header'] = [
      '#prefix' => '<a href="/pets_owners_storage/" class="button">',
      '#suffix' => '</a>',
      '#markup' => 'View All Records',
    ];

    return $form;
  }

  /**
   * Validate form. Get from parent
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * Submit form. Update or Insert data in database table 'pets_owners_storage'/
   * SECURITY QUESTION ABOUT INSERT???????????????????
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fields = [
      'name' => $form_state->getValue('name'),
      'gender' => $form_state->getValue('gender'),
      'prefix' => $form_state->getValue('prefix'),
      'age' => $form_state->getValue('age'),
      'father_name' => $form_state->getValue('fathers_name'),
      'mother_name' => $form_state->getValue('mothers_name'),
      'pets_bool' => $form_state->getValue('have_pets'),
      'pets_names' => $form_state->getValue('pets_names'),
      'email' => $form_state->getValue('email'),
    ];

    /**
     * If 'id' isset in URL then check it in database
     */
    $query = \Drupal::database()
      ->select('pets_owners_storage', 'e')
      ->fields('e', [
        'name',
      ])
      ->condition('e.id', $_GET['id']);
    $rows = $query->countQuery()->execute()->fetchField();

    /**
     * If 'id' exist then Update record, else Insert record
     */
    if (isset($_GET['id']) &&  $rows > 0) {
      \Drupal::database()
        ->update('pets_owners_storage')
        ->fields($fields)
        ->condition('id', $_GET['id'])
        ->execute();
      $this->messenger()->addStatus($this->t('Data Updated'));
    } else {
      \Drupal::database()
        ->insert('pets_owners_storage')
        ->fields($fields)
        ->execute();
      $this->messenger()->addStatus($this->t('Thank you'));
    }
  }
}
