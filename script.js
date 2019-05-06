$(document).ready(function() {
    $("#menu li").click(function() {
        document.location = $(this).children().attr("href");
    });
});

function swapClass(swapObject, swapClass) {
    if (swapObject) {
        swapObject.className = swapClass;
    }
}

function ajaxData(id, file) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById(id).innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", file, true);
    xmlhttp.send();
}
