// document.addEventListener('DOMContentLoaded', function() {
//     var calendarEl = document.getElementById('calendar');
//     var calendar = new FullCalendar.Calendar(calendarEl, {
//       initialView: 'resourceTimelineWeek'
//     });
//     calendar.render();
//   });

jQuery( document ).ready( function($) {
  var calendar_obj = $( '#wpbody-content #calendar' ) 
  var calendar_ins = new FullCalendar.Calendar(calendar_obj, {
    initialView: 'resourceTimelineWeek'
  });
  calendar_ins.render();
});
