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
        var params = {
            username: username,
            password: password
        };
        ajaxRequest("/login", "POST", params, function(data) {
            localStorage.username = data['username'];
            showCalendar();
        }, function(data) {
            $('#login-username').addClass('error');
            $('#login-password').addClass('error');
        });
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
        var params = {
            userName: username,
            firstName: firstname,
            lastName: lastname,
            email: email,
            address: address,
            zip: zip,
            password: password1
        };

        ajaxRequest("/users", "POST", params, showLogin(), function() {
            $("#register-username").addClass("error");
        });
    }
}

function logout() {
    ajaxRequest("/logout", "GET", null, showLogin(), function() {
        // do nothing
    });
}
