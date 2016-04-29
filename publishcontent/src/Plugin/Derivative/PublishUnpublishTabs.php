<?php
/**
 * @file
 * Contains Drupal\publishcontent\Plugin\Derivative\PublishUnpublishTabs.
 */

namespace Drupal\publishcontent\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Defines when to show publish/unpublish link.
 */
class PublishUnpublishTabs extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (_publishcontent_get_method() == PUBLISHCONTENT_METHOD_TABS) {
      $this->derivatives['publishcontent.tabs'] = $base_plugin_definition;
      $this->derivatives['publishcontent.tabs']['base_route'] = 'entity.node.canonical';
      return $this->derivatives;
    }
  }
}
