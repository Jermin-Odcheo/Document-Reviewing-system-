document.getElementById('email').addEventListener('input', function(event) {
    this.value = this.value.replace(/\s/g, '');
});

document.getElementById('password').addEventListener('input', function(event) {
    this.value = this.value.replace(/\s/g, '');
});

document.getElementById('confirm_password').addEventListener('input', function(event) {
    this.value = this.value.replace(/\s/g, '');
});