/* Global interactive enhancements for the Learning Hub UI */

const Site = (() => {
    const heroIntervalMs = 6000;
    let heroTimer;

    const init = () => {
        initNavToggle();
        initHeroSlider();
        initCourseCarousels();
        initTestimonialSlider();
        initRevealAnimations();
    };

    const initNavToggle = () => {
        const toggle = document.querySelector('.nav-toggle');
        const nav = document.querySelector('.main-nav');

        if (!toggle || !nav) {
            return;
        }

        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            nav.classList.toggle('nav-open');
        });

        document.addEventListener('click', (event) => {
            if (!nav.contains(event.target) && !toggle.contains(event.target)) {
                nav.classList.remove('nav-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    const initHeroSlider = () => {
        const slider = document.querySelector('.hero-slider');
        if (!slider) return;

        const slides = slider.querySelectorAll('.hero-slide');
        const prevBtn = slider.querySelector('.slider-arrow.prev');
        const nextBtn = slider.querySelector('.slider-arrow.next');
        const dots = slider.querySelectorAll('.slider-dot');
        let currentIndex = 0;

        const goToSlide = (index) => {
            slides.forEach((slide, slideIndex) => {
                const active = slideIndex === index;
                slide.classList.toggle('active', active);
            });
            dots.forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === index));
            currentIndex = index;
        };

        const nextSlide = () => goToSlide((currentIndex + 1) % slides.length);
        const prevSlide = () => goToSlide((currentIndex - 1 + slides.length) % slides.length);

        prevBtn?.addEventListener('click', () => {
            prevSlide();
            resetHeroTimer();
        });
        nextBtn?.addEventListener('click', () => {
            nextSlide();
            resetHeroTimer();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                goToSlide(index);
                resetHeroTimer();
            });
        });

        const playHero = () => {
            heroTimer = setInterval(nextSlide, heroIntervalMs);
        };

        const pauseHero = () => {
            clearInterval(heroTimer);
        };

        const resetHeroTimer = () => {
            pauseHero();
            playHero();
        };

        slider.addEventListener('mouseenter', pauseHero);
        slider.addEventListener('mouseleave', playHero);

        playHero();
    };

    const initCourseCarousels = () => {
        document.querySelectorAll('.course-carousel').forEach((carousel) => {
            const track = carousel.querySelector('.carousel-track');
            const prevBtn = carousel.querySelector('.carousel-arrow.prev');
            const nextBtn = carousel.querySelector('.carousel-arrow.next');
            const cards = track.querySelectorAll('.course-card');
            if (!track || cards.length === 0) return;

            const scrollStep = () => Math.min(track.clientWidth * 0.9, cards[0].clientWidth * 3.2);

            prevBtn?.addEventListener('click', () => {
                track.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
            });
            nextBtn?.addEventListener('click', () => {
                track.scrollBy({ left: scrollStep(), behavior: 'smooth' });
            });

            let isDragging = false;
            let startX = 0;
            let scrollStart = 0;

            track.addEventListener('pointerdown', (event) => {
                isDragging = true;
                startX = event.clientX;
                scrollStart = track.scrollLeft;
                track.setPointerCapture(event.pointerId);
                track.classList.add('dragging');
            });

            track.addEventListener('pointermove', (event) => {
                if (!isDragging) return;
                const delta = startX - event.clientX;
                track.scrollLeft = scrollStart + delta;
            });

            const stopDrag = () => {
                isDragging = false;
                track.classList.remove('dragging');
            };

            track.addEventListener('pointerup', stopDrag);
            track.addEventListener('pointerleave', stopDrag);

            track.addEventListener('wheel', (event) => {
                event.preventDefault();
                track.scrollBy({ left: event.deltaY < 0 ? -120 : 120 });
            });
        });
    };

    const initTestimonialSlider = () => {
        const slider = document.querySelector('.testimonial-slider');
        if (!slider) return;

        const track = slider.querySelector('.testimonial-track');
        const cards = slider.querySelectorAll('.testimonial-card');
        const prevBtn = slider.querySelector('.testimonial-arrow.prev');
        const nextBtn = slider.querySelector('.testimonial-arrow.next');
        let currentIndex = 0;
        let timer;

        const updateSlider = () => {
            const offset = currentIndex * track.clientWidth;
            track.style.transform = `translateX(-${offset}px)`;
        };

        const nextTestimonial = () => {
            currentIndex = (currentIndex + 1) % cards.length;
            updateSlider();
        };

        prevBtn?.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + cards.length) % cards.length;
            updateSlider();
            resetTimer();
        });
        nextBtn?.addEventListener('click', () => {
            nextTestimonial();
            resetTimer();
        });

        const play = () => {
            timer = setInterval(nextTestimonial, 7000);
        };
        const pause = () => clearInterval(timer);
        const resetTimer = () => {
            pause();
            play();
        };

        slider.addEventListener('mouseenter', pause);
        slider.addEventListener('mouseleave', play);

        if (cards.length > 0) {
            updateSlider();
            play();
            window.addEventListener('resize', updateSlider);
        }
    };

    const initRevealAnimations = () => {
        const options = { threshold: 0.15 };
        const observer = new IntersectionObserver((entries, observerRef) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                    observerRef.unobserve(entry.target);
                }
            });
        }, options);

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach((element) => {
            observer.observe(element);
        });
    };

    return { init };
})();

window.addEventListener('DOMContentLoaded', Site.init);
