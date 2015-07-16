/**
 * Echoes date/time in browser current timezone
 *
 * @param {number} timestamp                Unix timestamp (seconds) not JS one (milliseconds)
 */
function printDateTime(timestamp) {
    if (timestamp == null)
        return;

    var local = moment.unix(timestamp).local();
    document.write(local.format('YYYY-MM-DD HH:mm:ss Z'));
}
