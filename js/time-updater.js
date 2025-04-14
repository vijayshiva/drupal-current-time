(function ($, Drupal) {
    Drupal.behaviors.timeUpdater = {
      attach: function (context, settings) {
        if (context.querySelector('#current-time-container')) {
          function fetchTime() {
            fetch(Drupal.url('ajax/current-time'))
              .then(response => {
                if (!response.ok) {
                  throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
              })
              .then(data => {
                $('#current-time-container .current-time').text(data.time);
                $('#current-time-container .current-date').text(data.full_date);
                $('#current-time-container .current-location').text(data.location);
              })
              .catch(error => {
                console.error('Error fetching time:', error);
              });
          }
  
          fetchTime();
          setInterval(fetchTime, 10000);
        }
      }
    };
  })(jQuery, Drupal);
  