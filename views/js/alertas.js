const formulariosAjax = document.querySelectorAll('.FormularioAjax');

function enviarFormularioAjax(e) {
  e.preventDefault();
  let data = new FormData(this);
  let method = this.getAttribute('method');
  let action = this.getAttribute('action');
  let tipo = this.getAttribute('data-form');

  let headers = new Headers();

  let config = {
    method: method,
    headers: headers,
    mode: 'cors',
    cache: 'no-cache',
    body: data
  };

  let alertText;

  if (tipo == 'save') {
    alertText = 'Los datos serán guardados';
  } else if (alertText == 'update') {
    alertText = 'Los datos serán modificados';
  } else if (alertText == 'search') {
    alertText = 'Se eliminará el término de busqueda';
  }
  else if (tipo == 'delete') {
    alertText = 'Los datos serán eliminados';
  } else {
    alertText = '¿Está seguro de realizar esta operación?'
  }

  Swal.fire({
    title: '¿Estás seguro?',
    text: alertText,
    icon: 'question',
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3085d6',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    cancelButtonColor: '#d33'
  }).then((result) => {
    if (result.value) {
      fetch(action, config)
        .then(response => response.json())
        .then(response => {
          return alertAjax(response);
        });
    }
  });
}

formulariosAjax.forEach(forms => {
  forms.addEventListener('submit', enviarFormularioAjax)
});

function alertAjax(alert) {
  if (alert.Alert == 'simple') {
    Swal.fire({
      title: alert.title,
      text: alert.text,
      icon: alert.icon,
      confirmButtonText: 'Aceptar'
    });
  } else if (alert.Alert == 'reload') {
    Swal.fire({
      title: alert.title,
      text: alert.text,
      icon: alert.icon,
      confirmButtonText: 'Aceptar'
    }).then((result) => {
      if (result.value) {
        location.reload();
      }
    });
  } else if (alert.Alert == 'clean') {
    Swal.fire({
      title: alert.title,
      text: alert.text,
      icon: alert.icon,
      confirmButtonText: 'Aceptar'
    }).then((result) => {
      if (result.value) {
        document.querySelector('.FormularioAjax').reset();
      }
    });
  } else if (alert.Alert == 'redirect') {
    window.location.href = alert.URL;
  }
}