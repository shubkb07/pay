import {app} from './app.js';

const homePage = {
    init() {
        this.initAnimations();
        this.initSmoothScroll();
    },
    
    initAnimations() {
        // Simple animation for pre-loaded hero section
        const heroElements = document.querySelectorAll('#pay-hero-title, #pay-hero-subtitle, #pay-hero-buttons');
        heroElements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('opacity-100');
            }, index * 200);
        });
        
        // Handle scroll animations
        const animatedElements = document.querySelectorAll('.appear-once');
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, { 
                threshold: 0.1,
                rootMargin: '0px 0px -10% 0px'
            });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            animatedElements.forEach(element => {
                element.classList.add('animate-fade-in');
            });
        }
    },
    
    initSmoothScroll() {
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
                        top: targetElement.offsetTop - headerHeight - 20, // Extra 20px for spacing
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    homePage.init();
});