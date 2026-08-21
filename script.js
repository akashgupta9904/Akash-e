/**
 * AKASH GAY'S SHOWCASE - INTERACTIVE JAVASCRIPT
 * Welcome Splash Screen, Particle Canvas, Themes, Affirmations, Confetti & Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
  initWelcomeOverlay();
  initParticleCanvas();
  initThemeSwitcher();
  initAffirmations();
  initConfettiTriggers();
  initNavigation();
  initBackToTop();
});

/* -------------------------------------------------------------------------- */
/* 0. Welcome Splash Screen & Automatic Confetti                              */
/* -------------------------------------------------------------------------- */
function initWelcomeOverlay() {
  const overlay = document.getElementById('welcome-overlay');
  const enterBtn = document.getElementById('enter-site-btn');

  // Trigger celebration on load
  setTimeout(() => {
    shootPrideConfetti();
  }, 400);

  if (enterBtn && overlay) {
    enterBtn.addEventListener('click', () => {
      overlay.classList.add('hidden');
      shootPrideConfetti();
    });

    // Close on click outside modal
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        overlay.classList.add('hidden');
        shootPrideConfetti();
      }
    });
  }
}

/* -------------------------------------------------------------------------- */
/* 1. Animated Particle Background Canvas                                     */
/* -------------------------------------------------------------------------- */
function initParticleCanvas() {
  const canvas = document.getElementById('bg-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  let width = (canvas.width = window.innerWidth);
  let height = (canvas.height = window.innerHeight);

  window.addEventListener('resize', () => {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
  });

  const colors = ['#ec4899', '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444'];
  const particleCount = Math.min(Math.floor(window.innerWidth / 18), 75);
  const particles = [];

  for (let i = 0; i < particleCount; i++) {
    particles.push({
      x: Math.random() * width,
      y: Math.random() * height,
      radius: Math.random() * 2.8 + 1,
      color: colors[Math.floor(Math.random() * colors.length)],
      vx: (Math.random() - 0.5) * 0.7,
      vy: (Math.random() - 0.5) * 0.7,
      alpha: Math.random() * 0.55 + 0.25
    });
  }

  function animate() {
    ctx.clearRect(0, 0, width, height);

    // Draw Particles
    particles.forEach((p, i) => {
      p.x += p.vx;
      p.y += p.vy;

      if (p.x < 0) p.x = width;
      if (p.x > width) p.x = 0;
      if (p.y < 0) p.y = height;
      if (p.y > height) p.y = 0;

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
      ctx.fillStyle = p.color;
      ctx.globalAlpha = p.alpha;
      ctx.fill();

      // Connect near particles
      for (let j = i + 1; j < particles.length; j++) {
        const p2 = particles[j];
        const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
        if (dist < 115) {
          ctx.beginPath();
          ctx.moveTo(p.x, p.y);
          ctx.lineTo(p2.x, p2.y);
          ctx.strokeStyle = p.color;
          ctx.globalAlpha = (1 - dist / 115) * 0.16;
          ctx.lineWidth = 0.9;
          ctx.stroke();
        }
      }
    });

    ctx.globalAlpha = 1;
    requestAnimationFrame(animate);
  }

  animate();
}

/* -------------------------------------------------------------------------- */
/* 2. Pride Affirmations Generator                                           */
/* -------------------------------------------------------------------------- */
const affirmations = [
  {
    quote: "Being gay is a natural part of who I am. Living with honesty, love, and self-respect is my everyday pride.",
    author: "Akash Gay • Age 21"
  },
  {
    quote: "Never be bullied into silence. Never allow yourself to be made a victim. Accept no one's definition of your life; define yourself.",
    author: "Harvey Milk"
  },
  {
    quote: "Turn your magic on. To live authentically at 21 as a proud gay man with dignity is a triumph of freedom.",
    author: "Akash Gay • Pride Manifesto"
  },
  {
    quote: "Equality means more than passing laws. The struggle is really won in the hearts and minds of the community, where it counts.",
    author: "Barbara Gittings"
  },
  {
    quote: "Love is love, identity is truth, and confidence is the best thing you can ever wear.",
    author: "Akash Gay • Daily Inspiration"
  }
];

let currentAffirmationIdx = 0;

function initAffirmations() {
  const quoteText = document.getElementById('affirmation-text');
  const quoteAuthor = document.getElementById('affirmation-author');
  const nextBtn = document.getElementById('new-quote-btn');

  if (!nextBtn || !quoteText || !quoteAuthor) return;

  nextBtn.addEventListener('click', () => {
    quoteText.style.opacity = '0';
    quoteAuthor.style.opacity = '0';

    setTimeout(() => {
      currentAffirmationIdx = (currentAffirmationIdx + 1) % affirmations.length;
      quoteText.textContent = `"${affirmations[currentAffirmationIdx].quote}"`;
      quoteAuthor.textContent = `— ${affirmations[currentAffirmationIdx].author}`;
      quoteText.style.opacity = '1';
      quoteAuthor.style.opacity = '1';
    }, 250);
  });
}

/* -------------------------------------------------------------------------- */
/* 3. Confetti Celebrations                                                  */
/* -------------------------------------------------------------------------- */
function shootPrideConfetti() {
  if (typeof confetti !== 'function') return;

  // Pride rainbow palette
  const prideColors = ['#ef4444', '#f97316', '#eab308', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'];

  // Left burst
  confetti({
    particleCount: 55,
    angle: 60,
    spread: 75,
    origin: { x: 0, y: 0.8 },
    colors: prideColors
  });

  // Right burst
  confetti({
    particleCount: 55,
    angle: 120,
    spread: 75,
    origin: { x: 1, y: 0.8 },
    colors: prideColors
  });

  // Center star burst
  setTimeout(() => {
    confetti({
      particleCount: 65,
      spread: 100,
      origin: { y: 0.55 },
      colors: prideColors
    });
  }, 250);
}

function initConfettiTriggers() {
  const celebrateNavBtn = document.getElementById('celebrate-nav-btn');
  const confettiCannonBtn = document.getElementById('confetti-cannon-btn');

  if (celebrateNavBtn) {
    celebrateNavBtn.addEventListener('click', shootPrideConfetti);
  }

  if (confettiCannonBtn) {
    confettiCannonBtn.addEventListener('click', shootPrideConfetti);
  }
}

/* -------------------------------------------------------------------------- */
/* 4. Dynamic Theme Switcher                                                 */
/* -------------------------------------------------------------------------- */
function initThemeSwitcher() {
  const themeBtns = document.querySelectorAll('.theme-btn');
  const htmlRoot = document.documentElement;

  themeBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      themeBtns.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');

      const targetTheme = btn.getAttribute('data-set-theme');
      htmlRoot.setAttribute('data-theme', targetTheme);

      // Trigger celebratory micro confetti
      if (typeof confetti === 'function') {
        confetti({
          particleCount: 25,
          spread: 45,
          origin: { y: 0.7 }
        });
      }
    });
  });
}

/* -------------------------------------------------------------------------- */
/* 5. Navigation & Mobile Menu                                               */
/* -------------------------------------------------------------------------- */
function initNavigation() {
  const menuToggle = document.getElementById('menu-toggle');
  const navLinks = document.getElementById('nav-links');
  const links = document.querySelectorAll('.nav-link');

  if (menuToggle && navLinks) {
    menuToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
    });

    links.forEach((link) => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('open');
      });
    });
  }

  // Active section scroll spy
  window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section');
    const scrollPos = window.scrollY + 220;

    sections.forEach((section) => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      const id = section.getAttribute('id');

      if (scrollPos >= top && scrollPos < top + height) {
        links.forEach((link) => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${id}`) {
            link.classList.add('active');
          }
        });
      }
    });
  });
}

/* -------------------------------------------------------------------------- */
/* 6. Form Submission Simulation                                             */
/* -------------------------------------------------------------------------- */
window.handleFormSubmit = function () {
  const form = document.getElementById('contact-form');
  const toast = document.getElementById('form-toast');

  if (toast) {
    toast.style.display = 'flex';
    shootPrideConfetti();

    setTimeout(() => {
      form.reset();
      setTimeout(() => {
        toast.style.display = 'none';
      }, 4000);
    }, 1000);
  }
};

/* -------------------------------------------------------------------------- */
/* 7. Back to Top Button                                                     */
/* -------------------------------------------------------------------------- */
function initBackToTop() {
  const backToTopBtn = document.getElementById('back-to-top-btn');
  if (!backToTopBtn) return;

  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}
