document.addEventListener('DOMContentLoaded', () => {
    const carouselContainers = document.querySelectorAll('.carousel-container');

    carouselContainers.forEach(container => {
        const carouselTrack = container.querySelector('.carousel-track');
        let slides = Array.from(carouselTrack.children); // Get initial slides
        const prevButton = container.querySelector('.carousel-button.prev');
        const nextButton = container.querySelector('.carousel-button.next');

        if (slides.length <= 1) {
            if (prevButton) prevButton.style.display = 'none';
            if (nextButton) nextButton.style.display = 'none';
            return;
        }

        const firstSlideClone = slides[0].cloneNode(true);
        const lastSlideClone = slides[slides.length - 1].cloneNode(true);

        carouselTrack.appendChild(firstSlideClone);
        carouselTrack.insertBefore(lastSlideClone, slides[0]);

        slides = Array.from(carouselTrack.children);

        let currentSlideIndex = 1;
        carouselTrack.style.transition = 'none';
        carouselTrack.style.transform = `translateX(-${slides[currentSlideIndex].offsetLeft}px)`;

        setTimeout(() => {
            carouselTrack.style.transition = 'transform 0.5s ease-in-out';
        }, 50);

        const moveToSlide = (index) => {
            currentSlideIndex = index;
            const offset = -slides[currentSlideIndex].offsetLeft;
            carouselTrack.style.transform = `translateX(${offset}px)`;

            slides.forEach((slide, i) => {

                slide.classList.toggle('active', i === currentSlideIndex && i !== 0 && i !== slides.length - 1);
            });
        };

        carouselTrack.addEventListener('transitionend', () => {
            if (currentSlideIndex === slides.length - 1) {
                carouselTrack.style.transition = 'none';
                currentSlideIndex = 1;
                carouselTrack.style.transform = `translateX(-${slides[currentSlideIndex].offsetLeft}px)`;

                setTimeout(() => {
                    carouselTrack.style.transition = 'transform 0.5s ease-in-out';
                }, 50);
            }
            if (currentSlideIndex === 0) {
                carouselTrack.style.transition = 'none';
                currentSlideIndex = slides.length - 2;
                carouselTrack.style.transform = `translateX(-${slides[currentSlideIndex].offsetLeft}px)`;

                setTimeout(() => {
                    carouselTrack.style.transition = 'transform 0.5s ease-in-out';
                }, 50);
            }
        });

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                if (currentSlideIndex < slides.length - 1) {
                    moveToSlide(currentSlideIndex + 1);
                }
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                if (currentSlideIndex > 0) {
                    moveToSlide(currentSlideIndex - 1);
                }
            });
        }
        moveToSlide(1);
    });
});
