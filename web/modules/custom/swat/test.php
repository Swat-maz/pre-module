<?php

/**
 * @file
 * Contains \Drupal\nomadmodule\Form\Nomadform.
 */

namespace Drupal\nomadmodule\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InsertCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an nomadmodule name form.
 */

class Nomadform extends FormBase {

  /**
   *(@inheritdoc).
   */
  public function getFormId() {
    return 'nomadmodule_name_form';
  }
  /**
   * (@inheritdoc).
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form ['name'] = [
      '#title' => t("Your cat's name:"),
      '#type' => 'textfield',
      '#size' => 32,
      '#description' => t("The name of your pet, must contain at least 2 characters and maximum length is 32 characters, and can not contain any numbers, whitespaces, and symbols."),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validateNameAjax',
        'event' => 'keyup',
        'progress' => [
          'type' => 'throbber',
          'message' => t('Verifying name..'),
        ],
      ],
    ];
    $form ['email'] = [
      '#title' => t('Your email:'),
      '#type' => 'email',
      '#required' => TRUE,
      '#description' => t("Your email can contain only latin alphabet letters, 'at' sign, dash sign, underscore sign, and dots."),
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'event' => 'keyup',
        'progress' => [
          'type' => 'throbber',
          'message' => t('Verifying email..'),
        ],
      ],
    ];
    $form ['image'] = [
      '#title' => t('Add your pet image'),
      '#type' => 'managed_file',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2097152],
      ],
      '#description' => t("Your pet image size must be less than 2MB. Only files with the following extensions are allowed: png, jpg, jpeg."),
      '#upload_location' => 'public://photos',
      '#required' => TRUE,
    ];
    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];
    $form ['submit'] = [
      '#type' => 'submit',
      '#value' => t('Add cat'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ],
    ];
    return $form;
  }

  /**
   * (@inheritdoc).
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('name');
    $emailvalue = $form_state->getValue('email');
    if (!preg_match('/^[A-Za-z]*$/', $value) || strlen($value)<2 || strlen($value)>32) {
      $form_state->setErrorByName ('name', t('The name %name is not valid.', array('%name' => $value)));
    }
    if (filter_var($emailvalue, FILTER_VALIDATE_EMAIL) && preg_match('/[#$%^&*()+=!\[\]\';,\/{}|":<>?~\\\\]/', $emailvalue)) {
      $form_state->setErrorByName ('email', t('The email %email is not valid.', array('%email' => $emailvalue)));
    }
    else {
      $this->messenger()->deleteAll();
    }
  }

  /**
   * (@inheritdoc).
   */
  public function validateNameAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $value = $form_state->getValue('name');
    if ($value == '') {
      $response->addCommand(new HtmlCommand('#form-system-messages', "<div class='alert alert-dismissible fade show alert-danger'>The name field is required.
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button></div>"));
    }
    elseif (!preg_match('/^[A-Za-z]*$/', $value) || strlen($value) < 2 || strlen($value) > 32) {
      $response->addCommand(new HtmlCommand('#form-system-messages', "<div class='alert alert-dismissible fade show alert-danger'>The name $value is not valid.
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button></div>"));
    }
    else {
      $response->addCommand(new HtmlCommand('#form-system-messages', "<div class='alert alert-dismissible fade show alert-success'>The name $value is correct.
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button></div>"));
    }
    return $response;
  }
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $emailvalue = $form_state->getValue('email');
    if ($emailvalue == '') {
      $response->addCommand(new HtmlCommand('#form-system-messages', "<div class='alert alert-dismissible fade show alert-danger'>Email field is required.
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
    </div>"));
    }
    elseif (filter_var($emailvalue, FILTER_VALIDATE_EMAIL) && !preg_match('/[#$%^&*()+=!\[\]\';,\/{}|":<>?~\\\\]/', $emailvalue)) {
      $response->addCommand(new HtmlCommand('#form-system-messages', "<div class='alert alert-dismissible fade show alert-success'>Email $emailvalue is correct.
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
</div>"));
    }
    else {
      $response->addCommand(new HtmlCommand('#form-system-messages', "<div class='alert alert-dismissible fade show alert-danger'>Email $emailvalue is not valid.
<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
    </div>"));
    }
    return $response;
  }
  /**
   * {@inheritdoc}
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
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

  /**
   *  (@inheritdoc).
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    \Drupal::messenger()->addMessage($this->t('Form Submitted Successfully'), 'status', TRUE);
  }
}
