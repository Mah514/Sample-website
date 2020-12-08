/* ----------------------------------------------------------------------------------
                                    Cookie Controls (script)
------------------------------------------------------------------------------------*/
//  make cookie
function setCookie(name, value, daysToLive) {
    var cookie = name + "=" + encodeURIComponent(value);

    if (typeof daysToLive === "number") {
        cookie += "; max-age=" + (daysToLive * 1);

        document.cookie = cookie;
    }
}

//  read cookie
function getCookie(name) {
    var cookiesArray = document.cookie.split(";");

    for (var i = 0; i < cookiesArray.length; i++) {
        var cookieFound = cookiesArray[i].split("=");

        if (name == cookieFound[0].trim()) {
            return decodeURIComponent(cookieFound[1]); //  value of cookie found
        }
    }

    return null; // null, no cookie found
}