// Scroll reveal animations
document.querySelectorAll('.section > .container, .section-alt > .container, .section-cta > .container, .image-break').forEach(function(el) {
    el.classList.add('reveal');
});

var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('.reveal').forEach(function(el) {
    observer.observe(el);
});
