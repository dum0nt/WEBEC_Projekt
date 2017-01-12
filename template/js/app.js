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

function ajaxRequest(url, method, parameters, donefunction, failfunction) {
    req = $.ajax({
        url: url,
        context: document.body,
        method: method,
        dataType: 'json',
        data: parameters,
    });

    req.done(function(data) {
        donefunction(data);
    });

    req.fail(function(data) {
        failfunction(data);
    });
}

function showLogin() {
    $('#login-logo').show();
    $('nav').hide();
    $('section').hide();
    $('input').removeClass('error');
    $('input').val('');
    $(LOGIN_CONTENT).show();
}

function showRegistration() {
    $('#login-logo').show();
    $('nav').hide();
    $('section').hide();
    $('input').removeClass('error');
    $('input').val('');
    $(REGISTER_CONTENT).show();
}

function showAccount() {
    $('#login-logo').hide();
    $('nav').show();
    $('section').hide();
    $('nav li').removeClass('active');
    $(ACCOUNT_NAVLINK).addClass('active');
    $(ACCOUNT_CONTENT).show();
}

function showCalendar() {
    $('#login-logo').hide();
    $('nav').show();

    // show calendar section
    $('section').hide();
    $('nav li').removeClass('active');
    $('#reservation-content input').val('');
    $(CALENDAR_NAVLINK).addClass('active');
    $(CALENDAR_CONTENT).show();
    $(TIMETABLE_CONTENT).show();

    // fill calendar
    $(CALENDAR_DIV).fullCalendar({
        // options and callbacks
        header: {
            left:   '',
            center: 'title',
            right:  'today prev,next'
        },
        defaultView: 'agendaWeek',
        locale: 'de',
        height: 'auto',
        minTime: '08:00:00',
        maxTime: '18:00:00'
    });
}

function showReservation() {
    $('#login-logo').hide();
    $('nav').show();

    $('section').hide();
    $('nav li').removeClass('active');
    $(CALENDAR_NAVLINK).addClass('active');
    $(CALENDAR_CONTENT).show();
    $(RESERVATION_CONTENT).show();
}

function showShips() {
    $('#login-logo').hide();
    $('nav').show();

    $('section').hide();
    $('nav li').removeClass('active');
    $(SHIP_NAVLINK).addClass('active');
    $(SHIP_CONTENT).show();
}

function saveReservation() {
    var date = $('#reservation-date').val();
    var from = $('#reservation-from').val();
    var to = $('#reservation-to').val();
    alert('Datum: ' + date + '\nVon: ' + from + '\nBis: ' + to);
}

function saveShip() {
    var shipname = $('#ship-name').val();
    var shiptype = $('#ship-type').val();
    var berthtown = $('#ship-berthtown').val();
    var berth = $('#ship-berth').val();
    alert('Name: ' + shipname + '\nTyp: ' + shiptype + '\nStandort: ' + berthtown + '\nAnlegeplatz: ' + berth);
}

function clearShipCreation() {
    $('.shipCreation input').val('');
    $('.shipCreation select').val('');
}

$(document).ready(function() {
    ajaxRequest("http://localhost", "GET", null, showCalendar(), showLogin());
});
