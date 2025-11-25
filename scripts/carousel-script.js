const carousel = document.querySelector('.carousel');
const slides = carousel.querySelectorAll('.carousel-item');
const nextBtn = carousel.querySelector('.carousel-control.next');
const prevBtn = carousel.querySelector('.carousel-control.prev');
const bulletsContainer = carousel.querySelector('.carousel-bullets');
const progressBar = carousel.querySelector('.carousel-progress-bar');

let currentIndex = 0;
let autoSlideInterval;
let progressInterval;

// Appliquer les images en background via data-bg
slides.forEach(slide => {
  const bgUrl = slide.getAttribute('data-bg');
  if (bgUrl) {
    slide.style.backgroundImage = `url('${bgUrl}')`;
  }
});

// Créer les bullets
slides.forEach((slide, i) => {
  const bullet = document.createElement('span');
  if (i === 0) bullet.classList.add('active');
  bullet.addEventListener('click', () => {
    goToSlide(i);
    resetAutoSlide();
  });
  bulletsContainer.appendChild(bullet);
});

const bullets = bulletsContainer.querySelectorAll('span');

function updateCarousel() {
  const offset = -currentIndex * 100;
  carousel.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
  
  // Mettre à jour les bullets
  bullets.forEach((b, i) => b.classList.toggle('active', i === currentIndex));
  
  // Mettre à jour la classe active sur les slides
  slides.forEach((s, i) => {
    s.classList.toggle('active', i === currentIndex);
  });
  
  // Réinitialiser et démarrer la barre de progression
  resetProgressBar();
}

function nextSlide() {
  currentIndex = (currentIndex + 1) % slides.length;
  updateCarousel();
}

function prevSlide() {
  currentIndex = (currentIndex - 1 + slides.length) % slides.length;
  updateCarousel();
}

function goToSlide(index) {
  currentIndex = index;
  updateCarousel();
}

// Gestion de la barre de progression
function resetProgressBar() {
  progressBar.style.width = '0%';
  progressBar.classList.remove('animating');
  
  // Forcer un reflow pour redémarrer l'animation
  void progressBar.offsetWidth;
  
  progressBar.classList.add('animating');
}

// Gestion de l'auto-slide
function startAutoSlide() {
  autoSlideInterval = setInterval(nextSlide, 5000);
  resetProgressBar();
}

function resetAutoSlide() {
  clearInterval(autoSlideInterval);
  startAutoSlide();
}

// Event listeners
nextBtn.addEventListener('click', () => {
  nextSlide();
  resetAutoSlide();
});

prevBtn.addEventListener('click', () => {
  prevSlide();
  resetAutoSlide();
});

// Pause au survol
carousel.addEventListener('mouseenter', () => {
  clearInterval(autoSlideInterval);
  progressBar.classList.remove('animating');
});

carousel.addEventListener('mouseleave', () => {
  startAutoSlide();
});

// Support du clavier
document.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') {
    prevSlide();
    resetAutoSlide();
  } else if (e.key === 'ArrowRight') {
    nextSlide();
    resetAutoSlide();
  }
});

// Support du swipe tactile
let touchStartX = 0;
let touchEndX = 0;

carousel.addEventListener('touchstart', (e) => {
  touchStartX = e.changedTouches[0].screenX;
});

carousel.addEventListener('touchend', (e) => {
  touchEndX = e.changedTouches[0].screenX;
  handleSwipe();
});

function handleSwipe() {
  if (touchEndX < touchStartX - 50) {
    nextSlide();
    resetAutoSlide();
  }
  if (touchEndX > touchStartX + 50) {
    prevSlide();
    resetAutoSlide();
  }
}

// Démarrer l'auto-slide au chargement
startAutoSlide();