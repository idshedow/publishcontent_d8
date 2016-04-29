<?php

/**
 * @file
 * Creating of publishcontent permissions for each node type.
 */

namespace Drupal\publishcontent;

use Drupal\node\Entity\NodeType;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Routing\UrlGeneratorTrait;

/**
 * PublishcontentPermissions class.
 */
class PublishcontentPermissions {

  use StringTranslationTrait;
  use UrlGeneratorTrait;

  /**
   * Generate publishcontent permissions for all node types.
   */
  public function nodeTypePermissions() {
    $perms = array();
    foreach (NodeType::loadMultiple() as $type) {
      $perms += $this->buildPermissions($type);
    }
    return $perms;
  }

  /**
   * Building of permissions.
   */
  protected function buildPermissions(NodeType $type) {
    $type_id = $type->id();
    $type_params = array('%type_name' => $type->label());

    return array(
      "publish any $type_id content" => array(
        'title' => $this->t('Publish any %type_name content', $type_params),
      ),
      "publish own $type_id content" => array(
        'title' => $this->t('Publish own %type_name content', $type_params),
      ),
      "publish editable $type_id content" => array(
        'title' => $this->t('Publish editable %type_name content', $type_params),
      ),
      "unpublish any $type_id content" => array(
        'title' => $this->t('Unpublish any %type_name content', $type_params),
      ),
      "unpublish own $type_id content" => array(
        'title' => $this->t('Unpublish own %type_name content', $type_params),
      ),
      "unpublish editable $type_id content" => array(
        'title' => $this->t('Unpublish editable %type_name content', $type_params),
      ),
    );
  }

}
