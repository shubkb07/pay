const app = {
	name: 'Pay',
	version: '1.0.0',
	dom: (() => {
		const elements = {};
		const elementsWithId = document.querySelectorAll("[id]");
	  
		// Convert kebab-case to camelCase
		const toCamelCase = (str) => {
		  return str
			.replace(/pay-/, "")
			.replace(/-([a-z])/g, (g) => g[1].toUpperCase());
		};
	  
		// Convert all elements with IDs to an object with camelCase keys
		elementsWithId.forEach((element) => {
		  const camelCaseId = toCamelCase(element.id);
		  elements[camelCaseId] = element;
		});
	  
		return elements;
	})(),
	
	// Dark mode functionality (system-based only)
	darkMode: {
		init() {
			// Check system preference
			if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.classList.add('dark');
			}
			
			// Listen for system changes
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
				if (e.matches) {
					document.documentElement.classList.add('dark');
				} else {
					document.documentElement.classList.remove('dark');
				}
			});
		}
	},
	
	// Mobile menu functionality with enhanced accessibility
	mobileMenu: {
		// Store focusable elements and last focused element
		focusableElements: [],
		lastFocusedElement: null,
		
		init() {
			// Add click handler for menu button
			if (app.dom.mobileMenuButton) {
				app.dom.mobileMenuButton.addEventListener('click', (e) => {
					e.stopPropagation();
					app.mobileMenu.toggle();
				});
			}
			
			// Add click handler for overlay
			if (app.dom.mobileMenuOverlay) {
				app.dom.mobileMenuOverlay.addEventListener('click', () => {
					app.mobileMenu.close();
				});
			}
			
				// Add click handler for close button inside mobile menu
			if (app.dom.closeMobileMenu) {
				app.dom.closeMobileMenu.addEventListener('click', () => {
					app.mobileMenu.close();
				});
			}
			
			// Close menu when clicking outside
			document.addEventListener('click', (e) => {
				if (app.dom.mobileMenu && 
					!app.dom.mobileMenu.classList.contains('hidden') && 
					!app.dom.mobileMenu.contains(e.target) && 
					!app.dom.mobileMenuButton.contains(e.target)) {
					app.mobileMenu.close();
				}
			});
			
			// Close menu when pressing Escape
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape' && 
					app.dom.mobileMenu && 
					!app.dom.mobileMenu.classList.contains('hidden')) {
					app.mobileMenu.close();
				}
				
				// Handle tab key for focus trap when menu is open
				if (e.key === 'Tab' && 
					app.dom.mobileMenu && 
					!app.dom.mobileMenu.classList.contains('hidden')) {
					app.mobileMenu.trapFocus(e);
				}
			});
		},
		
		toggle() {
			if (app.dom.mobileMenu && app.dom.mobileMenu.classList.contains('hidden')) {
				this.open();
			} else {
				this.close();
			}
		},
		
		open() {
			if (app.dom.mobileMenu && app.dom.mobileMenuOverlay) {
				// Store the element that had focus before opening menu
				this.lastFocusedElement = document.activeElement;
				
				// Show the menu and overlay
				app.dom.mobileMenu.classList.remove('hidden');
				app.dom.mobileMenuOverlay.classList.remove('hidden');
				
				// Update ARIA attributes
				app.dom.mobileMenuButton.setAttribute('aria-expanded', 'true');
				
				// Change the icon
				const icon = app.dom.mobileMenuButton.querySelector('i');
				if (icon) {
					icon.classList.remove('fa-bars');
					icon.classList.add('fa-times');
				}
				
				// Get all focusable elements within the menu
				this.focusableElements = this.getFocusableElements(app.dom.mobileMenu);
				
				// Focus the first element after a short delay (for animation)
				setTimeout(() => {
					if (this.focusableElements.length > 0) {
						this.focusableElements[0].focus();
					} else {
						app.dom.mobileMenu.focus();
					}
				}, 100);
				
				// Prevent body scrolling
				document.body.classList.add('overflow-hidden');
			}
		},
		
		close() {
			if (app.dom.mobileMenu && app.dom.mobileMenuOverlay) {
				// Hide the menu and overlay
				app.dom.mobileMenu.classList.add('hidden');
				app.dom.mobileMenuOverlay.classList.add('hidden');
				
				// Update ARIA attributes
				app.dom.mobileMenuButton.setAttribute('aria-expanded', 'false');
				
				// Change the icon back
				const icon = app.dom.mobileMenuButton.querySelector('i');
				if (icon) {
					icon.classList.remove('fa-times');
					icon.classList.add('fa-bars');
				}
				
				// Return focus to the element that had it before the menu was opened
				if (this.lastFocusedElement) {
					this.lastFocusedElement.focus();
				}
				
				// Re-enable body scrolling
				document.body.classList.remove('overflow-hidden');
			}
		},
		
		// Get all focusable elements within an element
		getFocusableElements(parent) {
			const focusableSelectors = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
			return Array.from(parent.querySelectorAll(focusableSelectors))
				.filter(el => !el.disabled && el.offsetParent !== null); // Filter out disabled and hidden elements
		},
		
		// Keep focus within the menu when it's open (focus trap)
		trapFocus(event) {
			// If no focusable elements, do nothing
			if (this.focusableElements.length === 0) return;
			
			const firstElement = this.focusableElements[0];
			const lastElement = this.focusableElements[this.focusableElements.length - 1];
			
			// If shift+tab and focus is on first element, move to last element
			if (event.shiftKey && document.activeElement === firstElement) {
				event.preventDefault();
				lastElement.focus();
			}
			// If tab and focus is on last element, move to first element
			else if (!event.shiftKey && document.activeElement === lastElement) {
				event.preventDefault();
				firstElement.focus();
			}
		}
	},
	
	// Initialize all common functionality
	init() {
		app.darkMode.init();
		app.mobileMenu.init();
	}
};

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', app.init);

export {app};