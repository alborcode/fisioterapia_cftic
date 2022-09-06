import 'date-time-picker-component/dist/css/date-time-picker-component.min.css';
import { DateTimePicker } from "date-time-picker-component/dist/js/date-time-picker-component.min";

new DateTimePickerComponent.DateTimePicker('datetimepicker',{
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

// Retrieves the date time selection and puts it in the 'selection' variable
let selection = document.querySelector( 'div#datetimepicker input.date_output' ).value;