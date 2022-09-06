// Keep in mind that these are the styles from flatpickr package
// See troubleshooting section in case you have problems importing the styles

import "flatpickr/dist/themes/material_green.css";

import Flatpickr from "react-flatpickr";
import { Component } from "react";

class App extends Component {
  constructor() {
    super();

    this.state = {
      date: new Date()
    };
  }

  render() {
    const { date } = this.state;
    return (
      <Flatpickr
        data-enable-time
        value={date}
        onChange={([date]) => {
          this.setState({ date });
        }}
      />
    );
  }
}