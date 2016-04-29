<?php

/**
 * @file
 * Contains \Drupal\publishcontent\Form\PublishcontentConfigForm.
 */

namespace Drupal\publishcontent\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\Context\ContextInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PublishcontentConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'publishcontent.settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['publishcontent.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    if (!is_null($this->config('publishcontent.settings')->get('publishcontent_method'))) {
      $case = $this->config('publishcontent.settings')->get('publishcontent_method');
    }
    else {
      $case = PUBLISHCONTENT_METHOD_TABS;
    }
    $form['publishcontent_method'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Quick publish method'),
      '#default_value' => $case,
      '#description' => $this->t('Choose the quick links method. With no quick links, the published checkbox will still appear on the node edit form. Note that a Drupal cache clear is required after changing this.'),
      '#options' => array(
        PUBLISHCONTENT_METHOD_NONE => $this->t('None.'),
        PUBLISHCONTENT_METHOD_BUTTON => $this->t('Button.'),
        PUBLISHCONTENT_METHOD_TABS => $this->t('Tabs.'),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('publishcontent.settings')
      ->set('publishcontent_method', $form_state->getValue('publishcontent_method'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
