/**
 * @author: CoochieHunter
 */
(function () {
  "use strict";

  let toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000,
      timerProgressBar: true,
      showCloseButton: true
  });

  let toast2s = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    showCloseButton: true
});

  function dialogConfirm(message, yesCallback, noCallback) {
    let lang = localStorage.getItem('language') ?? 'kr'

    Swal.fire({
      title: message,
      showDenyButton: true,
      confirmButtonText: (lang === 'en') ? 'Yes' : '예',
      denyButtonText: (lang === 'en') ? 'No' : '아니오',
      customClass: {
        cancelButton: 'btn btn-warning',
        confirmButton: 'btn btn-success',
        denyButton: 'btn btn-secondary',
      }
    }).then((result) => {
      if (result.isConfirmed) {
        if (yesCallback)
          yesCallback();
      } else if (result.isDenied) {
        if (noCallback)
          noCallback()
      }
    })
  }

  function dialogConfirm2(title, message, yesCallback, noCallback) {
    let lang = localStorage.getItem('language') ?? 'kr'

    Swal.fire({
      title: title,
      text: message,
      showDenyButton: true,
      confirmButtonText: (lang === 'en') ? 'Yes' : '예',
      denyButtonText: (lang === 'en') ? 'No' : '아니오',
      customClass: {
        cancelButton: 'btn btn-warning',
        confirmButton: 'btn btn-success',
        denyButton: 'btn btn-secondary',
      }
    }).then((result) => {
      if (result.isConfirmed) {
        if (yesCallback)
          yesCallback();
      } else if (result.isDenied) {
        if (noCallback)
          noCallback()
      }
    })
  }
  function dialogConfirm3(title, message, yesCallback, noCallback) {
    let lang = localStorage.getItem('language') ?? 'kr'

    Swal.fire({
      title: title,
      html: message,
      showDenyButton: true,
      confirmButtonText: (lang === 'en') ? 'Yes' : '예',
      denyButtonText: (lang === 'en') ? 'No' : '아니오',
      customClass: {
        cancelButton: 'btn btn-warning toaster-btn',
        confirmButton: 'toaster-btn confirm ',
        denyButton: 'btn btn-secondary toaster-btn',
      }
    }).then((result) => {
      if (result.isConfirmed) {
        if (yesCallback)
          yesCallback();
      } else if (result.isDenied) {
        if (noCallback)
          noCallback()
      }
    })
  }
  function dialogInfo(message, confirmCallback, onCloseCallback) {
    let lang = localStorage.getItem('language') ?? 'kr'

    Swal.fire({
      title: message,
      icon: 'info',
      confirmButtonText: lang === 'en' ? 'OK' : '확인',
      allowOutsideClick: false,
      onClose: onCloseCallback
    }).then((result) => {
      if (result.isConfirmed && typeof confirmCallback === 'function')
        confirmCallback()
    })
  }
  function dialogInfo2(title, message, confirmCallback, onCloseCallback) {
    let lang = localStorage.getItem('language') ?? 'kr'

    Swal.fire({
      title: title,
      html: message,
      icon: 'info',
      confirmButtonText: lang === 'en' ? 'OK' : '확인',
      allowOutsideClick: false,
      onClose: onCloseCallback
    }).then((result) => {
      if (result.isConfirmed && typeof confirmCallback === 'function')
        confirmCallback()
    })
  }

  function dialogFull(message, onCloseCallback) {
    Swal.mixin({customClass:{title:'text-grey-400',confirmButton: 'btn-link bg-transparent'}}).fire({
      title: message, 
      background: 'var(--new-message-background)',
      width: "100%",
      onClose: onCloseCallback,
      confirmButtonText: `<img src="/assets/images/icons/envelope.svg" height="120px" />`
    }).then((result)=> {
      if(result.isConfirmed && typeof onCloseCallback === 'function') 
        onCloseCallback();
    })
  }

  window.dialog = {
    info: dialogInfo,
    info2: dialogInfo2,
    confirm: dialogConfirm,
    confirm2: dialogConfirm2,
    confirm3: dialogConfirm3,
    full: dialogFull
  }

  window.toaster = {
    success: (message) => toast.fire({ icon: 'success', title: message }),
    info: (message) => toast2s.fire({ icon: 'info', title: message }),
    error: (message) => toast.fire({ icon: 'error', title: message }),
    warning: (message) => toast.fire({ icon: 'warning', title: message }),
    question: (message) => toast.fire({ icon: 'question', title: message })
  }
}())