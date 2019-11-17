<?php

namespace Drupal\specbee_world_clock;

use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Service for World's clock DateTime funtionality.
 */
class DateTime {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Service for Drupal DateFormatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter;
   */
  protected $dateFormatter;

  /**
   * Constructs required services..
   *
   * @param \Drupal\Core\Datetime\DateFormatter $dateFormatter
   *   The dateformatter.
   */
  public function __construct(DateFormatter $dateFormatter, ConfigFactoryInterface $config_factory) {
    $this->dateFormatter = $dateFormatter;
    $this->config = $config_factory->get('specbee_world_clock.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('config.factory')
    );
  }

  /**
   * Return datetime as per set admin configuration timezone.
   */
  public function getDateTime($timeZone = NULL, $format) {
    if (is_null($timeZone)) {
      $timeZone = $this->getTimeZone();
    }

    $date = $this->dateFormatter->format(
      time(),
      'custom',
      $format,
      $timeZone
    );
    return $date;
  }

  /**
   * Return timezone set in admin config page.
   */
  public function getTimeZone() {
    if ($zone = $this->config->get('timezone')) {
      return $zone;
    }
    return FALSE;
  }

  /**
   * Function to return the date and time of set timezone as array.
   */
  public function getDateTimeIndividual($timeZone = NULL) {
    if (is_null($timeZone)) {
      $timeZone = $this->getTimeZone();
    }

    $date = $this->dateFormatter->format(
      time(),
      'custom',
      'Y-m-d-h-i-s',
      $timeZone
    );
    $indiDate = explode('-', $date);
    return $indiDate;
  }

}
