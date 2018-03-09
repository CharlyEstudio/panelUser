// show error/success/warning message, for more examples of library: http://goo.gl/wa6xWr
// @params type: error/success/warning
//@param msg: show message for user
function showMessage(type, msg) {
  Command: toastr[type](msg)

  toastr.options = {
    'closeButton': true,
    'debug': false,
    'newestOnTop': false,
    'progressBar': true,
    'positionClass': 'toast-top-right',
    'preventDuplicates': true,
    'onclick': null,
    'showDuration': '300',
    'hideDuration': '1000',
    'timeOut': '5000',
    'extendedTimeOut': '1000',
    'showEasing': 'swing',
    'hideEasing': 'linear',
    'showMethod': 'fadeIn',
    'hideMethod': 'fadeOut'
  }
}

function redirect(url) {
  location.href=url;
}
