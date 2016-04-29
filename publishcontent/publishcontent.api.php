<?php

/**
 * @file
 * Describe hooks provided by the publishcontent module.
 */

use \Drupal\Component\Utility\SafeMarkup;

/**
 * Allow other modules the ability to modify access to the publish controls.
 *
 * Modules may implement this hook if they want to have a say in whether or not
 * a given user has access to perform publish action on a node.
 *
 * @param node $node
 *   A node object being checked
 * @param user $account
 *   The user wanting to publish the node.
 *
 * @return bool|NULL
 *   PUBLISHCONTENT_ACCESS_ALLOW - if the account can publish the node
 *   PUBLISHCONTENT_ACCESS_DENY - if the user definetley can not publish
 *   PUBLISHCONTENT_ACCESS_IGNORE - This module wan't change the outcome.
 *   It is typically better to return IGNORE than DENY. If no module returns
 *   ALLOW then the account will be denied publish access. If one module
 *   returns DENY then the user will denied even if another module returns
 *   ALLOW.
 */
function hook_publishcontent_publish_access($node, $account) {
  $access = !$node->isPublished() && (Drupal::currentUser()->hasPermission('administer nodes')
    || Drupal::currentUser()->hasPermission('publish any content')
    || (Drupal::currentUser()->hasPermission('publish own content') && $account->id() == $node->uid->target_id)
    || (Drupal::currentUser()->hasPermission('publish editable content') && (!isset($node->nid->value) || $node->access('update', $account)))
    || (Drupal::currentUser()->hasPermission('publish own ' . SafeMarkup::checkPlain($node->type->target_id) . ' content') && $account->id() == $node->uid->target_id)
    || (Drupal::currentUser()->hasPermission('publish any ' . SafeMarkup::checkPlain($node->type->target_id) . ' content'))
    || (Drupal::currentUser()->hasPermission('publish editable ' . SafeMarkup::checkPlain($node->type->target_id) . ' content') && (!isset($node->nid->value) || $node->access('update', $account)))
  );

  if ($access) {
    // The user can publish the node according to this hook.
    // If another hook denys access they will be denied.
    return PUBLISHCONTENT_ACCESS_ALLOW;
  }

  // This function does not believe they can publish but is
  // not explicitly denying access to publish. If no other hooks
  // allow it then the user will be denied.
  return PUBLISHCONTENT_ACCESS_IGNORE;
}

/**
 * Allow other modules the ability to modify access to the unpublish controls.
 *
 * Modules may implement this hook if they want to have a say in whether or not
 * a given user has access to perform unpublish action on a node.
 *
 * @param node $node
 *   A node object being checked
 * @param user $account
 *   The user wanting to unpublish the node.
 *
 * @return bool|NULL
 *   PUBLISHCONTENT_ACCESS_ALLOW - if the user can unpublish the node.
 *   PUBLISHCONTENT_ACCESS_DENY - if the user definetley cannot unpublish.
 *   PUBLISHCONTENT_ACCESS_IGNORE - This module wan't change the outcome.
 *   It is typically better to return IGNORE than DENY. If no module returns
 *   ALLOW then the user will be denied access. If one module returns
 *   DENY then the user will denied even if another module returns
 *   ALLOW.
 */
function hook_publishcontent_unpublish_access($node, $account) {
  $access = $node->isPublished() && (Drupal::currentUser()->hasPermission('administer nodes')
    || Drupal::currentUser()->hasPermission('unpublish any content')
    || (Drupal::currentUser()->hasPermission('unpublish own content') && $account->id() == $node->uid->target_id)
    || (Drupal::currentUser()->hasPermission('unpublish editable content') && (!isset($node->nid->value) || $node->access('update', $account)))
    || (Drupal::currentUser()->hasPermission('unpublish own ' . SafeMarkup::checkPlain($node->type->target_id) . ' content') && $account->id() == $node->uid->target_id)
    || (Drupal::currentUser()->hasPermission('unpublish any ' . SafeMarkup::checkPlain($node->type->target_id) . ' content'))
    || (Drupal::currentUser()->hasPermission('unpublish editable ' . SafeMarkup::checkPlain($node->type->target_id) . ' content') && (!isset($node->nid->value) || $node->access('update', $account)))
  );

  if ($access) {
    // The user is allowed to unpublish the node according to this hook.
    // If another hook denys access they will be denied.
    return PUBLISHCONTENT_ACCESS_ALLOW;
  }

  // This function does not believe they can publish but is
  // not explicitly denying access to publish. If no other hooks
  // allow it then the user will be denied.
  return PUBLISHCONTENT_ACCESS_IGNORE;
}
