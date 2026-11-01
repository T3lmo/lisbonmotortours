const slides = document.querySelectorAll('.tour-slider .slide');
const prevBtn = document.querySelector('.tour-slider .prev');
const nextBtn = document.querySelector('.tour-slider .next');

let index = 0;

if (slides.length > 0) {
    slides[0].classList.add('active');

    const showSlide = (i) => {
        slides.forEach(s => s.classList.remove('active'));
        slides[i].classList.add('active');
    };

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            index = (index + 1) % slides.length;
            showSlide(index);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            index = (index - 1 + slides.length) % slides.length;
            showSlide(index);
        });
    }

    // Auto play (opcional, mantém)
    setInterval(() => {
        index = (index + 1) % slides.length;
        showSlide(index);
    }, 4000);
}
