import {app} from './app.js';

const notFoundPage = {
    init() {
        this.displayRequestedUrl();
        this.setupBackButton();
        this.initAnimations();
    },
    
    displayRequestedUrl() {
        // Get the URL that was requested and display it
        const urlElement = document.getElementById('pay-requested-url');
        if (urlElement) {
            const currentUrl = window.location.href;
            urlElement.textContent = currentUrl;
        }
    },
    
    setupBackButton() {
        // Set up the "Go Back" button functionality
        const backButton = document.getElementById('pay-go-back');
        if (backButton) {
            backButton.addEventListener('click', () => {
                // Check if we have history to go back to
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    // If no history, go to homepage
                    window.location.href = '/';
                }
            });
        }
    },
    
    initAnimations() {
        // Ensure animations play properly
        const animatedElements = document.querySelectorAll('.animate-fade-in');
        
        // Force animations to play again if needed
        animatedElements.forEach(el => {
            el.style.opacity = '0';
            
            // Tiny timeout to ensure the style change takes effect
            setTimeout(() => {
                el.style.opacity = '';
            }, 10);
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    notFoundPage.init();
});

// Track 404 errors in analytics if available
window.addEventListener('load', () => {
    // Check if any analytics object exists (like Google Analytics)
    if (typeof gtag === 'function') {
        gtag('event', 'page_view', {
            'event_category': 'error',
            'event_label': '404: ' + window.location.pathname + window.location.search
        });
    }
});
