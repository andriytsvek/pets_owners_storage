<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class DeleteForm extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected $id;

  /**
   * Build Form
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit Form and delete the record in database
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::database()
      ->delete('pets_owners_storage')
      ->condition('id',$this->id)
      ->execute();

    $this->messenger()->addStatus($this->t('Record deleted'));

    $form_state->setRedirect('pets_owners_storage.show_data');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "confirm_delete_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('pets_owners_storage.show_data');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Do you want to delete %id?', ['%id' => $this->id]);
  }

}
