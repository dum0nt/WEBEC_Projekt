var QUERY_FORECAST = 'http://api.openweathermap.org/data/2.5/forecast/daily?units=metric&cnt=7&appid=f123fa9f14160191af9e90b4a039bddc&q=';
var BOAT_NAME;
var BOAT_ID;
var BERTH_ID;
var BERTH_TOWN;
var BERTH_TOWN_NAME;
var CALENDAR;

function loadDropdownContent() {
    var select = $('#calendar_dropdown');
    select.empty();
    select.append('<option value="" disabled selected>W&auml;hlen Sie ein Schiff ...</option>');
    var counter = 0;
    data('/ships', function (ships) {
        ships.forEach(function (item) {
            var value = 'boat' + counter;
            var text = item.ShipName;
            select.append('<option value="' + value + '">' + text + '</option>');
            counter += 1;
        })
    });
}

function loadCalendar(){
    CALENDAR = $('#calendar');
    CALENDAR.fullCalendar({
        header: {
            left:   '',
            center: 'title',
            right:  'today prev,next'
        },
        eventRender: function(event,element,view) {
            if (event.icon && event.allDay) {
                var img = document.createElement("img");
                img.src = "/template/img/"+event.icon+".png";
                var toInsert = element.find('div:first');
                var temp ='<p id="forecast-temp">'+event.temp+'</p>';
                var direction = '<p id="forecast-deg">'+evaluateWindDirection(event.direction)+'</p>';
                var speed = '<p id="forecast-speed">'+(parseFloat(event.speed)*1.94384).toFixed(1).toString()+" knoten"+'</p>';
                toInsert.prepend(img,temp,direction,speed);
            }
        },
        defaultView: 'agendaWeek',
        locale: 'de',
        height: 'auto',
        minTime: '08:00:00',
        maxTime: '18:00:00',
    });
}

function loadSelectedShipCalendar() {
    var select = document.getElementById('calendar_dropdown');
    var boatName = select.options[select.selectedIndex].text;

    if(boatName!="Wählen Sie ein Schiff ..."){
        BOAT_NAME  = boatName;
        loadCalendar();
        loadWeatherData();
        loadReservations();
    }
}

function loadReservations() {
    $('#calendar').fullCalendar('removeEvents');
    $.ajax({
        url:'/reservations',
        type:'GET',
        dataType:'json',
        success:function (reservations) {
            reservations.forEach(function (reservation) {
                if(BOAT_NAME === reservation.shipName) {
                    var event = {
                        id: reservation.reservationId,
                        title: reservation.userName,
                        start: reservation.startTime,
                        end: reservation.endTime,
                    };
                    $('#calendar').fullCalendar('renderEvent', event, true);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) }
    });
}

function data(url, successFunction) {
    $.ajax({
        url: url,
        dataType: 'json',
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) },
        success: successFunction
    });
}

function loadWeatherData() {
    var cityName = findCityName(BOAT_NAME);

    data(QUERY_FORECAST+cityName,function (city){
        var today = new Date();
        var event={id:0,temp:city.list[0].temp.day+" °C",direction:city.list[0].deg, speed:city.list[0].speed,
            textColor:"black",start:today,color:"transparent",allDay:true, icon:city.list[0].weather[0].icon, stick:true};
        $('#calendar').fullCalendar('removeEvents',0);
        $('#calendar').fullCalendar( 'renderEvent', event,true);

        var tomorrow = new Date().setDate(today.getDate()+1);
        var event={id:1,temp:city.list[1].temp.day+" °C",direction:city.list[1].deg, speed:city.list[1].speed,
            textColor:"black",start:tomorrow,color:"transparent",allDay:true, icon:city.list[1].weather[0].icon};
        $('#calendar').fullCalendar('removeEvents',1);
        $('#calendar').fullCalendar( 'renderEvent', event,true);

        var thirdDay = new Date().setDate(today.getDate()+2);
        var event={id:2,temp:city.list[2].temp.day+" °C",direction:city.list[2].deg, speed:city.list[2].speed,
            textColor:"black",start:thirdDay,color:"transparent",allDay:true, icon:city.list[2].weather[0].icon};
        $('#calendar').fullCalendar('removeEvents',2);
        $('#calendar').fullCalendar( 'renderEvent', event,true);

        var fourthDay = new Date().setDate(today.getDate()+3);
        var event={id:3,temp:city.list[3].temp.day+" °C",direction:city.list[3].deg, speed:city.list[3].speed,
            textColor:"black",start:fourthDay,color:"transparent",allDay:true, icon:city.list[3].weather[0].icon};
        $('#calendar').fullCalendar('removeEvents',3);
        $('#calendar').fullCalendar( 'renderEvent', event,true);

        var fifthDay = new Date().setDate(today.getDate()+4);
        var event={id:4,temp:city.list[4].temp.day+" °C",direction:city.list[4].deg, speed:city.list[4].speed,
            textColor:"black",start:fifthDay,color:"transparent",allDay:true, icon:city.list[4].weather[0].icon};
        $('#calendar').fullCalendar('removeEvents',4);
        $('#calendar').fullCalendar( 'renderEvent', event,true);

        var sixthDay = new Date().setDate(today.getDate()+5);
        var event={id:5,temp:city.list[5].temp.day+" °C",direction:city.list[5].deg, speed:city.list[5].speed,
            textColor:"black",start:sixthDay,color:"transparent",allDay:true, icon:city.list[5].weather[0].icon};
        $('#calendar').fullCalendar('removeEvents',5);
        $('#calendar').fullCalendar( 'renderEvent', event,true);

        var seventhDay = new Date().setDate(today.getDate()+6);
        var event={id:6,temp:city.list[6].temp.day+" °C",direction:city.list[6].deg, speed:city.list[6].speed,
            textColor:"black",start:seventhDay,color:"transparent",allDay:true, icon:city.list[6].weather[0].icon};
        $('#calendar').fullCalendar('removeEvents',6);
        $('#calendar').fullCalendar( 'renderEvent', event,true);
    });
}

function findCityName(){
    var ships = $.ajax({
        url: '/ships',
        async: false,
        dataType: 'json'
    }).responseJSON;
    ships.forEach(function (item) {
        if(item.ShipName === BOAT_NAME) BERTH_ID = item.BerthId;
    });

    var berths = $.ajax({
        url: '/berths',
        async: false,
        dataType: 'json'
    }).responseJSON;
    berths.forEach(function (item) {
        if(item.BerthId === BERTH_ID) BERTH_TOWN = item.BerthTownId;
    });

    var berthTownName = $.ajax({
        url: '/berthtowns',
        async: false,
        dataType: 'json'
    }).responseJSON;
    berthTownName.forEach(function (item) {
        if(item.BerthTownId === BERTH_TOWN) BERTH_TOWN_NAME = item.TownName;
    });
    return BERTH_TOWN_NAME;
}

function saveReservation() {
    var date = $('#reservation-date').val();
    var from = $('#reservation-from').val();
    var to = $('#reservation-to').val();

    var ships = $.ajax({
        url: '/ships',
        async: false,
        dataType: 'json'
    }).responseJSON;
    ships.forEach(function (item) {
        if(item.ShipName === BOAT_NAME) BOAT_ID = item.ShipId;
    });

    var startTime = date+" "+from;
    var endTime = date+" "+to;
    $.ajax({
        url:'/reservations',
        type:'POST',
        data:{userId:2,startTime:startTime,endTime:endTime,shipId:BOAT_ID},                       /* XXX UserId speichern XXXX */
        success:function () {
            loadSelectedShipCalendar();
            showCalendar();
        },
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) }
    })
}


function evaluateWindDirection(direction) {
    var deg = parseFloat(direction);
    switch(true){
        case (deg<22.5): return "Nord";
        case (22.5<deg<67.5): return "Nord Ost";
        case (67.5<deg<112.5): return "Ost";
        case (112.5<deg<157.5): return "Süd Ost";
        case (157.5<deg<202.5): return "Süd";
        case (202.5<deg<247.5): return "Süd West";
        case (247.5<deg<292.5): return "West";
        case (292.5<deg<337.5): return "Nord West";
        case (337.5<deg): return "Nord";
    }
}


