<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Route that show page with data.
 */
class ShowOwners {

  const PREFIX = [
    '1' => 'mr',
    '2' => 'mrs',
    '3' => 'ms',
  ];

  const GENDER = [
    '0' => 'Male',
    '1' => 'Female',
    '2' => 'Unknown',
    ];

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
      'edit' => t(''),
      'delete' => t(''),
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
      $rows[] = [
        'id' => $row->id,
        'prefix' => self::PREFIX[$row->prefix],
        'name' => $row->name,
        'email' => $row->email,
        'gender' => self::GENDER[$row->gender],
        'age' => $row->age,
        'father_name' => $row->father_name,
        'mother_name' => $row->mother_name,
        'pets_bool' => $pets_bool = ($row->pets_bool) ? 'Yes' : 'No',
        'pets_names' => $row->pets_names,
        Link::fromTextAndUrl('Edit', Url::fromUserInput('/admin/pets_owners/edit/'.$row->id)),
        Link::fromTextAndUrl('Delete', Url::fromUserInput('/admin/pets_owners/delete/'.$row->id)),
      ];
    }

    $form['link'] = [
      '#title' => t('Add Record'),
      '#type' => 'link',
      '#url' => Url::fromRoute('pets_owners_storage.show_form'),
      '#attributes' => [
        'class' => [
          'button',
        ],
      ],
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
}
