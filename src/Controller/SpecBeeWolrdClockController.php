<?php

namespace Drupal\specbee_world_clock\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\specbee_world_clock\DateTime;

/**
 * Json Api to return current time of a timezone.
 */
class SpecBeeWolrdClockController {

  /**
   * Return Date And time.
   */
  public function getDateTime() {
    $dateTimeService = \Drupal::service('specbee_world_clock.datetime');
    $dateTime = $dateTimeService->getDateTime(NULL, 'jS M Y H:i A');
    $data = ['dateTime' => $dateTime];
    return new JsonResponse([
      'data' => $data,
      'method' => 'GET',
    ]);
  }

}
