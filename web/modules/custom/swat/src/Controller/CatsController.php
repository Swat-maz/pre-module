<?php

namespace Drupal\swat\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

/**
 * Defines CatsController class.
 */
class CatsController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {

    $simpleform = \Drupal::formBuilder()->getForm('Drupal\swat\Form\CatsForm');
//    $db = \Drupal::service('database');
//    $query = $db->select('swat', 'cf');
//    $query->fields('cf', array('name', 'email'));
//    $result = $query->execute()->fetchAll();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
      $simpleform,
      $this->show(1),
//      $result,
    ];
  }

  public function show($id) {

    $conn = Database::getConnection();

    $query = $conn->select('swat', 'm')
      ->condition('id', $id)
      ->fields('m');
    $data = $query->execute()->fetchAssoc();
    $full_name = $data['name'];
    $email = $data['email'];
    $timestamp = $data['timestamp'];

    $file = File::load($data['photo']);
//    $picture = $file->getFileUri();
    $picture[] = [
      '#type' => 'image',
      '#theme' => 'image_style',
      '#style_name' => 'large',
      '#uri' => $file->getFileUri(),
    ];
    return [
      '#type' => 'markup',
      '#markup' => "<h3>$full_name</h3><br>
                    <p>$timestamp</p> <br>
                    <p>$email</p>",
      $picture,
    ];
  }

}
