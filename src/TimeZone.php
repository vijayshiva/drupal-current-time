<?php

namespace Drupal\current_time;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\DateFormatter;

class Timezone {

  protected $config;
  protected $date_format;

  public function __construct(ConfigFactory $config, DateFormatter $date_format) {
    $this->config = $config;
    $this->date_format = $date_format;
  }

  /**
   * Returns time in format:
   * 11:15 am
   * Monday, 19 September 2022
   * Time in New York, NY, USA
   */
  public function getFormattedTimeDetails() {
    $timezone = $this->config->get("current_time.settings")->get("Timezone");
    $city = $this->config->get("current_time.settings")->get("City");
    $country = $this->config->get("current_time.settings")->get("Country");

    $now = new DrupalDateTime('now', $timezone);
    $timestamp = $now->getTimestamp();

    return [
      'time' => $this->date_format->format($timestamp, 'custom', 'g:i a', $timezone),
      'full_date' => $this->date_format->format($timestamp, 'custom', 'l, j F Y', $timezone),
      'location' => "Time in {$city}, {$country}",
    ];
  }
}
