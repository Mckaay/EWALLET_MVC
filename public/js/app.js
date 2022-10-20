$.validator.addMethod('validPassword',
function (value, element, param) {
  if (value != '') {

    if (value.match(/.*[a-z]+.*/i) == null) {
      return false;
    }
    if (value.match(/.*\d+.*/i) == null) {
      return false;
    }
    if (/^[a-zA-Z0-9- ]*$/.test(value) == false) {
      return false;
    }
  }
  return true;
},
'Must contain at least one letter and one number'
);

