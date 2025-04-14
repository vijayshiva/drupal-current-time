<?php

namespace Drupal\current_time\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Controller for returning the current time in JSON format.
 *
 * This controller fetches the current time based on the configured timezone
 * and returns it as a JSON response. It's typically used for AJAX requests
 * to dynamically update the time displayed on the frontend without requiring
 * a full page reload.
 */
class CurrentTimeController extends ControllerBase {

  protected $dateFormatter;
  protected $configFactory;

/**
 * Constructs a CurrentTimeController object.
 *
 * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
 *   The date formatter service used to format timestamps into human-readable date strings.
 * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
 *   The configuration factory service used to access the module's configuration settings.
 */
  public function __construct(DateFormatterInterface $dateFormatter, ConfigFactoryInterface $configFactory) {
    $this->dateFormatter = $dateFormatter;
    $this->configFactory = $configFactory;
  }

/**
 * {@inheritdoc}
 *
 * Instantiates the controller with required services.
 *
 * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
 *   The service container.
 *
 * @return static
 *   Returns an instance of the controller with injected services.
 */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('config.factory')
    );
  }

/**
 * Returns the current time, date, and location as a JSON response.
 *
 * This method retrieves the configured timezone, city, and country from the
 * module's settings.
 *
 * @return \Symfony\Component\HttpFoundation\JsonResponse
 *   A JSON response with the following structure:
 *   - time: The current time (e.g., '11:15 am').
 *   - full_date: The current date (e.g., 'Monday, 19 September 2022').
 *   - location: A string indicating the location (e.g., 'Time in New York, NY, USA').
 */
  public function getTime() {
    $config = $this->configFactory->get('current_time.settings');
    $timezone = $config->get('Timezone');
    $city = $config->get('City');
    $country = $config->get('Country');
    $timestamp = (new DrupalDateTime())->getTimestamp();
  
    $time = $this->dateFormatter->format(
      $timestamp,
      'custom',
      'g:i a',
      $timezone
    );
  
    $date = $this->dateFormatter->format(
      $timestamp,
      'custom',
      'l, j F Y',
      $timezone
    );
  
    $location = "Time in $city, $country";
  
    return new JsonResponse([
      'time' => $time,
      'full_date' => $date,
      'location' => $location,
    ]);
  }
  
}
