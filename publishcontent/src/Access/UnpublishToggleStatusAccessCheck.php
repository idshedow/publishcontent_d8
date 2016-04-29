<?php
/**
 * @file
 * Contains Drupal\publishcontent\Access\UnpublishToggleStatusAccessCheck.
 */

namespace Drupal\publishcontent\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Access checking for unpublish page.
 */
class UnpublishToggleStatusAccessCheck implements AccessInterface {

  public function __construct() {
    $this->arguments = explode('/', (\Drupal::request()->getPathInfo()));
  }

  public function access() {
    if ($this->arguments[1] == 'node' && is_numeric($this->arguments[2])) {
      $node = entity_load('node', $this->arguments[2]);
      if (!is_object($node)) {
        return AccessResult::forbidden();
      }
    }
    else {
      return AccessResult::forbidden();
    }
    return $node->status->value && publishcontent_unpublish_access($node) ? AccessResult::allowed() : AccessResult::forbidden();
  }
}
