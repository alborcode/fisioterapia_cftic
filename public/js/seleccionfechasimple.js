const es = {
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
    'wed_':'Mercoles',
    'thu_':'Jueves',
    'fri_':'Viernes',
    'sat_':'Sabado',
    'sun_':'Domingo',
    'done':'Hecho'
};
const fechainicial = "{{ fechaini }}";
console.log(fechainicial)
const fechafinal = "{{ fechafin }}";
console.log(fechafinal)
const fechaactual = "{{ fechadia }}";
console.log(fechaactual)

new DateTimePickerComponent.DatePicker( 'select_date', 
{
    first_date: fechainicial,
    last_date: fechafinal,
    start_date: fechaactual,
    first_day_no: 1,
    l10n: es,
    date_output: "short_ISO",
    styles: {
        active_background: '#e34c26',
        active_color: '#fff',
        inactive_background: '#0366d9',
        inactive_color: '#fff'
    }
});

    // <script>
    //     config={
    //         enableTime: false,
    //         dateFormat: "d-m-Y",
    //         altInput: true,
    //         altFormat: "d F Y",
    //         disable: [
    //             function(date) {
    //                 return (date.getDay() === 0 || date.getDay() === 6);
    //             }
    //         ],
    //         locale: {
    //             "firstDayOfWeek": 1 // Comienza la Semana en Lunes
    //         }   
    //     }
    //     flatpickr("#txtFecha", config);
    // </script>