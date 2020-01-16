<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Access\AccessResult;

/**
 * Route that show page with data.
 */
class ShowStorage {

  /**
   * Show data from database table.
   */
  public function display() {

    /**
     * Creating table header.
     */
    $header_table = [
      'id' => t('ID'),
      'prefix' => t('Prefix'),
      'name' => t('Name'),
      'email' => t('Email'),
      'gender' => t('Gender'),
      'age' => t('Age'),
      'father_name' => t('Fathers name'),
      'mother_name' => t('Mothers name'),
      'pets_bool' => t('Pets?'),
      'pets_names' => t('Pets names'),
      'edit',
      'delete',
    ];

    /**
     * Query for data select.
     */
    $query = \Drupal::database()
      ->select('pets_owners_storage', 'e')
      ->fields('e', [
        'id',
        'name',
        'gender',
        'prefix',
        'age',
        'father_name',
        'mother_name',
        'pets_bool',
        'pets_names',
        'email',
      ]);
    $data = $query->execute()->fetchAll();

    /**
     * Rows for future table.
     */
    $rows=[];
    foreach($data as $row){
      $prefix = '';
      if ($row->prefix == '1') $prefix = 'mr';
      elseif ($row->prefix == '2') $prefix = 'mrs';
      elseif ($row->prefix == '3') $prefix = 'ms';

      $gender = '';
      if ($row->gender == '0') $gender = 'Male';
      elseif ($row->gender == '1') $gender = 'Female';
      elseif ($row->gender == '2') $gender = 'Unknown';

      $rows[] = [
        'id' => $row->id,
        'prefix' => $prefix,
        'name' => $row->name,
        'email' => $row->email,
        'gender' => $gender,
        'age' => $row->age,
        'father_name' => $row->father_name,
        'mother_name' => $row->mother_name,
        'pets_bool' => $pets_bool = ($row->pets_bool) ? 'Yes' : 'No',
        'pets_names' => $row->pets_names,
        Link::fromTextAndUrl('Edit', Url::fromUserInput('/pets_owners_form?id='.$row->id)),
        Link::fromTextAndUrl('Delete', Url::fromUserInput('/pets_owners_storage/delete/'.$row->id)),
      ];
    }

    $form['text_header'] = [
      '#prefix' => '<a href="/pets_owners_form/" class="button">',
      '#suffix' => '</a>',
      '#markup' => 'Add Record',
    ];

    /**
     * Create table.
     */
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No data found'),
    ];
    return $form;
  }

  /**
   * Custom access permission
   */
  public function access() {
    $user = \Drupal::currentUser();
    if (!in_array('authenticated', $user->getRoles())) {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }

}
