<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Core\Access\AccessResult;

/**
 * Custom permissions class.
 */
class AccessPermission
{
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
