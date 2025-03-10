import {app} from './app.js';

const homePage = {
    init() {
        this.initHeroAnimations();
        this.initScrollAnimations();
        this.initSmoothScroll();
    },
    
    initHeroAnimations() {
        // Animate hero section elements on page load
        const heroElements = document.querySelectorAll('#pay-hero-title, #pay-hero-subtitle, #pay-hero-buttons');
        heroElements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('opacity-100');
            }, 100 + index * 200);
        });
    },
    
    initScrollAnimations() {
        // Find all elements that should animate on scroll
        const animatedElements = document.querySelectorAll('.appear-once');
        
        // First make elements already in viewport visible immediately
        this.showVisibleElements(animatedElements);
        
        // Then set up scroll detection for other elements
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Make element visible
                        entry.target.classList.add('animate-fade-in');
                        // Stop watching this element
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                // Trigger animation when element is 10% visible
                threshold: 0.1,
                // Start animation slightly before element enters viewport
                rootMargin: '0px 0px -5% 0px'
            });
            
            // Start observing each element
            animatedElements.forEach(element => {
                observer.observe(element);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            this.showAllElements(animatedElements);
        }
        
        // Add a safety timeout to ensure elements eventually appear
        setTimeout(() => this.showAllElements(animatedElements), 3000);
    },
    
    // Show elements that are already visible in the viewport
    showVisibleElements(elements) {
        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            
            // Element is at least partially in viewport
            if (rect.top <= windowHeight && rect.bottom >= 0) {
                element.classList.add('animate-fade-in');
            }
        });
    },
    
    // Make all elements visible (fallback)
    showAllElements(elements) {
        elements.forEach(element => {
            if (!element.classList.contains('animate-fade-in')) {
                element.classList.add('animate-fade-in');
            }
        });
    },
    
    initSmoothScroll() {
        // Handle anchor links with smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Close mobile menu if open
                    if (app.dom.mobileMenu && !app.dom.mobileMenu.classList.contains('hidden')) {
                        app.mobileMenu.close();
                    }
                    
                    // Get header height for offset
                    const headerHeight = document.querySelector('header').offsetHeight;
                    
                    // Smooth scroll with offset for header
                    window.scrollTo({
                        top: targetElement.offsetTop - headerHeight - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    homePage.init();
});

// Ensure animations still work after page is fully loaded
window.addEventListener('load', () => {
    setTimeout(() => {
        const animatedElements = document.querySelectorAll('.appear-once');
        homePage.showVisibleElements(animatedElements);
    }, 500);
});

// Handle animations on scroll manually as a fallback
window.addEventListener('scroll', () => {
    const animatedElements = document.querySelectorAll('.appear-once:not(.animate-fade-in)');
    homePage.showVisibleElements(animatedElements);
});