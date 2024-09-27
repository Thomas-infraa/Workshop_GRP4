// script.js
const carouselContainer = document.querySelector('.carousel-container');
const slides = document.querySelectorAll('.carousel-slide');
const prevButton = document.querySelector('.prev');
const nextButton = document.querySelector('.next');

let currentSlide = 0;
const totalSlides = slides.length;

// Function to update the position of the carousel
function updateCarousel() {
    // Calculate the translation amount based on the current slide index
    const offset = currentSlide * -100;
    carouselContainer.style.transform = `translateX(${offset}%)`;
}

// Show the previous slide
prevButton.addEventListener('click', () => {
    currentSlide = (currentSlide === 0) ? totalSlides - 1 : currentSlide - 1;
    updateCarousel();
});

// Show the next slide
nextButton.addEventListener('click', () => {
    currentSlide = (currentSlide === totalSlides - 1) ? 0 : currentSlide + 1;
    updateCarousel();
});
