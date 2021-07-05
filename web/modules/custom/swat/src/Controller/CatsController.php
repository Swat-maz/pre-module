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
    $db = \Drupal::database();
    $query = $db->select('swat', 'cf');
    $query->fields('cf', 'name', 'email');
    $result = $query->execute()->fetchAll();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello! You can add here a photo of your cat.'),
      $simpleform,
      $result,
    ];
  }

//  public function load() {
//    $connection = \Drupal::service('database');
//    $query = $connection->select('swat', 'a');
//    $query->fields('a', ['name', 'email', 'timestamp', 'photo']);
//    $result = $query->execute()->fetchAll();
//    return $result;
//  }
//  public function report() {
//    $content = [];
//    $content['form'] = $this->content();
//    $headers = [
//      t('Cat name'),
//      t('Email'),
//      t('Submitted'),
//      t('Photo'),
//    ];
//    $info = json_decode(json_encode($this->load()), TRUE);
//    $info = array_reverse($info);
//    $rows = [];
//    foreach ($info as &$value) {
//      $fid = $value['photo'];
//      $file = File::load($fid);
//      $value['photo'] = [
//        '#type' => 'image',
//        '#theme' => 'image_style',
//        '#style_name' => 'large',
//        '#uri' => $file->getFileUri(),
//      ];
//      $renderer = \Drupal::service('renderer');
//      $value['photo'] = $renderer->render($value['photo']);
//      array_push($rows, $value);
//    }
//    $content['table'] = [
//      '#type' => 'table',
//      '#header' => $headers,
//      '#rows' => $rows,
//      '#empty' => t('No entries available.'),
//    ];
//    return $content;
//  }

}
