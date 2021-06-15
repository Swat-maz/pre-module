<?php
/**
 *@file
 *Contains \Drupal\swat\Form\CollectPhone.
 */
namespace Drupal\swat\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

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
    ];

    $form['action']['#type'] = 'actions';

    $form['action']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('title')) < 2){
      $form_state->setErrorByName('title', $this->t('Name is too short.'));
    }
    if (strlen($form_state->getValue('title')) > 32){
      $form_state->setErrorByName('title', $this->t('Name is too looong.'));
    }
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addMessage($this->t('Your cat name save'));
  }
}
