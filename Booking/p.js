/* ----------------------------------------------------------------------------------
                                Pass Visibility (script)
------------------------------------------------------------------------------------*/
/* Changes the input type of the password field on icon click */
var visibility = {
    v: false,
};

function isVisible(v) {
    visibility[v] = !visibility[v];

    var state = visibility[v];

    if (state == true) {
        document.getElementById("showPASS").innerHTML = '<i class="material-icons">&#xe8f4;</i>'; /* visibility on */
        document.getElementById("confirmPass").type = 'text';

    } else if (state == false) {
        document.getElementById("showPASS").innerHTML = '<i class="material-icons">&#xe8f5;</i>'; /* visibility off */
        document.getElementById("confirmPass").type = 'password';
    }

}