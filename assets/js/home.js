import {app} from './app.js';

const homePage = {
    init() {
        // Initialize hero animation
        this.initHeroAnimation();
        
        // Initialize section animations
        this.initSectionAnimations();
    },
    
    initHeroAnimation() {
        if (app.dom.heroTitle) {
            // Add fade-in animation class
            app.dom.heroTitle.classList.add('animate-fade-in');
        }
        
        if (app.dom.heroSubtitle) {
            // Delayed fade-in for subtitle
            setTimeout(() => {
                app.dom.heroSubtitle.classList.add('animate-fade-in');
            }, 300);
        }
        
        if (app.dom.heroButtons) {
            // Delayed fade-in for buttons
            setTimeout(() => {
                app.dom.heroButtons.classList.add('animate-fade-in');
            }, 600);
        }
    },
    
    initSectionAnimations() {
        // Add intersection observer to handle scroll animations
        const sections = document.querySelectorAll('.animate-on-scroll');
        
        if (sections.length && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            
            sections.forEach(section => {
                observer.observe(section);
            });
        }
    }
};

// Initialize home page functionality
document.addEventListener('DOMContentLoaded', () => {
    homePage.init();
});