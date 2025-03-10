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
        
        // First make all animated elements visible that are already in viewport
        this.applyInitialAnimations(animatedElements);
        
        if (animatedElements.length && 'IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Apply different animations based on section
                        this.animateElement(entry.target);
                        
                        // Also animate child elements if any
                        const childElements = entry.target.querySelectorAll('.appear-once');
                        if (childElements.length) {
                            Array.from(childElements).forEach((el, index) => {
                                setTimeout(() => {
                                    this.animateElement(el);
                                }, index * 100); // Stagger by 100ms
                            });
                        }
                        
                        // Stop observing after animation applied
                        observer.unobserve(entry.target);
                    }
                });
            }, { 
                threshold: 0.05, // Lower threshold to trigger earlier
                rootMargin: '0px 0px -5% 0px'
            });
            
            // Start observing all animated elements
            animatedElements.forEach(element => {
                // Skip elements already animated
                if (!element.classList.contains('animate-fade-in') && 
                    !element.classList.contains('animate-fade-up') &&
                    !element.classList.contains('animate-scale-in')) {
                    observer.observe(element);
                }
            });
        } else {
            // Fallback if IntersectionObserver is not available
            animatedElements.forEach(element => {
                element.style.opacity = '1';
                element.classList.remove('appear-once');
            });
        }
    },
    
    // Apply animations to elements already in viewport on page load
    applyInitialAnimations(elements) {
        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const isInViewport = (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
            
            if (isInViewport) {
                this.animateElement(element);
            }
        });
    },
    
    // Apply appropriate animation to an element
    animateElement(element) {
        // Choose animation based on section or element type
        const section = element.closest('section');
        let animationClass = 'animate-fade-in';
        
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
        
        // If element is a card, use fade-up animation
        if (element.classList.contains('card')) {
            animationClass = 'animate-fade-up';
        }
        
        element.classList.add(animationClass);
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
                        top: targetElement.offsetTop - headerHeight,
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
    
    // Ensure animations work even if content is loaded after DOMContentLoaded
    window.addEventListener('load', () => {
        homePage.initSectionAnimations();
    });
});