<?php

namespace Drupal\pets_owners_storage\Controller;

/**
 * Show Thank You page.
 */
class ThankYouPage
{
  /**
   * Returns a Thank You page.
   */
  public function thankyouPage() {
    $element = [
      '#markup' => 'Data submitted',
    ];
    return $element;
  }
}
