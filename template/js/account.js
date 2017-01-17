var USER_NAME;
var FIRST_NAME;
var LAST_NAME;
var EMAIL;
var ADDRESS;
var PLZ;
var CITY;

function loadUserData() {
    var userId = localStorage.userid;
   $.ajax({
        url: '/users/'+userId,
        type: 'GET',
        dataType: 'json',
        success:function(user){
            USER_NAME = user.userName;
            FIRST_NAME = user.firstName;
            LAST_NAME = user.lastName;
            EMAIL = user.email;
            ADDRESS = user.address;
            PLZ = user.zip;
            CITY = user.city;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(jqXHR, textStatus, errorThrown);
        }
    }).done(function () {
       $('#account-username').attr('placeholder',USER_NAME);
       $('#account-firstname').attr('placeholder',FIRST_NAME);
       $('#account-lastname').attr('placeholder',LAST_NAME);
       $('#account-email').attr('placeholder',EMAIL);
       $('#account-address').attr('placeholder',ADDRESS);
       $('#account-zip').attr('placeholder',PLZ);
       $('#account-city').attr('placeholder',CITY);
   });
}

function updateNewPassword(){
    var userId = localStorage.userid;
    var oldPw = $('#account-old-password').val();
    var newPw1 = $('#account-new-password1').val();
    var newPw2 = $('#account-new-password2').val();
    var hasErrors = false;

    if(newPw1 === null || newPw1 === '' || newPw1.length < 8){
        $('#account-new-password1').addClass('error');
        hasErrors = true;
    }
    if(newPw2 === null || newPw2 === '' || newPw2.length < 8){
        $('#account-new-password2').addClass('error');
        hasErrors = true;
    }

    if (newPw1 !== newPw2) {
        $('#account-new-password1').addClass('error');
        $('#account-new-password2').addClass('error');
        hasErrors = true;
        alert('Die beiden Passwörter müssen gleich sein!');
    }

    if (!hasErrors) {
        $.ajax({
            url: '/users/' + userId,
            type: 'PUT',
            data: {oldPassword: oldPw, newPassword: newPw1},
            dataType: 'json',
            success: function (user) {
                alert(user.firstName + " has been updated!");
                showAccount();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#account-old-password').addClass('error');
            }
        });
    }
}