import { Controller } from '@hotwired/stimulus';
import ContentLoader from 'stimulus-content-loader'

const application = Application.start()
application.register('content-loader', ContentLoader)

import 'date-time-picker-component/dist/css/date-time-picker-component.min.css';
import { DateTimePicker } from "date-time-picker-component/dist/js/date-time-picker-component.min";


export default class extends Controller {
    // static targets = ['input', 'preview'];

    render(event) {    
        const rendered = new DatePicker( 'select_date' );
        this.previewTarget.innerHTML = rendered;
    }
}



