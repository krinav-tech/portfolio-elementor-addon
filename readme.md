
# Portfolio Elementor Addon

This plugin was developed to meet specific client requirements, as no existing add-on fully addressed their needs. The plugin is designed to manage and display portfolio items with advanced filtering, AJAX, and pagination features. It utilizes color-thief to dynamically extract dominant colors from images and displays them within the portfolio items.


## Features

- Dynamic Color Extraction: Uses the color-thief library to extract colors from images. Includes the Color Thief script for color analysis:  <script src="https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.min.js?ver=2.3.0" id="color-thief-js"></script>

- Custom Post Type Portfolio Integration: Integrates with ACF to manage and display portfolio items.
- Filtering Options: Allows users to filter portfolio items based on various attributes.
- AJAX Support: Supports AJAX for smoother user interactions without page reloads.
- Pagination: Adds custom pagination to navigate through multiple pages of portfolio items.



## Installation

Install my plugin

- Download or clone the repository.
- Place the plugin folder in the wp-content/plugins directory.
- Activate the plugin through the WordPress admin panel.

## Limitations and Scope for Improvement

This plugin was built quickly to meet an immediate requirement, and while it functions as intended, it’s not optimized with best practices in mind. Here are some areas where improvements could be made:

- Object-Oriented Structure: The plugin could be refactored using Object-Oriented Programming (OOP) to improve modularity and readability.
- Design Patterns: Implementing design patterns, such as the Singleton Pattern, could manage single instances where needed, - making the code more efficient.
- Autoloader Setup: A Composer-based autoloader would streamline the inclusion of files and dependencies, enhancing performance and organization.
- Documentation: Detailed internal documentation could be added to help other developers understand and extend the code.

## Disclaimer

This plugin was developed for a specific project and may not work for general use without modification. It requires setup within ACF for specific custom fields and a "portfolio" post type. Users may need to adjust these configurations based on their own setup.