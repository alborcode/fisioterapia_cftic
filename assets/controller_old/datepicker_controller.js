import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static values = {
        element: { type: String, default: 'txtFecha' }
    }
    config={
        enableTime: true,
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d F Y",
        disable: [
            //function(date) {
                // si retorna true la fecha estará deshabilitada
                //return (date.getDay() === 0 || date.getDay() === 6);
            //}
            {
                from: "2022-09-01",
                to: "2022-09-05"
            },
            {
                from: "2022-09-21",
                to: "2022-09-23"
            }
        ],
        locale: {
            "firstDayOfWeek": 1 // Comienza la Semana en Lunes
        }
    }
    flatpickr(element, {});
    // flatpickr("#txtFecha", config);

}
