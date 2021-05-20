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
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
    ];
  }

}
