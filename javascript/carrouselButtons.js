document.addEventListener('DOMContentLoaded', () => {
    const carouselContainers = document.querySelectorAll('.carousel-container');

    carouselContainers.forEach(container => {
        const carouselTrack = container.querySelector('.carousel-track');
        const slides = Array.from(carouselTrack.children);
        const prevButton = container.querySelector('.carousel-button.prev');
        const nextButton = container.querySelector('.carousel-button.next');
        let currentSlideIndex = 0;

        if (slides.length <= 1) {
            if (prevButton) prevButton.style.display = 'none';
            if (nextButton) nextButton.style.display = 'none';
            return;
        }
        const moveToSlide = (index) => {
            currentSlideIndex = index;
            const offset = -slides[currentSlideIndex].offsetLeft;
            carouselTrack.style.transform = `translateX(${offset}px)`;

            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === currentSlideIndex);
            });
        };

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                let nextIndex = (currentSlideIndex + 1) % slides.length;
                moveToSlide(nextIndex);
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                let prevIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
                moveToSlide(prevIndex);
            });
        }

        moveToSlide(0);
    });
});
