import {app} from './app.js';

const homePage = {
    init() {
        // Initialize section animations
        this.initSectionAnimations();
        
        // Enable smooth scrolling for anchor links
        this.initSmoothScroll();
    },
    
    initSectionAnimations() {
        // Handle sections with appear-once class
        const animatedElements = document.querySelectorAll('.appear-once');
        
        if (animatedElements.length && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Apply different animations based on section
                        const section = entry.target.closest('section');
                        let animationClass = 'animate-fade-in';
                        
                        // Choose animation based on section content or position
                        if (section) {
                            const sectionIndex = Array.from(document.querySelectorAll('section')).indexOf(section);
                            
                            switch (sectionIndex % 5) {
                                case 1: animationClass = 'animate-fade-up'; break;
                                case 2: animationClass = 'animate-fade-left'; break;
                                case 3: animationClass = 'animate-fade-right'; break;
                                case 4: animationClass = 'animate-scale-in'; break;
                                default: animationClass = 'animate-fade-in';
                            }
                        }
                        
                        // Apply animation with slight delay to ensure no flickering
                        setTimeout(() => {
                            entry.target.classList.add(animationClass);
                        }, 50);
                        
                        // Stop observing after animation applied
                        observer.unobserve(entry.target);
                    }
                });
            }, { 
                threshold: 0.15,
                rootMargin: '0px 0px -5% 0px'
            });
            
            // Start observing all animated elements
            animatedElements.forEach(element => {
                observer.observe(element);
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
                    // Close mobile menu if open
                    if (app.dom.mobileMenu && !app.dom.mobileMenu.classList.contains('hidden')) {
                        app.mobileMenu.close();
                    }
                    
                    // Smooth scroll with offset for header
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