<?php

/**
 * Implements hook_views_data_alter().
 *
 * Add items to the node table that are relevant to publishcontent.
 */
function publishcontent_views_data_alter(&$data) {
  $data['node']['publishcontent'] = array(
    'title' => t('Publish link'),
    'field' => array(
      'title' => t('Publish link'),
      'help' => t('Display a link to publish the node.'),
      'id' => 'publishcontent_node_link',
    ),
  );
}
