<?php

namespace Drupal\swat\Controller;

use Drupal\Core\Controller\ControllerBase;
/**
 * Defines CatsController class.
 */
class CatsController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *  Return markup array.
   */
    public function content() {

      $simpleform = \Drupal::formBuilder()->getForm('Drupal\swat\Form\CatsForm');

      return [
        '#type' => 'markup',
        '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
        $simpleform,
      ];
    }

}
