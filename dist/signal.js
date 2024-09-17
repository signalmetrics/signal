const signal = (() => {

    // Get the current script tag and check for data-endpoint attribute
    const currentScript = document.currentScript || document.querySelector('script[data-endpoint]');
    const defaultEndpoint = '/analytics/event';
    const endpoint = currentScript ? currentScript.getAttribute('data-endpoint') || defaultEndpoint : defaultEndpoint;

    // Global variable to hold visit_signature
    let visit_signature;

    // Function to collect metadata
    function metadata() {
        const load_time = window.performance.timing.domInteractive - window.performance.timing.navigationStart;
        return {
            dispatch_moment: Date.now(),
            visit_signature: visit_signature,
            custom_user_id:  currentScript.getAttribute('data-custom-user-id'),
            referrer: document.referrer,
            url: window.location.href,
            title: document.title,
            screen_resolution: {
                x: window.screen.width,
                y: window.screen.height
            },
            viewport: {
                x: window.innerWidth,
                y: window.innerHeight
            },
            language: navigator.language,
            timezone_offset_minutes: new Date().getTimezoneOffset(),
            page_load_time_ms: load_time,
            connection_type: navigator.connection && navigator.connection.effectiveType ? navigator.connection.effectiveType : "unknown",
        };
    }

    // Function to generate visit_signature
    function generateVisitSignature() {
        // Use the standard Unix epoch (since 1970)
        const now = Date.now(); // Current time in seconds since 1970

        // Generate a random salt (for example, 5-digit random number)
        const randomSalt = Math.floor(Math.random() * 90000) + 10000;

        // Combine the epoch time and random salt
        return `${now}${randomSalt}`;
    }

    // Function to encode query parameters
    function encodeParameters(params) {
        return "?" + Object.keys(params).map(function (key) {
            return encodeURIComponent(key) + "=" + encodeURIComponent(
                typeof params[key] === 'object' ? JSON.stringify(params[key]) : params[key]
            );
        }).join("&");
    }

    // Function to send tracking data via GET request using an image element
    function sendImageTracking(params) {
        const img = document.createElement("img");
        img.setAttribute("alt", "");
        img.setAttribute("aria-hidden", "true");
        img.style.position = "absolute";
        img.src = endpoint + encodeParameters(params);
        img.addEventListener("load", function () {
            img.parentNode && img.parentNode.removeChild(img);
        });
        img.addEventListener("error", function () {
            img.parentNode && img.parentNode.removeChild(img);
        });
        document.body.appendChild(img);
    }

    // Function to send tracking data via Beacon API
    function sendBeaconTracking(params) {
        const data = JSON.stringify(params);
        navigator.sendBeacon(endpoint, data);
    }

    setTimeout(function () {
        signal.trackPageview();
    })

    // Public API
    return {
        trackPageview: function () {
            // Generate visit_signature when the pageview is tracked
            visit_signature = generateVisitSignature();

            const params = {
                type: 'page_view',
                metadata: metadata()
            };

            // Use the image request to send the data
            sendImageTracking(params);
        },
        fire: function (eventName, eventData = {}) {
            const params = {
                type: eventName,
                data: eventData,
                metadata: metadata() // Attach the metadata, including visit_signature
            };

            // Use the Beacon API to send the data
            sendBeaconTracking(params);
        }
    };
})();

// Example usage:
// Track a pageview
// signal.trackPageview();

// Track an event
// signal.fire('button_click', { button_id: 'signup' });

window.addEventListener('beforeunload', function (e) {
    signal.fire('page_unload');
});
