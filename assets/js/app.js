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
	
	// Dark mode functionality
	darkMode: {
		init() {
			// Check system preference
			if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.classList.add('dark');
			}
			
			// Setup event listeners for theme toggles
			if (app.dom.themeToggle) {
				app.dom.themeToggle.addEventListener('click', app.darkMode.toggle);
			}
			if (app.dom.mobileThemeToggle) {
				app.dom.mobileThemeToggle.addEventListener('click', app.darkMode.toggle);
			}
			
			// Listen for system changes
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
				if (e.matches) {
					document.documentElement.classList.add('dark');
				} else {
					document.documentElement.classList.remove('dark');
				}
			});
		},
		toggle() {
			document.documentElement.classList.toggle('dark');
		}
	},
	
	// Mobile menu functionality
	mobileMenu: {
		init() {
			if (app.dom.mobileMenuButton && app.dom.mobileMenu) {
				app.dom.mobileMenuButton.addEventListener('click', app.mobileMenu.toggle);
			}
		},
		toggle() {
			app.dom.mobileMenu.classList.toggle('hidden');
			const icon = app.dom.mobileMenuButton.querySelector('i');
			if (icon) {
				if (icon.classList.contains('fa-bars')) {
					icon.classList.remove('fa-bars');
					icon.classList.add('fa-times');
				} else {
					icon.classList.remove('fa-times');
					icon.classList.add('fa-bars');
				}
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