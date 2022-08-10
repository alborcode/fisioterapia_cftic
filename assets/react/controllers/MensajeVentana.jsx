// assets/react/controllers/MyComponent.jsx

import React from 'react';
import Swal from "sweetalert2"
// minified version is also included
// import 'react-toastify/dist/ReactToastify.min.css';

const mensajeKo = () => {
  Swal.fire('Error', {
      position: toast.POSITION.TOP_CENTER
  });
};

const mensajeWarning = () => {
  toast.warning('Success Notification !', {
      position: toast.POSITION.TOP_CENTER
  });
};

const mensajeOk = () => {
  toast.success('Success Notification !', {
      position: toast.POSITION.TOP_CENTER
  });
};

const mensajeInfo = () => {
  toast.info('Success Notification !', {
      position: toast.POSITION.TOP_CENTER
  });
};

const mensaje = (
  <ToastContainer
    position="top-center"
    autoClose={5000}
    hideProgressBar={false}
    newestOnTop={false}
    closeOnClick
    rtl={false}
    pauseOnFocusLoss
    draggable
    pauseOnHover
  />
);

function Mensaje(){


  return (
    <div>
        <button onClick={showToastMessage}>Notify</button>
        <ToastContainer />
    </div>
  );

}

export default Mensaje;