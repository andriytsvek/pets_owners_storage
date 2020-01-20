<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\pets_owners_form\Form\ShowForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

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
    $form= parent::buildForm($form,  $form_state);

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
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

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

    \Drupal::database()
      ->insert('pets_owners_storage')
      ->fields($fields)
      ->execute();

    $url = Url::fromRoute('pets_owners_storage.show_thankyou');
    $form_state->setRedirectUrl($url);
  }

}
