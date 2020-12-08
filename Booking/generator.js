/* ----------------------------------------------------------------------------------
                                    Random Number Generator (script)
------------------------------------------------------------------------------------*/
//  Creates a random string of numbers and letters 
//  using the time stamp and converting it to hex
function getID() {
    var length = 9;
    var id = "";
    var hex = '';
    var timestamp = new Date;
    var timestamp = timestamp.getTime();
    var timestamp = timestamp.toString();
    var ts = timestamp.toString();
    var parts = ts.split("").reverse();


    console.log('A:timestamp.length ->' + timestamp.length);
    for (var i = 0; i < length; ++i) {
        var index = Math.floor(Math.random() * (parts.length));
        id += parts[index];
    }
    id = parseInt(id);
    hex = id.toString(16).toUpperCase();
    id = parseInt(hex, 16);
    return hex;
}