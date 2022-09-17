let prod = true;
// prod = false;

if (prod) {

    // console.log = function () {
    // };

    function silentErrorHandler() {
        return true;
    }

    window.onerror = silentErrorHandler;
}


