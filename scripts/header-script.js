const burger = document.querySelector('.burger');
const mobileNav = document.querySelector('.mobile-nav');
const overlay = document.querySelector('.mobile-nav-overlay');
const closeBtn = document.querySelector('.mobile-close');

burger.addEventListener('click', () => {
    mobileNav.classList.add('open');
    overlay.classList.add('open');
});

closeBtn.addEventListener('click', closeMobile);
overlay.addEventListener('click', closeMobile);

function closeMobile() {
    mobileNav.classList.remove('open');
    overlay.classList.remove('open');
}

// accordion catégories
document.querySelectorAll('.mobile-category-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.parentElement.classList.toggle('open');
    });
});
