# CYGMA Migration Status - 2026-05-15

## Completed This Stage

- Confirmed WordPress admin access for staging and production.
- Installed and activated `CYGMA Migration Tools` on both sites.
- Left all migration switches disabled on both sites:
  - Maintenance mode: off
  - Redirect map: off
  - News URLs: off
- Confirmed production and staging public URLs still return normal HTTP 200 responses after plugin activation.
- Used authenticated WordPress REST calls inside wp-admin sessions to copy selected staging page content and Elementor meta into production draft pages.
- Enabled WordPress `Discourage search engines from indexing this site` on staging only.
- Verified staging public HTML now includes `<meta name='robots' content='noindex, nofollow' />`.
- Imported staging homepage media into the production media library and patched the `home-new` draft to use production media URLs.
- Imported key staging Elementor templates into production as draft templates with live conditions removed.
- Cleaned safe draft-copy issues in production drafts without publishing them:
  - Replaced `/become-a-member/` CTA links in `home-new` with `/membership/apply/`.
  - Fixed `Mission & Piorities` to `Mission & Priorities` in stored `home-new` draft data.
  - Replaced the incorrect Hitboox placeholder paragraph in `home-new` and `apply` with CYGMA-specific copy.
  - Replaced visible `Example` section labels in migrated page drafts with contextual labels.

## Production Draft Pages Created

Target-path drafts created without changing public production navigation or redirects:

| Source staging slug | Production draft ID | Production slug | Status | Notes |
| --- | ---: | --- | --- | --- |
| `about-us` | 8760 | `about` | Draft | Placeholder `Example` section labels replaced; still needs editorial QA. |
| `become-a-member` | 8759 | `apply` | Draft | Parent page is production Membership page, so final path is intended as `/membership/apply/`. |
| `membership-policy` | 8758 | `membership-policy` | Draft | Ready for legal/content review before replacing old `membershippolicy`. |

Review-only drafts created with `-new` slugs to avoid changing live URLs:

| Source staging slug | Production draft ID | Production slug | Status | Notes |
| --- | ---: | --- | --- | --- |
| `our-members` | 8761 | `members-new` | Draft | Use to review new members directory design before replacing `/members/`. |
| `news` | 8762 | `news-new` | Draft | Staging page has no meaningful Elementor layout data; news archive still needs template work. |
| `code-of-conduct` | 8763 | `code-of-conduct-new` | Draft | Review against Notion/DOCX source before replacing live legal page. |
| `cookie-policy` | 8764 | `cookie-policy-new` | Draft | Review against Notion/DOCX source before replacing live legal page. |
| `home` | 8765 | `home-new` | Draft | Staging upload URLs, old membership CTA route, Hitboox placeholder copy, and obvious section-label placeholders cleared in stored draft data. |
| `privacy-policy` | 8766 | `privacy-policy-new` | Draft | Review against Notion/DOCX source before replacing live legal page. |

## Production Media Imported

| Production media ID | File | Production URL |
| ---: | --- | --- |
| 8779 | `about-us-img-01-scaled-1.jpg` | `https://cygma.eu/wp-content/uploads/2026/05/about-us-img-01-scaled-1.jpg` |
| 8780 | `slider-image-01.jpg` | `https://cygma.eu/wp-content/uploads/2026/05/slider-image-01.jpg` |
| 8781 | `slider-image-02-e1775328520906.webp` | `https://cygma.eu/wp-content/uploads/2026/05/slider-image-02-e1775328520906.webp` |
| 8782 | `logo.svg` | `https://cygma.eu/wp-content/uploads/2026/05/logo.svg` |

The `home-new` draft now has zero staging upload references and 44 production upload references.

## Production Elementor Template Drafts Created

All templates below were imported as drafts and had original Elementor display conditions removed, so they do not affect live production yet.

| Staging template | Production draft ID | Production slug | Type |
| --- | ---: | --- | --- |
| `header` | 8778 | `cygma-new-header` | Header |
| `footer` | 8776 | `cygma-new-footer` | Footer |
| `news-archive` | 8775 | `cygma-new-news-archive` | Archive |
| `news-tag-page-archive` | 8772 | `cygma-new-news-tag-page-archive` | Archive |
| `news-featured-item` | 8774 | `cygma-new-news-featured-item` | Loop item |
| `elementor-loop-item-1568` | 8773 | `cygma-new-elementor-loop-item-1568` | Loop item |
| `recent-news-cards-loop-template` | 8771 | `cygma-new-recent-news-cards-loop-template` | Loop item |
| `news-cards-for-homepage` | 8777 | `cygma-new-news-cards-for-homepage` | Loop item |
| `member-page-detail-template` | 8769 | `cygma-new-member-page-detail-template` | Single post |
| `members-card-loop-template` | 8770 | `cygma-new-members-card-loop-template` | Loop item |
| `members-detailed-page-no-image-template` | 8767 | `cygma-new-members-detailed-page-no-image-template` | Page template |
| `members-detailed-page-with-image-template` | 8768 | `cygma-new-members-detailed-page-with-image-template` | Page template |

## Validation Notes

- Production `/about/` draft preview renders and exposes the Elementor edit link.
- Production `home-new` draft preview renders and exposes the Elementor edit link.
- Public production remains unchanged: homepage returns 200 and `/about-us/` still returns 200.
- Redirects and `/news/[slug]/` routing remain disabled until target pages are published and QA passes.
- Staging is now noindex/nofollow through WordPress Reading Settings.
- Migrated production drafts and draft Elementor templates have zero remaining `cygma.bonafideshops.com` references after domain cleanup.
- Stored migrated page draft data now has zero remaining `Example`, `Piorities`, `Hitboox`, and `/become-a-member/` matches in the audited production drafts after safe cleanup.
- Elementor `Clear Files & Data` was run successfully from production wp-admin after draft cleanup.
- Regenerated draft preview HTML for `home-new`, `apply`, and `about` no longer shows `Example`, `Piorities`, or Hitboox placeholder copy.
- Remaining `/become-a-member/` matches in regenerated preview HTML come from the global menu/header item for `How to apply`, not from the migrated page draft bodies.
- Migration switches were rechecked after cache regeneration and remain off: maintenance, redirect map, and news routing.
- News archive draft templates were patched to reference production loop-template draft IDs instead of staging IDs:
  - `cygma-new-news-archive` ID 8775: `1531` -> `8774`, `1568` -> `8773`.
  - `cygma-new-news-tag-page-archive` ID 8772: `1568` -> `8773`.
- Elementor `Clear Files & Data` was run again after the news template draft ID patch.
- Production currently has 9 published posts intended for news migration; all 9 have featured images. One draft post, `Elementor #8082`, has no slug and should be excluded from news QA.
- With news routing still off, root post URLs return 200, `/news/[slug]/` currently redirects back to the root post URL, and `/news/` remains the current production news page.
- News archive draft query cleanup was applied after confirming production category IDs `17` and `18` return 404:
  - `cygma-new-news-archive` ID 8775 now has no invalid term filters; the featured loop uses `8774` and is limited to 1 latest post, while the main loop uses `8773` with pagination.
  - `cygma-new-news-tag-page-archive` ID 8772 now uses `8773`, no empty term filter, and `current_query` for tag archive context.
- Elementor `Clear Files & Data` was run again after the news archive query cleanup.
- Draft link cleanup was applied to obvious internal CTAs only:
  - `Become a Member` draft-page links now point to `/membership/apply/` in `home-new`, `about`, `apply`, and `members-new`.
  - `View All Members` on `home-new` now points to `/members/`.
  - Re-audit confirms zero `/become-a-member/` links remain in migrated draft page bodies.
- Elementor `Clear Files & Data` was run again after the draft link cleanup.
- Draft asset QA found 96 missing production upload URLs across migrated page bodies, mostly staging April design assets.
- Imported 95 public staging assets into the production media library, plus 2 Roman Zanin assets whose source filenames contain a hidden Unicode separator.
- Patched migrated draft page bodies from missing `/2026/04/` asset URLs to the new production `/2026/05/` media URLs.
- Elementor `Clear Files & Data` was run again after the draft asset import and URL patch.
- Re-audit confirms 112 of 112 migrated draft page-body asset URLs now resolve, with zero missing assets and zero remaining `/2026/04/` asset references.
- Draft preview QA confirmed all migrated draft pages return 200: `home-new` 8765, `about` 8760, `members-new` 8761, `apply` 8759, `news-new` 8762, `membership-policy` 8758, `code-of-conduct-new` 8763, `cookie-policy-new` 8764, and `privacy-policy-new` 8766.
- The production draft previews were missing staging's global horizontal overflow guard. Added draft-only Elementor page CSS to `home-new` and `apply`: `html, body { overflow-x: hidden; }`.
- Rechecked `home-new` and `apply` previews after the CSS patch; both now have no horizontal overflow at the current desktop QA viewport.
- Replaced the remaining placeholder sentence `Some tempting sentence here incentivising to join` in `apply`, `about`, and `members-new` with neutral CYGMA membership copy.
- Imported Elementor template previews return 200 with no missing images or horizontal overflow in the current desktop QA viewport: news archive 8775, news tag archive 8772, featured news loop 8774, news card loop 8773, homepage news loop 8777, header 8778, and footer 8776.
- Elementor `Clear Files & Data` was run again after visual QA draft patches.
- Draft CTA link cleanup was extended after preview QA found Elementor buttons with no explicit URL:
  - `home-new`: hero/industry/about `Learn More` buttons and `Meet the Team` now point to `/about-us/`; `Check the Benefits` points to `/membership/apply/`; `View All Members` points to `/members/`; `View Older News` points to `/news/`.
  - `about`: `Learn More` points to `/about-us/`; bottom `Become a Member` points to `/membership/apply/`.
  - `members-new`: `Become a Member` points to `/membership/apply/`.
  - `apply`: all `Become a Member` buttons point to `/membership/apply/`; `Contact Us` points to `/contact/` after confirming `/contact/` returns 200 and `/contact-us/` returns 404.
- Legal draft pages were compared against local source documents in `CYGMA/Docs/`; imported draft bodies were incomplete, so the legal body areas were replaced from the source `.docx` text while keeping the pages draft-only:
  - `membership-policy` 8758 from `CYGMA_Membership_terms_23042026.docx`.
  - `code-of-conduct-new` 8763 from `CYGMA-code-of-conduct.docx`.
  - `cookie-policy-new` 8764 from `CYGMA-cookie-policy.docx`.
  - `privacy-policy-new` 8766 from `CYGMA_Privacy_Policy_23042026.docx`.
- Legal draft preview recheck confirms all four legal pages return 200, have no missing images, no horizontal overflow, and source-scale word counts.
- Elementor `Clear Files & Data` was run again after the legal source import and CTA link cleanup.
- Draft header/template cleanup was applied:
  - `cygma-new-header` 8778 `Become a Member` container/button now points to `/membership/apply/` in the draft template data.
  - Published/global menu links are intentionally unchanged; the rendered `How to apply`/`Join CYGMA` menu route still points to `/become-a-member/` until the approved cutover step.
- Template asset QA found 12 missing upload URLs in imported draft templates, mainly news archive background/pagination assets and header SVGs.
- Uploaded 6 missing public staging template assets to production media IDs 8910-8915: `News-bg.webp`, `left-arrow.svg`, `right-arrow.svg`, `CYGMA-Logo-mobile.svg`, `Hamburger-closed.svg`, and `hamburger-opened.svg`.
- Patched imported draft template URLs from missing `/2026/03/` and `/2026/04/` paths to resolving production `/2026/05/` media URLs; `become-member-bg-mob.png` was mapped to the existing production `become-member-bg-mob.webp` because the `.png` source is absent on staging.
- Re-audit confirms 12 of 12 imported draft template upload URLs now resolve, with zero remaining `/2026/03/` or `/2026/04/` template asset references.
- Preview smoke test for draft news archive 8775, news tag archive 8772, footer 8776, and header 8778 returns 200 with no broken images or horizontal overflow in the current desktop QA viewport.
- Elementor `Clear Files & Data` was run again after the template asset patch.
- Cutover readiness audit was performed without changing live content:
  - Current front page is published page 5917, slug `home-1`; WordPress `show_on_front` is `page` and `page_on_front` is 5917.
  - Current posts page setting is page 2158, slug `blog-grid`, while the public `/news/` page is page 18.
  - Draft `apply` 8759 already has parent page 15, slug `membership`; publishing it should create `/membership/apply/`, which currently returns 404.
  - Current live routes return 200 for `/`, `/about-us/`, `/members/`, `/news/`, `/membership/`, `/become-a-member/`, `/privacy-policy/`, `/cookie-policy/`, `/code-of-conduct/`, `/membershippolicy/`, and `/contact/`.
  - No local By-Laws/statutes file was found in the workspace; the `By-Laws` CTA still needs a source document or final URL.
  - `Relocate to Cyprus` has no direct live target at `/relocate-to-cyprus/`, `/relocate/`, or `/why-cyprus/`; existing `/cyprus/` page 19 returns 200 and can be used if approved.
  - Migration switches were rechecked and remain off: maintenance, redirects, and news routing.
  - Added a fourth off-by-default migration switch, `member_routing`, to support canonical `/members/[slug]/` URLs for the `memberships` CPT during cutover.
  - Live route audit found `/memberships/[slug]/` member URLs currently resolve, but `/members/[slug]/` redirects to the homepage before member routing is enabled.
  - Production has 23 published `memberships` items, not 22 as the original planning note expected.
  - Created `docs/cutover-runbook-20260515.md` with exact page/template/menu IDs, routing controls, news/member URL maps, cutover order, QA checks, and rollback steps.

## Remaining Stages

1. Editorial/legal decision stage: final human review of home/about/news/legal copy, decide `Relocate to Cyprus` target, and provide/approve the By-Laws URL or file.
2. Cutover preparation stage: prepare exact publish/slug/front-page/menu/template-condition operations and rollback steps for the approved window.
3. Cutover execution stage: enable maintenance, publish/replace target pages and templates, update menus/header, set front page if needed, enable redirects/news routing, regenerate caches, and disable maintenance after checks.
4. Post-cutover QA and monitoring stage: validate live routes, redirects, news URLs, noindex/indexing state, analytics/SEO basics, forms, mobile layouts, and rollback readiness.

## Current Blockers Before Cutover

- The homepage/media URL blocker and obvious placeholder/typo blockers are cleared in stored draft data, but the homepage still needs human editorial review before publishing.
- The staging/imported About page has safe placeholder labels replaced, but repeated headings and section copy still need editorial review before publishing.
- Staging News page has almost no page-level Elementor layout data, but relevant Elementor archive and loop templates have now been imported, patched, and left as draft templates for review.
- News archive draft templates no longer contain staging source term filters or staging loop-template IDs; first-pass preview QA is clean, but final manual QA is still required before enabling news templates/routing.
- Remaining draft body `#` placeholders are intentionally left for editorial/document decision: `home-new` has `Relocate to Cyprus`; `about` has `By-Laws`.
- Legal pages now use the local `CYGMA/Docs/` source text, but a final legal/editorial read-through is still required before replacing production pages.
- Published/global navigation still contains the old `How to apply`/`Join CYGMA` route to `/become-a-member/`; update this only during the approved page publish/cutover step so the live site is not changed prematurely.

## Next Recommended Actions

1. Continue human visual QA of production draft previews in wp-admin while logged in, especially homepage motion/spacing and About leadership/committee copy.
2. Continue editorial/legal review in Elementor or source docs before publishing any migrated page, including deciding final targets for the remaining draft `#` placeholders.
3. Review imported draft Elementor templates against live production posts, then publish/apply conditions only during the approved cutover window.
4. Update the global menu/header `How to apply` route during cutover after the target membership application page is ready.
5. After content QA, publish target pages, enable maintenance mode, enable redirects/news routing, run final checks, then disable maintenance mode.
