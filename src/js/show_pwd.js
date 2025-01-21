function togglePasswordVisibility(passwordFieldId, checkboxId) {
    const passwordField = document.getElementById(passwordFieldId);
    const checkbox = document.getElementById(checkboxId);

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password'; 
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    togglePasswordVisibility('password', 'showPwd1');
    togglePasswordVisibility('confirm_password', 'showPwd2');
});