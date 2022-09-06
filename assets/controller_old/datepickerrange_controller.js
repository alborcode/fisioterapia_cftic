import 'date-time-picker-component/dist/css/date-time-picker-component.min.css';
import { DateTimeRangePicker } from "date-time-picker-component/dist/js/date-time-picker-component.min";

new DateTimeRangePicker( 'start_date_time', 'end_date_time', {
  first_date: "2030-01-02T10:30:00",
  start_date: "2030-01-05T16:00:00",
  end_date: "2030-01-06T18:00:00",
  last_date: new Date( 2030, 0, 29, 14, 0 ),
  first_day_no: 1,
  date_output: "timestamp",
  min_range_hours: 18,
  styles: 
  {
    active_background: '#e34c26',
    active_color: '#fff',
    inactive_background: '#0366d9',
    inactive_color: '#fff'
  },
  l10n: 
  {
    'jan':'Ene',
    'feb':'Feb',
    'mar':'Mar',
    'apr':'Abr',
    'may':'May',
    'jun':'Jun',
    'jul':'Jul',
    'aug':'Ago',
    'sep':'Sep',
    'oct':'Oct',
    'nov':'Nov',
    'dec':'Dic',
    'jan_':'Enero',
    'feb_':'Febrero',
    'mar_':'Marzo',
    'apr_':'Abril',
    'may_':'Mayo',
    'jun_':'Junio',
    'jul_':'Julio',
    'aug_':'Agosto',
    'sep_':'Septiembre',
    'oct_':'Octubre',
    'nov_':'Noviembre',
    'dec_':'Diciembre',
    'mon':'Lun',
    'tue':'Mar',
    'wed':'Mie',
    'thu':'Jue',
    'fri':'Vie',
    'sat':'Sab',
    'sun':'Dom',
    'mon_':'Lunes',
    'tue_':'Martes',
    'wed_':'Miercoles',
    'thu_':'Jueves',
    'fri_':'Viernes',
    'sat_':'Sabado',
    'sun_':'Domingo',
  }
});

let selectionstart = document.querySelector( 'div#start_date_time input.date_output' ).value;
let selectionend = document.querySelector( 'div#end_date_time input.date_output' ).value;