/**
 * Created by joelm on 04.12.2016.
 */

var LOGIN_CONTENT = '#login-content';
var ACCOUNT_CONTENT = '#account-content';
var CALENDAR_CONTENT = '#calendar-content';
var BOAT_CONTENT = '#boat-content';

// hides all section elements
function showSection(sectionId) {
    $('section').hide();
    $(sectionId).show();
}

$(document).ready(function() {
    showSection(LOGIN_CONTENT);

    $('#account-link').on('click', function() {
        showSection(ACCOUNT_CONTENT);
    });

    $('#calendar-link').on('click', function() {
        showSection(CALENDAR_CONTENT);
        $('#calendar').fullCalendar({
            // put your options and callbacks here
        })
    });

    $('#boat-link').on('click', function() {
        showSection(BOAT_CONTENT);
    });

});

