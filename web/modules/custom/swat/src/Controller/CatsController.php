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
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
      $simpleform,
      $this->show(),

      //      $this->report(),
    ];
  }

  public function show() {

    $conn = Database::getConnection();

    $query = $conn->select('swat', 's');
//    ->condition('id', $id);
    $query->fields('s', ['id', 'name', 'email', 'timestamp', 'photo']);
    $query->orderBy('s.timestamp', 'DESC');
//    $query->range(0, 99);
    $data = $query->execute()->fetchAllAssoc('id');
    $data = json_decode(json_encode($data), TRUE);
    $result = [];
    foreach ($data as $value) {
      $full_name = $value['name'];
      $email = $value['email'];
      $timestamp = $value['timestamp'];
      $file = File::load($value['photo']);
      //    $picture = $file->getFileUri();
      $picture = [
        '#type' => 'image',
        '#theme' => 'image_style',
        '#style_name' => 'large',
        '#uri' => $file->getFileUri(),
      ];
      $result[] = [
        '#type' => 'markup',
        '#markup' => "<div class='swatshow'><h3>$full_name</h3>
                    <p>$email</p>",
        $picture,
        '#suffix' => "<p>$timestamp</p></div>",
      ];
    }
    return $result;
  }

}
