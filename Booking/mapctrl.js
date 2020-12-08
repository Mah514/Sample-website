/* ----------------------------------------------------------------------------------
                                    Map Controls (script)
------------------------------------------------------------------------------------*/
//  Updates the map on address change
function mapUpdate() {
    var t = document.querySelector('#timeSlots');
    var t_text = t.options[t.selectedIndex].value;

    if (t_text == 'ACAD') {
        mymap.setView([acadLAT, acadLNG], 16);

        circleMarker.setRadius(100);
        circleMarker.setLatLng([acadLAT, acadLNG]);
    }
    if (t_text == 'CAE') {
        mymap.setView([caeLAT, caeLNG], 16);

        circleMarker.setRadius(100);
        circleMarker.setLatLng([caeLAT, caeLNG]);
    }
    if (t_text == 'STHU') {
        mymap.setView([sthuLAT, sthuLNG], 16);

        circleMarker.setRadius(100);
        circleMarker.setLatLng([sthuLAT, sthuLNG]);
    }
}