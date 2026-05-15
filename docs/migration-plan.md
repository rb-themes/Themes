# CYGMA Website Migration Plan

## Goal

Carefully migrate the new CYGMA design from `https://cygma.bonafideshops.com` to the production site at `https://cygma.eu`, while protecting the current production site, preserving SEO, and implementing news posts in the new design structure.

## Verified Current State

- GitHub is connected: `https://github.com/rb-themes/Themes.git`
- Local branch `main` tracks `origin/main`
- Initial theme snapshot commit: `e26ed04`
- Local backups exist from 2026-05-14, including files archives and database dumps
- Repository tracks custom theme code and excludes WordPress core, uploads, backups, logs, and secrets
- Production backup options show active theme `hello-elementor`, stylesheet `hello-elementor`, and permalink structure `/%postname%/`
- Production public REST API currently exposes 9 real posts at root-level URLs
- Staging public REST API currently exposes the new page set, but its news posts appear to be placeholder/copy content and should not overwrite production posts blindly
- Public header checks on 2026-05-15 showed both staging and production returning HTTP 200; maintenance/noindex was not active

## Source Materials To Collect

- Notion guide: CYGMA Website GUIDE at `https://www.notion.so/CYGMA-Website-GUIDE-360980cb859a803ebed0c9ae9f54fd2a`
- Terms and policies: `CYGMA/Docs/`
- Brandbook: `CYGMA/Figma/CYGMA_project.fig`
- Redirect map: `CYGMA/CSV/Website Redirect map - Core Pages.csv`
- Staging site: `https://cygma.bonafideshops.com`
- Production site: `https://cygma.eu`

Local source files already found outside git:

- `CYGMA/Docs/CYGMA_Privacy_Policy_23042026.docx`
- `CYGMA/Docs/CYGMA_Membership_terms_23042026.docx`
- `CYGMA/Docs/CYGMA-cookie-policy.docx`
- `CYGMA/Docs/CYGMA-code-of-conduct.docx`
- `CYGMA/Figma/CYGMA_project.fig`
- `CYGMA/CSV/Website Redirect map - Core Pages.csv`

Do not commit credentials, database dumps, exported uploads, or private access documents to git.

## Target Architecture From Notion

- `/` - Home
- `/about/` - About us
- `/members/` - Members directory
- `/members/[studio-slug]/` - Individual member page
- `/membership/` - Why join
- `/membership/apply/` - How to apply
- `/news/` - News index
- `/news/[article-slug]/` - Individual article
- `/contact/` - Contact
- `/privacy-policy/` - Privacy policy
- `/cookie-policy/` - Cookie policy
- `/code-of-conduct/` - Code of Conduct
- `/membership-policy/` - Membership Rules

Slug rules: lowercase only, hyphens between words, no dates, no file extensions, no article IDs, and keep slugs under about 60 characters where possible.

## Redirect Map Summary

The local core redirect CSV currently contains:

- 8 URLs to keep with HTTP 200
- 7 URLs to redirect with HTTP 301
- 6 placeholder or junk URLs to remove with HTTP 410

Important examples:

- `/about-us/` -> `/about/`
- `/become-a-member/` and `/how-to-apply/` -> `/membership/apply/`
- `/membershippolicy/` -> `/membership-policy/`
- `/contacts/` -> `/contact/`
- `/blog/` and `/blog-list/` -> `/news/`
- `/cyprus/`, `/team/`, `/events/`, `/faqs/`, `/elementor-page-6064/`, and `/7330-2/` -> 410

## Recommended Migration Strategy

### 1. Freeze And Inventory

- Confirm who can edit content during migration and set an editing freeze window.
- Export a fresh production database and files backup immediately before cutover.
- Export a fresh staging database and files backup before pulling final design changes.
- Record WordPress version, PHP version, active theme, active child theme, plugin versions, menus, widgets, forms, custom post types, taxonomies, and Elementor settings on both sites.
- Confirm whether the staging design is built with theme templates, Elementor templates, database content, plugin settings, or a mix.

### 2. Content And Design Mapping

- Map every current production page to its new staging equivalent.
- Reconcile page slugs against `Website Redirect map - Core Pages.csv`.
- Confirm which pages must keep old URLs and which pages require 301 redirects.
- Import Terms and Policies from `CYGMA/Docs/` into the matching new-design page templates.
- Apply the CYGMA brandbook from `CYGMA/Figma/` to typography, colors, spacing, imagery, buttons, and content hierarchy.

### 3. News Posts Implementation

- Use the Notion-defined news structure: Elementor Theme Builder single post template applied to posts.
- Map fields as follows: News image = Featured Image, News title = Post Title, News subtitle = Post Excerpt, News date = Post Info, News body = Post Content.
- Required views: `/news/` index, `/news/[article-slug]/` single post, search result appearance, empty state, and related/latest news block on key pages if present in the design.
- Move all root-level article URLs to `/news/[slug]/` according to the redirect map.
- Preserve SEO fields: SEO title, SEO description, canonical URL, Open Graph image, and sitemap inclusion.
- Test with at least three representative posts before production cutover.

Implementation started in git:

- `wp-content/mu-plugins/cygma-news-routing.php` makes native posts canonical at `/news/[slug]/`.
- Old root-level post URLs redirect to `/news/[slug]/` with HTTP 301.
- The plugin updates post permalinks and Rank Math canonical URLs.
- The plugin adds a rewrite rule and flushes rewrite rules once using a version marker.

Important: visual design for news remains an Elementor Theme Builder/database task. The routing plugin handles URL structure and SEO canonical behavior, but it does not replace the Elementor single post template design.

### 3a. Members Implementation

- Keep the Custom Post Type UI setup for Memberships, but plan the user-facing name as Members after migration.
- Member directory URL: `/members/`.
- Single member URL: `/members/[studio-slug]/`.
- Existing member item URLs under `/memberships/[slug]/` need 301 redirects to `/members/[slug]/`.
- Elementor Membership Template fields from Notion: Post Title for member name, Featured Image for logo, Post Terms for membership type, Post Content for description, and ACF fields for website, year founded, and number of employees.
- ACF fields: `year_founded`, `number_of_employees`, `website`.

### 3b. Critical Plugin Dependencies

- Complianz - Terms & Conditions
- Complianz - GDPR/CCPA Cookie Consent
- Elementor
- Elementor Pro
- Essential Addons For Elementor
- Make Connector
- Rank Math SEO
- SendPulse Email Marketing
- Site Kit by Google
- Open Graphite
- Bit Integrations
- Custom Post Type UI
- Advanced Custom Fields

### 4. Maintenance And Indexing Control

Best solution for cutover is a small MU-plugin guard plus a marker file. It is dependency-free, cannot be disabled by plugin updates, returns HTTP 503 during maintenance, and sends `X-Robots-Tag: noindex, nofollow, noarchive` headers.

The guard is versioned at `wp-content/mu-plugins/cygma-maintenance.php` and is inactive until enabled.

Enable maintenance mode on a server:

```bash
touch wp-content/maintenance.flag
```

Disable maintenance mode:

```bash
rm -f wp-content/maintenance.flag
```

Additional indexing protection:

- On staging, enable WordPress `Discourage search engines from indexing this site`.
- Password-protect staging if possible.
- During production cutover, keep maintenance enabled until final QA passes.
- Do not rely on `robots.txt` alone; blocked pages can still remain indexed without a noindex response.

### 4a. Redirects And Removed Pages

Implementation started in git:

- `wp-content/mu-plugins/cygma-redirects.php` implements core 301 redirects and 410 removals from the redirect map.
- 410 responses include `X-Robots-Tag: noindex, nofollow, noarchive`.
- Added `/blog-grid/` -> `/news/` because production currently exposes that old news page publicly.

Deploy this plugin only when the destination pages exist, especially `/about/`, `/membership/apply/`, and `/membership-policy/`.

### 5. Build And Staging QA

- Pull the latest staging design files and identify all database-backed design dependencies.
- Apply required theme and child-theme changes in git.
- Import or recreate page templates, Elementor templates, menus, forms, and global styles in a controlled staging copy.
- Confirm responsive layouts on desktop, tablet, and mobile.
- Confirm accessibility basics: keyboard focus, labels, contrast, skip links, alt text, and heading order.
- Confirm Rank Math/SEO metadata, canonical URLs, Open Graph images, XML sitemap, and schema output.
- Confirm Google Site Kit keeps GA4 `G-335ERSRPPM` and GTM `GTM-KWM977PP` configured after migration.
- Confirm LinkedIn Insights Partner ID `10127105` remains installed through Google Tag Manager.
- Confirm SendPulse form shortcode `[sendpulse-form id="8531"]` renders where required.
- Confirm redirects from the CSV against the staging URL set.

### 6. Production Cutover

- Create fresh production backup.
- Put production into maintenance mode with the MU-plugin flag.
- Disable caches and security features that could interfere with migration.
- Deploy versioned MU-plugin files:
  - `wp-content/mu-plugins/cygma-maintenance.php`
  - `wp-content/mu-plugins/cygma-news-routing.php`
  - `wp-content/mu-plugins/cygma-redirects.php`
- Confirm active theme before deploying theme code. Current production backup shows `hello-elementor`, so `hitboox` or `hitboox-child` changes will not affect production unless the active theme changes.
- Import required database content and settings from staging, avoiding blind overwrite of production-only records unless already approved.
- Run domain search-replace only for approved staging-to-production URLs.
- Flush permalinks and all caches.
- Run the QA checklist below.
- Disable maintenance mode only after critical pages, news posts, forms, redirects, and SEO checks pass.

### 7. Final QA Checklist

- Homepage loads with new design.
- Core pages load with correct design and content.
- Terms and policy pages match Notion source documents.
- News archive and individual news posts use the new design structure.
- Menus, footer, forms, buttons, and CTAs work.
- Redirect map returns expected 301 responses.
- No staging domain remains in page HTML, media URLs, templates, menus, or metadata.
- Production pages are indexable after maintenance is disabled.
- Staging remains noindex/password-protected.
- Git status is clean and changes are pushed.

### 8. Rollback Plan

- Keep maintenance enabled if critical QA fails.
- Restore production database from the fresh pre-cutover backup.
- Restore production files from the fresh pre-cutover backup.
- Flush cache and verify the old production design returns.
- Document the blocker in git before attempting the next cutover.

## Immediate Next Steps

1. Log into staging and export Elementor Theme Builder templates for Home, About, Members, Membership, Apply, News archive, News single, Contact, and legal pages.
2. Log into production and export a fresh full backup immediately before any live change.
3. Create or import missing production destination pages: `/about/`, `/membership/apply/`, and `/membership-policy/`.
4. Build/import and test the Elementor news post templates using the 9 real production posts.
5. Deploy MU-plugins to production, enable maintenance mode, import approved templates/content, run QA, then disable maintenance mode.