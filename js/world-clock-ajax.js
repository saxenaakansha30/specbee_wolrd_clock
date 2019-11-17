/**
 * @file
 *
 * For showing changing time per second with ajax callback.
 */
(function ($, Drupal) {
  Drupal.behaviors.SpecbeeWorldClock = {
    attach: function (context, settings) {
      setInterval(
        function () {
          $.ajax({url: window.location.href + "/specbee_world_clock/get_date_time", success: function(result){
            var wrapper = ".specbee-world-clock-wrapper .time";
            $(wrapper).html(result.data.dateTime);
          }});
        },
        60000
      );
    }
  };

}) (jQuery, Drupal);
