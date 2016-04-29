<?php

/**
 * @file
 * Contains Drupal\publishcontent\Controller\ToggleStatusController;
 */

namespace Drupal\publishcontent\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Controller class for publish/unpublish page.
 */
class ToggleStatusController extends ControllerBase {

  /**
   * Overriding default constructor.
   */
  public function __construct() {
    $this->arguments = explode('/', (\Drupal::request()->getPathInfo()));
  }

  /**
   * Change status of a node to the opposite.
   */
  public function toggleStatus() {
    if ($this->arguments[1] == 'node' && is_numeric($this->arguments[2])) {
      $node = entity_load('node', $this->arguments[2]);
    }
    else {
      $node = NULL;
    }

    // XOR the current status with 1 to get the opposite value.
    $node->status->value = $node->status->value ^ 1;

    // Save the status we want to set.
    $status = $node->status->value;

    // Try to update the node.
    $node->save();

    // Validate the status has changed.
    if ($status == $node->status->value) {
      // Everything went well.
      drupal_set_message(_publishcontent_get_message($node->nid->value, $node->title->value, $node->status->value));
    }
    else {
      // Prevent the user something went wrong.
      drupal_set_message(t('The status of the node could not be updated.'), 'error');
    }

    return new RedirectResponse($_SERVER['HTTP_REFERER']);
  }

}
