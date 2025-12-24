/**
 * PirateHash Theme Navigation
 *
 * @package PirateHash
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * Video Modal
     */
    const playBtn = document.getElementById('play-video-btn');
    const videoModal = document.getElementById('video-modal');
    const modalClose = document.getElementById('modal-close');
    const modalVideo = document.getElementById('modal-video');
    const heroVideo = document.getElementById('hero-video');
    const videoOverlay = document.getElementById('video-overlay');
    const videoClickOverlay = document.getElementById('video-click-overlay');

    if (playBtn && videoModal) {
        playBtn.addEventListener('click', function() {
            videoModal.classList.add('active');
            if (modalVideo) {
                modalVideo.play();
            }
            if (heroVideo) {
                heroVideo.pause();
            }
        });
    }

    if (modalClose && videoModal) {
        modalClose.addEventListener('click', function() {
            closeVideoModal();
        });
    }

    if (videoModal) {
        videoModal.addEventListener('click', function(e) {
            if (e.target === videoModal) {
                closeVideoModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && videoModal.classList.contains('active')) {
                closeVideoModal();
            }
        });
    }

    function closeVideoModal() {
        videoModal.classList.remove('active');
        if (modalVideo) {
            modalVideo.pause();
            modalVideo.currentTime = 0;
        }
        if (heroVideo) {
            heroVideo.play();
        }
    }

    /**
     * Mobile Menu Toggle
     */
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            navMenu.classList.toggle('active');
            
            // Animate hamburger icon
            this.classList.toggle('is-active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuToggle.contains(event.target) && !navMenu.contains(event.target)) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navMenu.classList.remove('active');
                menuToggle.classList.remove('is-active');
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                menuToggle.setAttribute('aria-expanded', 'false');
                navMenu.classList.remove('active');
                menuToggle.classList.remove('is-active');
            }
        });
    }

    /**
     * Header scroll behavior
     */
    const header = document.querySelector('.site-header');
    let lastScroll = 0;

    if (header) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            lastScroll = currentScroll;
        }, { passive: true });
    }

    /**
     * Smooth scroll for anchor links
     */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const target = document.querySelector(targetId);
            
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    /**
     * Animation on scroll - Intersection Observer
     */
    const animateElements = document.querySelectorAll('.animate-on-scroll');

    if (animateElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        animateElements.forEach(el => observer.observe(el));
    }

    /**
     * Focus visible polyfill for better keyboard navigation
     */
    document.body.addEventListener('mousedown', function() {
        document.body.classList.add('using-mouse');
    });

    document.body.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            document.body.classList.remove('using-mouse');
        }
    });

})();

