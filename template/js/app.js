function ajaxRequest(url, method, parameters, donefunction) {
    var timer = null;

    req = $.ajax({
        url: url,
        context: document.body,
        method: method,
        dataType: 'json',
        data: parameters,
    });

    req.done(function(data) {
        clearTimeout(timer);
        // removeOverlay();
        donefunction(data);
    });

    req.fail(function(data) {
        clearTimeout(timer);
        // removeOverlay();
        displayError(data.responseJSON);
    });
}

function displayError(errormsg) {
    console.log("Error: " + errormsg);
}
