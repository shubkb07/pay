import {app} from './app.js';

const homePage = {
    init() {
        // Initialize section animations
        this.initSectionAnimations();
        
        // Enable smooth scrolling for anchor links
        this.initSmoothScroll();
    },
    
    initSectionAnimations() {
        // Add intersection observer to handle scroll animations
        const sections = document.querySelectorAll('.animate-on-scroll');
        
        if (sections.length && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Add animation to the section itself
                        entry.target.classList.add('animate-fade-in-up');
                        
                        // Find child elements to animate with staggered delays
                        const animatedChildren = entry.target.querySelectorAll('.grid > div, .flex > div, h2, h3, p, .grid > a');
                        
                        if (animatedChildren.length) {
                            Array.from(animatedChildren).forEach((el, index) => {
                                setTimeout(() => {
                                    el.classList.add('animate-fade-in-up');
                                }, index * 100); // Stagger by 100ms
                            });
                        }
                        
                        // Stop observing after animation
                        observer.unobserve(entry.target);
                    }
                });
            }, { 
                threshold: 0.1,  // Trigger when 10% of element is visible
                rootMargin: '0px 0px -10% 0px' // Trigger slightly before element enters viewport
            });
            
            sections.forEach(section => {
                // Make children initially invisible
                const animatedChildren = section.querySelectorAll('.grid > div, .flex > div, h2, h3, p, .grid > a');
                Array.from(animatedChildren).forEach(el => {
                    el.style.opacity = '0';
                });
                
                // Observe the section
                observer.observe(section);
            });
        }
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
                    window.scrollTo({
                        top: targetElement.offsetTop - 80, // Offset for header
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
};

// Initialize home page functionality
document.addEventListener('DOMContentLoaded', () => {
    homePage.init();
});