# CLAUDE.md — JC Fitness Website

## Project Overview

Static multi-page website for JC Fitness, a fitness studio in Nieuw-Vennep, Netherlands. Built with vanilla HTML, CSS, and JavaScript — no frameworks or build tools. PHP backend handles the contact form and Instagram API integration.

**Live site**: Deployed via GitHub Pages (primary) and Netlify (secondary).

## Tech Stack

- **Frontend**: HTML5, CSS3, vanilla JavaScript
- **Backend**: PHP (contact form via `verzend.php`, Instagram API via `instagram.php`)
- **Fonts**: Google Fonts (Barlow Semi Condensed)
- **Anti-spam**: Google reCAPTCHA v3
- **External**: VirtuaGym (fitness management), Instagram Graph API
- **No npm/yarn/build tools** — files are served as-is

## Directory Structure

```
/
├── index.html          # Home page
├── over.html           # About page
├── aanbod.html         # Services page
├── rooster.html        # Schedule page
├── contact.html        # Contact form
├── bedankt.html        # Thank-you page (post form submission)
├── webshop.html        # Webshop (iframe on desktop, link on mobile)
├── style.css           # Single stylesheet (all pages)
├── reveal.js           # Scroll-reveal animations (Intersection Observer)
├── verzend.php         # Contact form handler (reCAPTCHA + email)
├── instagram.php       # Instagram API endpoint (with 15-min caching)
├── robots.txt          # SEO robots config
├── sitemap.xml         # Sitemap with priority values
├── .htaccess           # Apache rules (blocks cache file access)
├── images/             # All static images (jpg, webp, png)
├── .github/workflows/
│   └── pages.yml       # GitHub Actions — deploy to GitHub Pages on push to main
└── INSTAGRAM-SETUP.md  # Instagram API setup guide (in Dutch)
```

## Development Workflow

### No build step required

This is a static site. Edit HTML/CSS/JS files directly — no compilation or bundling needed.

### Deployment

- **Push to `main`** triggers GitHub Actions (`pages.yml`) which deploys to GitHub Pages.
- Netlify is configured as a secondary deployment target.

### Testing

- No automated test suite. Changes are verified by manual browser testing.
- Check responsive behavior at 768px (tablet) and 480px (mobile) breakpoints.

## Coding Conventions

### HTML

- All page content is in Dutch.
- Semantic HTML: `<header>`, `<nav>`, `<section>`, `<footer>`.
- ARIA labels on interactive elements (buttons, navigation).
- `loading="lazy"` on images below the fold.
- JSON-LD structured data (LocalBusiness) on `index.html`.
- Open Graph meta tags on all pages.

### CSS (`style.css`)

- **CSS custom properties** for theming (dark theme by default):
  - `--bg: #0f0f0f`, `--bg-alt: #1a1a1a`, `--bg-card: #1e1e1e`
  - `--text: #f5f5f5`, `--text-light: #e0e0e0`, `--text-muted: #aaa`
  - `--accent: #003399`
  - `--radius: 12px`
- **BEM-like utility classes**: `.btn`, `.btn-outline`, `.section`, `.section-alt`, `.section-gradient`
- **Grid system**: `.grid-2`, `.grid-3`, `.grid-4` using CSS Grid, stacking to 1 column on mobile.
- **Card components**: `.service-card`, `.testimonial-card`, `.trainer-card`, `.info-card`
- **Responsive breakpoints**: `@media (max-width: 768px)` and `@media (max-width: 480px)`.

### JavaScript

- Vanilla JS only — no libraries or frameworks.
- `reveal.js` uses `IntersectionObserver` for scroll-triggered `.visible` class toggling.
- Inline `<script>` blocks in HTML for page-specific behavior (hero slideshow, FAQ accordion, mobile menu toggle, Instagram feed).
- WhatsApp widget is a fixed-position link (bottom-right, z-index 99).

### PHP

- `verzend.php`: Validates name/email, verifies reCAPTCHA, sends email via `mail()`, redirects to `bedankt.html` or `contact.html?status=error`.
- `instagram.php`: Fetches Instagram posts via Graph API, caches results for 15 minutes in `instagram-cache.json`, returns JSON.

## Environment Variables / Placeholders

These placeholders must be replaced in production:

| File | Placeholder | Purpose |
|------|-------------|---------|
| `contact.html` | `HIER_JE_SITE_KEY` | Google reCAPTCHA v3 site key |
| `verzend.php` | `HIER_JE_SECRET_KEY` | Google reCAPTCHA v3 secret key |
| `instagram.php` | `INSTAGRAM_ACCESS_TOKEN_HIER` | Instagram API token (TransIP) |
| Netlify env | `INSTAGRAM_ACCESS_TOKEN` | Instagram API token (Netlify) |

## Key Features

- **Hero slideshow**: Cycles background images every 4 seconds with fade transitions.
- **Instagram feed**: Dual fallback — API-driven feed or 3 hardcoded embed posts.
- **Contact form**: reCAPTCHA v3 protected, email delivery via PHP `mail()`.
- **Mobile menu**: Hamburger toggle with animated icon.
- **Scroll animations**: Elements with `.reveal` class fade in via Intersection Observer.
- **Parallax sections**: `background-attachment: fixed` image breaks (desktop only).

## Important Notes

- The site language is **Dutch** — all user-facing text must remain in Dutch.
- Images use **WebP** where possible for performance; fallbacks in JPG/PNG.
- The `.htaccess` file blocks direct access to `instagram-cache.json`.
- No `package.json` exists — do not introduce Node.js tooling unless explicitly requested.
