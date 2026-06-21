import './bootstrap';

// Custom Website JS
console.log('Website Frontend Loaded');

// Smooth Scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if(target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Simple Scroll Animation (Fade In Up)
const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('opacity-100', 'translate-y-0');
            entry.target.classList.remove('opacity-0', 'translate-y-10');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', () => {
    // Select elements to animate
    const animateElements = document.querySelectorAll('section > div > div, .grid > div');
    
    animateElements.forEach((el) => {
        // Add initial classes for animation
        el.classList.add('transition', 'duration-700', 'ease-out', 'opacity-0', 'translate-y-10');
        observer.observe(el);
    });
});
