import { Controller } from '@hotwired/stimulus';

import 'date-time-picker-component/dist/css/date-time-picker-component.min.css';
import { DatePicker } from "date-time-picker-component/dist/js/date-time-picker-component.min";

// Retrieves the date time selection and puts it in the 'selection' variable
// let selection = document.querySelector( 'div#select_date input.date_output' ).value;

export default class extends Controller {

  rendered = new DatePicker( 'select_date', {
    first_date: "2022-01-01",
    start_date: "2022-12-31",
    last_date: new Date( 2022, 0, 29 ),
    first_day_no: 1,
    date_output: "timestamp",
    styles: {
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
  
  render(event) {
    this.previewTarget.innerHTML = rendered;
    // Retrieves the date time selection and puts it in the 'selection' variable
    let selection = document.getElementById( 'select_date').value;
    console.log(selection); 
  }
}