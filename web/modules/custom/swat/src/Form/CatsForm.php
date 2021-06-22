<?php
/**
 *@file
 *Contains \Drupal\swat\Form\CollectPhone.
 */
namespace Drupal\swat\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * @see \Drupal\Core\Form\FormBase
 */
class CatsForm extends FormBase {

  public function getFormId() {
    return 'cats_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your cat’s name:'),
      '#description' => $this->t('The minimum length of the name is 2 characters, and the maximum is 32 '),
      '#required' => TRUE,
//      '#ajax' => [
//        'callback' => '::myAjaxCallback',
//        'event' => 'change',
//      ],
    ];

    $form['action']['#type'] = 'actions';

    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => 100,
    ];

    $form['action']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#button_type' => 'primary',
      '#ajax' => [
        'callback' => '::myAjaxCallback',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ]
    ];

    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!preg_match('/^[A-Za-z]*$/', $form_state->getValue('title'))){
        $form_state->setErrorByName('title', $this->t('Use only letters A-Za-z'));
    }
    if (strlen($form_state->getValue('title')) < 2){
        $form_state->setErrorByName('title', $this->t('Name is too short.'));
    }
    if (strlen($form_state->getValue('title')) > 32){
      $form_state->setErrorByName('title', $this->t('Name is too looong.'));
    }
    else {
      $this->messenger()->deleteAll();
    }
  }

  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $message = [
      '#theme' => 'status_messages',
      '#message_list' => $this->messenger()->all(),
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
      ],
    ];
    $messages = \Drupal::service('renderer')->render($message);
    $ajax_response->addCommand(new HtmlCommand('#form-system-messages', $messages));
    return $ajax_response;
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addMessage($this->t('Your cat name "@name" save', ['@name' => $form_state->getValue('title')]));
  }
}

