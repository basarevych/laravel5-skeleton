/*
    Echoes date/time in browser timezone
*/
function printDateTime(timestamp) {
    if (timestamp == null)
        return;

    var local = moment.unix(timestamp).local();
    document.write(local.format('YYYY-MM-DD HH:mm:ss Z'));
}
