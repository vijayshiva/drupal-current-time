<?php

namespace Drupal\current_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 *`* Provides a 'TaksBlock' block.
 *
 * @Block(
 *   id = "sitelocation_form",
 *   admin_label = @Translation("TaksBlock"),
 *   category = @Translation("Plugin to render the location")
 * )
 */
class TaksBlock extends BlockBase {

  public function build() {
    $timeDetails = \Drupal::service("current_time.location")->getFormattedTimeDetails();

    $renderable = [
      '#theme' => 'current_time_template',
      '#time' => $timeDetails['time'],
      '#full_date' => $timeDetails['full_date'],
      '#location' => $timeDetails['location'],
      '#attached' => [
        'library' => [
          'current_time/time_updater',
        ],
      ],
    ];

    return $renderable;
  }

  public function getCacheMaxAge() {
    return Cache::PERMANENT;
  }

}
