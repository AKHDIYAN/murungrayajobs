/**
 * animations.js - Smooth animations and transitions
 * Portal Kerja Murung Raya
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // Scroll Animation - Fade In on Scroll
    // ========================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all elements with data-animate attribute
    document.querySelectorAll('[data-animate]').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });

    // ========================================
    // Smooth Scroll to Anchor
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                const target = document.querySelector(href);
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ========================================
    // Add Hover Effect to Cards
    // ========================================
    const cards = document.querySelectorAll('.job-card, .company-card, .stats-card');
    cards.forEach(card => {
        if (!card.classList.contains('card-hover')) {
            card.classList.add('card-hover-subtle');
        }
    });

    // ========================================
    // Loading Animation for Forms
    // ========================================
    const forms = document.querySelectorAll('form[data-loading]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
            }
        });
    });

    // ========================================
    // Staggered Animation for Lists
    // ========================================
    function staggerAnimation(selector, delayIncrement = 100) {
        const elements = document.querySelectorAll(selector);
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                el.style.transition = 'all 0.5s ease-out';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * delayIncrement);
        });
    }

    // Apply staggered animation to job listings
    if (document.querySelector('.job-listings')) {
        staggerAnimation('.job-listings .job-card', 80);
    }

    // ========================================
    // Number Counter Animation
    // ========================================
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString('id-ID');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, 16);
    }

    // Animate counters on stats cards
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.getAttribute('data-counter'));
        if (!isNaN(target)) {
            const observerCounter = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(el, target);
                        observerCounter.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            
            observerCounter.observe(el);
        }
    });

    // ========================================
    // Dropdown Animation
    // ========================================
    document.querySelectorAll('[data-dropdown-toggle]').forEach(toggle => {
        const targetId = toggle.getAttribute('data-dropdown-toggle');
        const target = document.getElementById(targetId);
        
        if (target) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                target.classList.toggle('hidden');
                
                if (!target.classList.contains('hidden')) {
                    target.style.opacity = '0';
                    target.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        target.style.transition = 'all 0.3s ease-out';
                        target.style.opacity = '1';
                        target.style.transform = 'translateY(0)';
                    }, 10);
                }
            });
        }
    });

    // ========================================
    // Notification Toast Animation
    // ========================================
    function showToast(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `fixed top-20 right-4 z-50 px-6 py-4 rounded-lg shadow-2xl transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        } text-white font-semibold`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(full)';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    // Expose toast function globally
    window.showToast = showToast;

    // ========================================
    // Page Load Animation
    // ========================================
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease-in';
        document.body.style.opacity = '1';
    }, 10);

    // ========================================
    // Button Ripple Effect
    // ========================================
    document.querySelectorAll('button, .btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.className = 'ripple-effect';
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add ripple CSS
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(rippleStyle);

    console.log('✨ Animations initialized successfully!');
});
