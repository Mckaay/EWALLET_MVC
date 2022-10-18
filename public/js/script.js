const eyeToggler = document.querySelector('#toggler');
const passwordInput = document.querySelector('#password');

eyeToggler.addEventListener('click', function () {
  if (this.className === "icon-eye") {
    this.className = "icon-eye-off";
  }
  else {
    this.className = "icon-eye";
  }
  changeType();
})

function changeType() {
  let passwordInput = document.querySelector('#password');
  if (eyeToggler.className === 'icon-eye') {
    passwordInput.type = 'password';
  }
  else {
    passwordInput.type = 'text';
  }
}









