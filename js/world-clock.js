/**
 * @file
 *
 * For showing changing time per second.
 */

(function ($, Drupal) {
  Drupal.behaviors.SpecbeeWorldClock = {
    attach: function (context, settings) {
      setInterval(
        function () {
          var wrapper = ".specbee-world-clock-wrapper .time";

          var zone = $(wrapper).data('zone');
          var year = $(wrapper).data('year');
          var month = $(wrapper).data('month') -1;
          var day = $(wrapper).data('day');
          var hour = $(wrapper).data('hour');
          var min = $(wrapper).data('min');
          var sec = $(wrapper).data('sec');

          var updatedDate = dateWithTimeZone(zone, year, month, day, hour, min, sec);
          console.log(updatedDate);
          console.log(sec);
          var updatedYear = updatedDate.getFullYear();
          var updatedMonth = updatedDate.getMonth();
          var updatedDay = updatedDate.getDate();
          var updatedHour = updatedDate.getHours();
          var updatedMinute = updatedDate.getMinutes();
          var updatedSecond = updatedDate.getSeconds();


          /**
           * Update data attributes of the wrapper.
           */
          updateDataOfWrapper(
            wrapper,
            updatedYear,
            updatedMonth + 1,
            updatedDay,
            updatedHour,
            updatedMinute,
            updatedSecond,
          );

          /**
           * Update the text of the wrapper.
           */
           updateTextOfWrapper(
            wrapper,
            updatedYear,
            updatedMonth,
            updatedDay,
            updatedHour,
            updatedMinute,
            updatedSecond,
           );
        },
        3000
      );
    }
  };

  /**
   * Return the updated date and time as per timezone.
   */
    dateWithTimeZone = (timeZone, year, month, day, hour, minute, second) => {
      let date = new Date(Date.UTC(year, month, day, hour, minute, second));

      let utcDate = new Date(date.toLocaleString('en-US', { timeZone: "UTC" }));
      let tzDate = new Date(date.toLocaleString('en-US', { timeZone: timeZone }));
      let offset = utcDate.getTime() - tzDate.getTime();

      date.setTime( date.getTime() + offset );

      return date;

    };

    /**
     * Update the text of the wrapper with new date.
     */
    function updateTextOfWrapper (
      wrapper,
      updatedYear,
      updatedMonth,
      updatedDay,
      updatedHour,
      updatedMinute,
      updatedSecond,
    ) {
      var monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
      ];
      var AmOrPm = updatedHour > 12 ? 'PM' : 'AM';

      updatedDateTime = getDayWithSuffic(updatedDay) + ' '
                        + monthShortNames[updatedMonth] + ' '
                        + updatedYear + ' - '
                        + updatedHour + ':'
                        + updatedMinute + ' '
                        + updatedSecond + ' '
                        + AmOrPm;
      $(wrapper).html(updatedDateTime);
    }

    /**
     * Update data attributes of the wrapper.
     */
    function updateDataOfWrapper(
      wrapper,
      updatedYear,
      updatedMonth,
      updatedDay,
      updatedHour,
      updatedMinute,
      updatedSecond,
    ) {
      $(wrapper).attr('data-year',updatedYear);
      $(wrapper).attr('data-month',updatedMonth);
      $(wrapper).attr('data-day',updatedDay);
      $(wrapper).attr('data-hour',updatedHour);
      $(wrapper).attr('data-min',updatedMinute);
      $(wrapper).attr('data-sec',updatedSecond);
    }

    /**
     * Function to return date day with suffic 3rd, th and st.
     */
    function getDayWithSuffic($day) {
      var unitRem = $day % 10,
          biRem = $day % 100;
      if (unitRem == 1 && biRem != 11) {
          return $day + "st";
      }
      if (unitRem == 2 && biRem != 12) {
          return $day + "nd";
      }
      if (unitRem == 3 && biRem != 13) {
          return $day + "rd";
      }
      return $day + "th";
    }


}) (jQuery, Drupal);
