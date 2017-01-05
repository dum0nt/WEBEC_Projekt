var LOGIN_CONTENT = '#login-content';
var REGISTER_CONTENT = '#register-content';

function showLogin() {
    $('section').hide();
    $('input').removeClass('error');
    $('input').val('');
    $(LOGIN_CONTENT).show();
}

function showRegistration() {
    $('section').hide();
    $('input').removeClass('error');
    $('input').val('');
    $(REGISTER_CONTENT).show();
}

function login() {
    $('input').removeClass('error');
    var username = $('#login-username').val();
    var password = $('#login-password').val();

    var hasErrors = false;

    if (username === null || username === "") {
        $('#login-username').addClass('error');
        hasErrors = true;
    }

    if (password === null || password === "") {
        $('#login-password').addClass('error');
        hasErrors = true;
    }

    if (!hasErrors) {
        alert("Username = " + username + ", password = " + password);
        // perform login request
    }
}

function register() {
    $('input').removeClass('error');
    var username = $('#register-username').val();
    var firstname = $('#register-firstname').val();
    var lastname = $('#register-lastname').val();
    var email = $('#register-email').val();
    var address = $('#register-address').val();
    var zip = $('#register-zip').val();
    var password1 = $('#register-password1').val();
    var password2 = $('#register-password2').val();

    var hasErrors = false;

    if (username === null || username === "") {
        $('#register-username').addClass('error');
        hasErrors = true;
    }
    if (firstname === null || firstname === "") {
        $('#register-firstname').addClass('error');
        hasErrors = true;
    }
    if (lastname === null || lastname === "") {
        $('#register-lastname').addClass('error');
        hasErrors = true;
    }
    if (email === null || email === "") {
        $('#register-email').addClass('error');
        hasErrors = true;
    }
    if (address === null || address === "") {
        $('#register-address').addClass('error');
        hasErrors = true;
    }
    if (zip === null || zip === "") {
        $('#register-zip').addClass('error');
        hasErrors = true;
    }
    if (password1 === null || password1 === "" || password1.length < 8) {
        $('#register-password1').addClass('error');
        hasErrors = true;
    }
    if (password2 === null || password2 === "" || password2.length < 8) {
        $('#register-password2').addClass('error');
        hasErrors = true;
    }
    if (password1 !== password2) {
        $('#register-password1').addClass('error');
        $('#register-password2').addClass('error');
        hasErrors = true;
        alert('Die beiden Passwörter müssen gleich sein!');
    }

    if (!hasErrors) {
        alert("ok");
        // perform registration request
    }
}

$(document).ready(function() {
    showLogin();
});
