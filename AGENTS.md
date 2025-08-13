# AGENTS.md

Official best practices guide for maintaining the **ai-wp-seo-check** WordPress plugin in an organized, secure, performant, and easy-to-evolve way.

> **Purpose**: Standardize how we contribute, test, release, and maintain the plugin. This document must be read before opening PRs, creating branches, or publishing releases.

---

## 1) Folder Structure

```
ai-wp-seo-check/
├── ai-wp-seo-check.php       # Main bootstrap (header, hooks, autoload)
├── readme.txt                # WordPress.org standard
├── uninstall.php             # Clean data removal
├── src/                      # PHP source code (PSR-4 via Composer)
│   ├── Admin/                 # Admin screens and Settings API
│   ├── Api/                   # REST endpoints
│   ├── Domain/                # Business logic
│   ├── Infrastructure/        # WP adapters, repositories, services
│   └── Support/               # Helpers, traits, value objects
├── assets/
│   ├── css/                   # Compiled CSS (do not edit manually)
│   ├── js/                    # Compiled JS (do not edit manually)
│   └── images/
├── resources/
│   ├── css/                   # Source CSS (PostCSS/Sass)
│   ├── js/                    # JS/TS source (build with @wordpress/scripts or Vite)
│   └── views/                 # PHP templates (escaped output)
├── languages/                 # .po/.mo/.pot (i18n)
├── bin/                       # Maintenance/CI scripts
├── tests/                     # PHPUnit and integration tests
├── vendor/                    # Composer dependencies
└── composer.json
```

**Rules**
- Production code only in `src/` (organized by domain).
- No logic in templates; templates output should already be escaped.
- Build artifacts must not be edited manually.

---

## 2) Coding Standards

- **PHP**: WordPress Coding Standards (WPCS) + PSR-12 where not conflicting.
- **JS**: Modern ECMAScript, built with `@wordpress/scripts` or Vite.
- **CSS**: BEM or similar, avoid global CSS.
- Linters: `phpcs` (WPCS), `eslint`, `stylelint` required via pre-commit.

---

## 3) Security Checklist

- Use **nonces** for all mutable actions.
- Escape output (`esc_html`, `esc_attr`, `esc_url`).
- Sanitize input (`sanitize_text_field`, `absint`, `sanitize_email`).
- Use capability checks (`current_user_can`).
- SQL: always prepare queries.
- REST: validate `permission_callback` and `args`.
- File uploads: validate MIME/type/size.
- Never commit secrets.

---

## 4) Performance

- Conditional asset loading.
- Use late hooks for heavy initialization.
- Cache expensive data with transients/object cache.
- Avoid N+1 queries.
- Offload long-running tasks to async jobs.

---

## 5) Internationalization

- Use `__()`, `_e()`, `_x()`, etc. with the **Text Domain** `ai-wp-seo-check`.
- Generate `.pot` with `wp i18n make-pot`.
- Code/comments in English.

---

## 6) Dependencies

- Composer for PHP, NPM for JS/CSS.
- Avoid duplicate library loads.

---

## 7) Versioning & Changelog

- **SemVer**: `MAJOR.MINOR.PATCH`.
- Update `Version` in plugin header and `readme.txt`.
- Keep `CHANGELOG.md` updated.

---

## 8) Git Workflow

- Default branch: `main`.
- Branch naming: `feat/`, `fix/`, `docs/`.
- PRs must include description, screenshots, and linked issues.

---

## 9) Testing

- PHPUnit with WordPress test suite.
- Jest/Vitest for JS utilities.
- Minimum coverage: 70%.

---

## 10) CI/CD

- CI: run linters and tests on every PR.
- Release: tag, changelog, zip build without dev files.

---

## 11) Assets & Build

- Use consistent handles for enqueued scripts.
- Compile assets to `assets/`.

---

## 12) Hooks

- Document all public hooks.
- Prefix with `ai_wp_seo_check/`.

---

## 13) Settings & Admin UI

- Use the Settings API.
- Accessibility compliance required.

---

## 14) Uninstall

- Clean only expected data in `uninstall.php`.

---

## 15) Privacy

- Document collected data and comply with GDPR/LGPD.

---

## 16) Compatibility

- Minimum: WordPress 6.3, PHP 8.1.
- Test against the latest 2 minors.

---

## 17) Documentation

- Keep `README.md` and `readme.txt` updated.
- Maintain `docs/` for guides, FAQs, and public hooks.

---

## 18) Deprecation

- Mark with `@deprecated` and provide alternatives.
- Maintain back-compat for at least 1 minor version.

---

## 19) Release Process

1. Update changelog and version.
2. Run all tests and linters.
3. Build assets.
4. Package `.zip` without dev files.
5. Create tag and publish.

---

## 20) Naming Conventions

- Slug: `ai-wp-seo-check`.
- Namespace: `\AiWpSeoCheck`.
- Constants: `AI_WP_SEO_CHECK_VERSION`.
- Option prefixes: `ai_wp_seo_check_`.
- Asset handles: `ai-wp-seo-check-admin`.

---

## 21) Recommended Tools

- PHPCS + WPCS
- PHPStan/Psalm
- @wordpress/scripts
- WP-CLI
- Action Scheduler

---

End of document.
