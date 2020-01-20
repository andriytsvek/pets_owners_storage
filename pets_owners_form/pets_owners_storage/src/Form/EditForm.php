<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class EditForm extends ShowStorageForm {

  /**
   * ID of the item to delete.
   */
  protected $id;

  /**
   * Get form.
   */
  public function getFormId() {
    return 'pets_owners_edit_form';
  }

  /**
   * Build Form
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;

    $form= parent::buildForm($form,  $form_state);

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
      ->condition('e.id', $this->id);

    $data = $query->execute()->fetchAssoc();

    $form['name']['#default_value'] = (isset($data['name'])) ? $data['name'] : '';
    $form['gender']['#default_value'] = (isset($data['gender'])) ? $data['gender'] : '';
    $form['prefix']['#default_value'] = (isset($data['prefix'])) ? $data['prefix'] : '';
    $form['age']['#default_value'] = (isset($data['age'])) ? $data['age'] : '';
    $form['parents']['fathers_name']['#default_value'] = (isset($data['father_name'])) ? $data['father_name'] : '';
    $form['parents']['mothers_name']['#default_value'] = (isset($data['mother_name'])) ? $data['mother_name'] : '';
    $form['have_pets']['#default_value'] = (isset($data['pets_bool'])) ? $data['pets_bool'] : '';
    $form['pets_names']['#default_value'] = (isset($data['pets_names'])) ? $data['pets_names'] : '';
    $form['email']['#default_value'] = (isset($data['email'])) ? $data['email'] : '';
    $form['link'] = [
      '#title' => $this->t('All Records'),
      '#type' => 'link',
      '#url' => Url::fromRoute('pets_owners_storage.show_data'),
      '#attributes' => [
        'class' => [
          'button',
        ],
      ],
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
   * Submit Form and delete the record in database
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

    $query = \Drupal::database()
      ->select('pets_owners_storage', 'e')
      ->fields('e', [
        'name',
      ])
      ->condition('e.id', $this->id);
    $rows = $query->countQuery()->execute()->fetchField();

    /**
     * If 'id' exist then Update record, else Insert record
     */
    if ($rows > 0) {
      \Drupal::database()
        ->update('pets_owners_storage')
        ->fields($fields)
        ->condition('id', $this->id)
        ->execute();
      $this->messenger()->addStatus($this->t('Data Updated'));
    }
    else {
      \Drupal::database()
        ->insert('pets_owners_storage')
        ->fields($fields)
        ->execute();
      $this->messenger()->addStatus($this->t('Thank you'));
    }
  }
}
