<?php

namespace Drupal\current_time\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

class CurrentTimeController extends ControllerBase {

  protected $dateFormatter;
  protected $configFactory;

  public function __construct(DateFormatterInterface $dateFormatter, ConfigFactoryInterface $configFactory) {
    $this->dateFormatter = $dateFormatter;
    $this->configFactory = $configFactory;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('config.factory')
    );
  }

  public function getTime() {
    $config = $this->configFactory->get('current_time.settings');
    $timezone = $config->get('Timezone');
    $city = $config->get('City');
    $country = $config->get('Country');
    $timestamp = (new DrupalDateTime())->getTimestamp();
  
    // Time (e.g., "11:15 AM")
    $time = $this->dateFormatter->format(
      $timestamp,
      'custom',
      'g:i a',
      $timezone
    );
  
    // Date (e.g., "Monday, 19 September 2022")
    $date = $this->dateFormatter->format(
      $timestamp,
      'custom',
      'l, j F Y',
      $timezone
    );
  
    // Location line
    $location = "Time in $city, $country";
  
    return new JsonResponse([
      'time' => $time,
      'full_date' => $date,
      'location' => $location,
    ]);
  }
  
}
