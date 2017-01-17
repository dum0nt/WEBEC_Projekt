var LOGIN_CONTENT = '#login-content';
var REGISTER_CONTENT = '#register-content';
var ACCOUNT_CONTENT = '#account-content';
var ACCOUNT_NAVLINK = '#account-navlink';
var CALENDAR_CONTENT = '#calendar-content';
var CALENDAR_DIV = '#calendar';
var CALENDAR_NAVLINK = '#calendar-navlink';
var TIMETABLE_CONTENT = '#timetable-content';
var RESERVATION_CONTENT = '#reservation-content';
var SHIP_CONTENT = '#ship-content';
var SHIP_NAVLINK = '#ship-navlink';
var LAKE_LATITUDE = 47.282096;
var LAKE_LONGITUDE = 8.215788;

/**
 * shows the login dialog
 */
function showLogin() {
    $('#login-logo').show();
    $('nav').hide();
    $('section').hide();
    $('input').removeClass('error');
    $('input').val('');
    $(LOGIN_CONTENT).show();
}

/**
 * shows the registration dialog
 */
function showRegistration() {
    $('#login-logo').show();
    $('nav').hide();
    $('section').hide();
    $('input').removeClass('error');
    $('input').val('');
    $(REGISTER_CONTENT).show();
}

/**
 * shows the account dialog
 */
function showAccount() {
    loadUserData();
    $('#login-logo').hide();
    $('nav').show();
    $('section').hide();
    $('nav li').removeClass('active');
    $(ACCOUNT_NAVLINK).addClass('active');
    $(ACCOUNT_CONTENT).show();
}

/**
 * shows the calendar dialog
 */
function showCalendar() {
    loadDropdownContent();
    $('#login-logo').hide();
    $('nav').show();

    // show calendar section
    $('section').hide();
    $('nav li').removeClass('active');
    $('#reservation-content input').val('');
    $(CALENDAR_NAVLINK).addClass('active');
    $(CALENDAR_CONTENT).show();
    $(TIMETABLE_CONTENT).show();
}

/**
 * shows the reservation dialog
 */
function showReservation() {
    $('#login-logo').hide();
    $('nav').show();

    $('section').hide();
    $('nav li').removeClass('active');
    $(CALENDAR_NAVLINK).addClass('active');
    $(CALENDAR_CONTENT).show();
    $(RESERVATION_CONTENT).show();
}

/**
 * shows the ship dialog
 */
function showShips() {
    loadShips();
    $('#login-logo').hide();
    $('nav').show();

    $('section').hide();
    $('nav li').removeClass('active');
    $(SHIP_NAVLINK).addClass('active');
    $(SHIP_CONTENT).show();
}

/**
 * shows the calendar dialog
 */
function clearShipCreation() {
    $('.shipCreation input').val('');
    $('.shipCreation select').val('');
}

$(document).ready(function() {
    $.ajax({
        url:'/ships',
        type:'GET',
        data:null,
        success:function () {
            showCalendar();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showLogin();
        }
    });
});
