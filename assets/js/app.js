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
	
	// Mobile menu functionality
	mobileMenu: {
		init() {
			if (app.dom.mobileMenuButton) {
				app.dom.mobileMenuButton.addEventListener('click', app.mobileMenu.toggle);
			}
			
			// Add click event for overlay to close menu
			if (app.dom.mobileMenuOverlay) {
				app.dom.mobileMenuOverlay.addEventListener('click', app.mobileMenu.close);
			}
			
			// Add event listener for pressing escape key
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape') {
					app.mobileMenu.close();
				}
			});
			
			// Add event listener for clicks outside menu
			document.addEventListener('click', (e) => {
				if (app.dom.mobileMenu && 
					!app.dom.mobileMenu.contains(e.target) && 
					!app.dom.mobileMenuButton.contains(e.target) && 
					!app.dom.mobileMenu.classList.contains('hidden')) {
					app.mobileMenu.close();
				}
			});
		},
		toggle() {
			const isVisible = !app.dom.mobileMenu.classList.contains('hidden');
			
			if (isVisible) {
				app.mobileMenu.close();
			} else {
				app.mobileMenu.open();
			}
		},
		open() {
			app.dom.mobileMenu.classList.remove('hidden');
			app.dom.mobileMenuOverlay.classList.remove('hidden');
			document.body.classList.add('overflow-hidden');
			
			const icon = app.dom.mobileMenuButton.querySelector('i');
			if (icon) {
				icon.classList.remove('fa-bars');
				icon.classList.add('fa-times');
			}
		},
		close() {
			app.dom.mobileMenu.classList.add('hidden');
			app.dom.mobileMenuOverlay.classList.add('hidden');
			document.body.classList.remove('overflow-hidden');
			
			const icon = app.dom.mobileMenuButton.querySelector('i');
			if (icon) {
				icon.classList.remove('fa-times');
				icon.classList.add('fa-bars');
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