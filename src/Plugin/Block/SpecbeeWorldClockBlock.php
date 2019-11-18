<?php

namespace Drupal\specbee_world_clock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\specbee_world_clock\DateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a Specbee Wolrd Clock's Block.
 *
 * @Block(
 *   id = "specbee_world_clock_block",
 *   admin_label = @Translation("Specbee Wolrd Clock block"),
 *   category = @Translation("Specbee"),
 * )
 */
class SpecbeeWorldClockBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Service for world clock datetime.
   *
   * @var \Drupal\specbee_world_clock\DateTime
   */
  protected $dateTimeService;

  /**
   * Creates a SystemBrandingBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\specbee_world_clock\DateTime $dateTimeService
   *   Service for Specbee wolrd clock datetime calculation.
   */
  public function __construct(
    array $configuration,
    $plugin_id, $plugin_definition,
    ConfigFactoryInterface $config_factory,
    DateTime $dateTimeService
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config_factory->get('specbee_world_clock.settings');
    $this->dateTimeService = $dateTimeService;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('specbee_world_clock.datetime')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];
    $zone = $this->config->get('timezone');
    if (!empty($zone)) {
      $dateTime = $this->dateTimeService->getDateTime($zone, 'jS M Y - H:i A');
      $output = [
        'city' => $this->config->get('city') ? $this->config->get('city') : NULL,
        'country' => $this->config->get('country') ? $this->config->get('country') : NULL,
        'time' => $dateTime,
        'zone' => $zone,
      ];

      // Libarary to attach.
      if ($this->config->get('method') == 'js') {
        $library = ['specbee_world_clock/world_clock'];
        $output['indiDate'] = $this->dateTimeService->getDateTimeIndividual($zone);
      } else {
        $library = ['specbee_world_clock/world_clock_ajax'];
      }

    }
    return [
      '#theme' => 'specbee_world_clock_block',
      '#output' => $output,
      '#attached' => [
        'library' => $library,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(
      parent::getCacheTags(),
      $this->config->getCacheTags()
    );
  }

}
