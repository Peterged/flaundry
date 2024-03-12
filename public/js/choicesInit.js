import './choices.min.js';

document.addEventListener('DOMContentLoaded', function() {
    new Choices('#choices-select', {
        searchEnabled: true, // Enable the search field
        searchPlaceholderValue: 'Search...', // Optional placeholder text
    });
});