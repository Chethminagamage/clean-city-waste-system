import './bootstrap';
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';
import '../css/app.css'; // Ensure Tailwind + AOS styles are applied

// Set up Alpine globally
window.Alpine = Alpine;
Alpine.start();

// Initialize AOS on page load
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-in-out',
        offset: 80,
    });
});