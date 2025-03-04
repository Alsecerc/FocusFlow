
// This script fixes absolute URLs that may have incorrect port numbers
document.addEventListener('DOMContentLoaded', function() {
    // Get the server port we're actually running on
    const currentPort = window.location.port || '80';
    
    // Fix all links
    document.querySelectorAll('a').forEach(link => {
        // Only fix links to our own domain
        if (link.href.includes('localhost:8081')) {
            // Replace the incorrect port with the current one
            link.href = link.href.replace('localhost:8081', 'localhost:' + currentPort);
            console.log('Fixed link:', link.href);
        }
    });
    
    // Also fix any hardcoded fetch/AJAX requests
    const originalFetch = window.fetch;
    window.fetch = function(url, options) {
        if (typeof url === 'string' && url.includes('localhost:8081')) {
            url = url.replace('localhost:8081', 'localhost:' + currentPort);
            console.log('Fixed fetch URL:', url);
        }
        return originalFetch(url, options);
    };
});
