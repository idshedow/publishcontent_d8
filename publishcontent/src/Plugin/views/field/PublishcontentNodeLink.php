<?php

/**
 * @file
 * Definition of Drupal\d8views\Plugin\views\field\NodeTypeFlagger.
 */

namespace Drupal\publishcontent\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\UrlGeneratorTrait;
use Drupal\node\Entity\NodeType;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Routing\UrlGenerator;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("publishcontent_node_link")
 */
class PublishcontentNodeLink extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();
  }

  /**
   * Define the available options.
   * @return array
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['publish'] = $options['unpublish'] = array('default' => '', 'translatable' => TRUE);
    return $options;
  }

  /**
   * Define the view option form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    unset($form['text']);
    $form['publish'] = array(
      '#type' => 'textfield',
      '#title' => t('Text to display for publishing'),
      '#default_value' => $this->options['publish'],
    );
    $form['unpublish'] = array(
      '#type' => 'textfield',
      '#title' => t('Text to display for unpublishing'),
      '#default_value' => $this->options['unpublish'],
    );
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    $z='';
    $node = $values->_entity;
    $link_text = '';
    $url = NULL;
    // Ensure user has access to change status of this node.
    if ($node->status->value == PUBLISHCONTENT_NODE_IS_PUBLISHED
      && publishcontent_unpublish_access($node)) {
      // $op = 'unpublish';
      $link_text = $this->options['unpublish'] ? $this->options['unpublish'] : t('Unpublish');
      $url = Url::fromRoute('publishcontent.unpublish_tab', array('node' => $node->id()));
    }

   if ($node->status->value == PUBLISHCONTENT_NODE_IS_NOT_PUBLISHED
     && publishcontent_publish_access($node)) {
     // $op = 'publish';
     $link_text = $this->options['publish'] ? $this->options['publish'] : t('Publish');
     $url = Url::fromRoute('publishcontent.publish_tab', array('node' => $node->id()));
   }
    // If user has no access to change status of node render empty string.
       // $link = Link::createFromRoute('Publish', 'publishcontent.publish_tab',array('node' => $node->nid->value));
    // return $link->toString();
    return \Drupal::l($link_text, $url);
  }
}
