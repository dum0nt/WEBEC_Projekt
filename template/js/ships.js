var SHIPLIST_HEADER = '<tr><td colspan="4" style="padding:8px 0"><h1>Schiffsliste</h1></td></tr><tr><th>Schiffsname</th><th>Typ</th><th>Standort</th><th>Anlegeplatz</th></tr>'
var TEMP_BERTH_ID;
var TEMP_BERTH_TOWN;
var TEMP_BOAT_NAME;
var TEMP_BERTH_TOWN_NAME;
var TEMP_BERTH_TOWN_ID;
var TEMP_BERTH_NUMBER;

function loadShips(){
    var shiplist = $('.shiplist');
    shiplist.clear;
    shiplist.html(SHIPLIST_HEADER);
    $.ajax({
        url:"/ships",
        method:"GET",
        dataType: 'json',
        success:function(ships){
            ships.forEach(function (item) {
                var tr = document.createElement('tr');
                var td1 = document.createElement('td');
                var td2 = document.createElement('td');
                var td3 = document.createElement('td');
                var td4 = document.createElement('td');
                var p = document.createElement('p');
                p = item.ShipName;
                td1.append(p);
                p = item.ShipType;
                td2.append(p);
                p = findBerthTownName(item.ShipName);
                td3.append(p);
                p = findBerthNumber(item.BerthId);
                td4.append(p);
                tr.append(td1,td2,td3,td4);
                shiplist.append(tr);
            })
        },
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) }
    });
    loadBerthTownNames();
}

function saveShip() {
    var shipname = $('#ship-name').val();
    var shiptype = $('#ship-type').val();
    var berthtown = $('#ship-berthtown').val();
    var berth = $('#ship-berth').val();
    $.ajax({
       url:'/ships',
        type:'POST',
        data:{shipName:shipname,shipType:shiptype,berthId:berth},
        dataTyp:'json',
        success:function (response) {},
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) }
    });
    loadShips();
}

function loadBerthTownNames() {
    $('#ship-berthtown').empty();
    var response = $.ajax({
        url:"/berthtowns",
        method:"GET",
        dataType: 'json',
        success:function(berthtowns){
            var counter = 0;
            var emptyOption = new Option();
            $("#ship-berthtown").append(emptyOption);
            berthtowns.forEach(function (berthtown) {
                var option = new Option(berthtown.TownName, 'berthtown'+counter);
                counter += 1;
                $("#ship-berthtown").append(option);
            })
        },
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) }
    });
}
function loadBerths() {
    var select = document.getElementById('ship-berthtown');
    TEMP_BERTH_TOWN_NAME = select.options[select.selectedIndex].text;

    var berthtowns = $.ajax({
        url: '/berthtowns',
        typ: 'GET',
        async: false,
        dataType: 'json',
    }).responseJSON;
    berthtowns.forEach(function (berthtown) {
        if(berthtown.berthTownName === TEMP_BERTH_TOWN_NAME) TEMP_BERTH_TOWN_ID = berthtown.berthTownId;
    });

    $('#ship-berth').empty();
    var response = $.ajax({
        url:"/berths",
        method:"GET",
        dataType: 'json',
        data:{town:TEMP_BERTH_TOWN_ID},
        success:function(berthts){
            var counter = 1;
            var emptyOption = new Option();
            $("#ship-berth").append(emptyOption);
            berthts.forEach(function (berth) {
                if(berth.IsOccupied === false) {
                    var option = new Option(counter, berth.BerthNumber);
                    counter += 1;
                    $("#ship-berth").append(option);
                }
            })
        },
        error: function(jqXHR, textStatus, errorThrown) { console.error(jqXHR, textStatus, errorThrown) }
    });
}

function findBerthNumber(berthId) {
    TEMP_BERTH_ID = berthId;
    var berths = $.ajax({
        url: '/berths',
        async: false,
        dataType: 'json'
    }).responseJSON;
    berths.forEach(function (item) {
        if(item.BerthId === TEMP_BERTH_ID) TEMP_BERTH_NUMBER = item.BerthNumber;
    });
    return TEMP_BERTH_NUMBER;
}

function findBerthTownName(boatName){
    TEMP_BOAT_NAME = boatName;

    var ships = $.ajax({
        url: '/ships',
        async: false,
        dataType: 'json'
    }).responseJSON;
    ships.forEach(function (item) {
        if(item.ShipName === TEMP_BOAT_NAME) TEMP_BERTH_ID = item.BerthId;
    });

    var berths = $.ajax({
        url: '/berths',
        async: false,
        dataType: 'json',
    }).responseJSON;
    berths.forEach(function (item) {
        if(item.BerthId === TEMP_BERTH_ID) TEMP_BERTH_TOWN = item.BerthTownId;
    });

    var berthTownName = $.ajax({
        url: '/berthtowns',
        async: false,
        dataType: 'json'
    }).responseJSON;
    berthTownName.forEach(function (item) {
        if(item.BerthTownId === TEMP_BERTH_TOWN) TEMP_BERTH_TOWN_NAME = item.TownName;
    });

    return TEMP_BERTH_TOWN_NAME;
}

