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
  - No local By-Laws/statutes file was found in the workspace; on 2026-05-15 the remaining `By-Laws` `#` target was approved to stay as designed in the new website for launch.
  - `Relocate to Cyprus` has no direct live target at `/relocate-to-cyprus/`, `/relocate/`, or `/why-cyprus/`; on 2026-05-15 its `#` target was approved to stay as designed in the new website for launch.
  - Migration switches were rechecked and remain off: maintenance, redirects, and news routing.
  - Added a fourth off-by-default migration switch, `member_routing`, to support canonical `/members/[slug]/` URLs for the `memberships` CPT during cutover.
  - Live route audit found `/memberships/[slug]/` member URLs currently resolve, but `/members/[slug]/` redirects to the homepage before member routing is enabled.
  - Production has 23 published `memberships` items, not 22 as the original planning note expected.
  - Created `docs/cutover-runbook-20260515.md` with exact page/template/menu IDs, routing controls, news/member URL maps, cutover order, QA checks, and rollback steps.
  - Rebuilt `deploy/cygma-migration-tools.zip` with plugin version 0.2.0 and updated the plugin on staging and production; the new `member_routing` control is visible and all four switches remain unchecked on both sites.
  - Rebuilt `deploy/cygma-migration-mu-plugins-20260515.tar.gz` with `cygma-member-routing.php` included for cutover-only MU deployment if needed.
  - Post-update production route check confirmed public behavior remains unchanged while switches are off: `/` 200, `/memberships/gdcy/` 200, `/members/gdcy/` still 301s to `/`, `/news/[slug]/` still 301s to the root post, and the root post remains 200.
  - Cutover approval stage prepared in `docs/cutover-approval-packet-20260515.md`; no By-Laws/statutes source file was found locally or in production media search.
  - Draft page audit found 12 stale relative `/2026/04/` CSS asset references in `members-new` 8761; uploaded missing outline SVGs to production media IDs 8925-8928 and patched the draft CSS to resolving `/2026/05/` URLs.
  - Elementor `Clear Files & Data` was run after the members CSS asset patch.
  - Recheck confirms the 10 members CSS asset URLs resolve with 200, `members-new` has zero remaining `/2026/04/` references, all 9 draft previews return 200, and rendered draft previews show no broken images, staging references, old upload refs, or desktop horizontal overflow.
  - User approval recorded on 2026-05-15: leave the remaining `#` links as designed in the new website; these are no longer cutover blockers.
- Production cutover executed under maintenance mode:
  - Maintenance was enabled first and verified as HTTP 503 with `X-Robots-Tag: noindex, nofollow, noarchive` for logged-out visitors.
  - Rollback draft snapshots were created before overwriting live page data: Members 8929, News 8930, Privacy Policy 8931, Cookie Policy 8932, and Code of Conduct 8933.
  - New home page 8765 was published and set as `page_on_front`; WordPress settings are now `show_on_front=page`, `page_on_front=8765`, and `page_for_posts=18`.
  - New target pages were published: About 8760 at `/about/`, Apply 8759 at `/membership/apply/`, and Membership Policy 8758 at `/membership-policy/`.
  - Existing public URLs were updated in place with approved new Elementor data: Members 14, News 18, Privacy Policy 8269, Cookie Policy 8273, and Code of Conduct 8401.
  - Elementor templates were published and conditioned: header 8778 `include/general`, footer 8776 `include/general`, news archive 8775 `include/archive/post_archive`, news tag archive 8772 `include/archive/post_tag`, and member detail 8769 `include/singular/memberships` plus `include/singular/memberships_by_author`.
  - Old Elementor footer 7757 and old membership template 8087 had their display conditions cleared; existing post single template 7479 remains active for `include/singular/post`.
  - Header menu item 6508 now points to Home 8765, item 6507 now points to About 8760, and item 8617 now points to Apply 8759 at `/membership/apply/`.
  - Redirect, news routing, and member routing switches are enabled; maintenance is disabled.
  - Elementor `Clear Files & Data` was run after template/routing activation.
  - Public route QA confirms 200 responses for `/`, `/about/`, `/members/`, `/membership/`, `/membership/apply/`, `/news/`, `/membership-policy/`, `/privacy-policy/`, `/cookie-policy/`, `/code-of-conduct/`, `/contact/`, `/members/gdcy/`, and `/news/cygma-registers-to-represent-the-game-development-industry-in-cyprus/`.
  - Public redirect QA confirms `/about-us/`, `/become-a-member/`, `/membershippolicy/`, `/contacts/`, `/blog/`, `/blog-list/`, `/blog-grid/`, root-level news posts, and `/memberships/gdcy/` redirect to the expected new targets; `/how-to-apply/` still chains through `/become-a-member/` before reaching `/membership/apply/` due to an existing legacy redirect outside the custom map.
  - `/cyprus/` returns 410 with noindex headers as planned.
  - Public content scans for key routes found no staging-domain, noindex, or maintenance markers.
  - A redirect-priority hardening patch was added to the custom redirect code and deployed to production with a rebuilt `deploy/cygma-migration-tools.zip`; the plugin switches remained `maintenance=false`, `redirects=true`, `news_routing=true`, and `member_routing=true` after deployment.

## Current Production State

- The new CYGMA design is live on production.
- Maintenance mode is off.
- Redirect map, news routing, and member routing are on.
- WordPress settings remain `show_on_front=page`, `page_on_front=8765`, and `page_for_posts=18`.
- Staging remains separate and should stay noindex/nofollow.
- Rollback snapshot pages 8929-8933 are draft-only and should remain unpublished unless rollback is required.

## Post-Cutover Monitoring Pass

- Full news route sweep passed for all 9 published posts: `/news/[slug]/` returns 200 and each old root-level post URL returns 301 to the canonical news URL.
- Full member route sweep passed for all 23 published `memberships` items: `/members/[slug]/` returns 200 and each old `/memberships/[slug]/` URL returns 301 to the canonical member URL.
- Core redirect/410 sweep passed for `/about-us/`, `/become-a-member/`, `/membershippolicy/`, `/contacts/`, `/blog/`, `/blog-list/`, `/blog-grid/`, `/cyprus/`, `/team/`, `/events/`, `/faqs/`, `/elementor-page-6064/`, and `/7330-2/`.
- Production headers for `/`, `/about/`, `/members/`, `/membership/apply/`, `/news/`, and `/privacy-policy/` return 200 with no `X-Robots-Tag` noindex header.
- Production HTML markers on `/`, `/about/`, `/members/`, `/membership/apply/`, and `/news/` show Rank Math `index, follow` robots meta and Google Tag Manager `GTM-KWM977PP`; no staging-domain or maintenance markers were found.
- `robots.txt`, `sitemap_index.xml`, `page-sitemap.xml`, and `post-sitemap.xml` return 200.
- Staging remains noindex/nofollow in public HTML: `<meta name='robots' content='noindex, nofollow' />`.
- Contact page still exposes a contact-form marker in public HTML.
- Browser/MCP viewport resizing remained locked to a 1371px viewport, so true mobile layout QA still needs a real mobile device or a browser session with working viewport emulation.

## Current Post-Cutover Notes

- Remaining `#` targets for `Relocate to Cyprus` and `By-Laws` are intentional and approved as designed for launch.
- `/how-to-apply/` reaches `/membership/apply/` through a two-step 301 chain (`/how-to-apply/` -> `/become-a-member/` -> `/membership/apply/`). This is non-critical but can be cleaned up later in the existing redirect plugin/server redirect configuration.
- `/news/` includes existing production post featured-image derivatives under `/2026/04/`; these assets return 200 and are not missing staging migration assets.
- Continue post-cutover visual QA on real desktop/mobile devices, especially home motion/spacing, About leadership/committee copy, member detail pages, and legal copy.

## Next Recommended Actions

1. Monitor production for 404s, redirect chains, form submissions, and analytics events during the first live hours.
2. Run manual mobile QA on real devices and fix any visual regressions found.
3. Keep rollback snapshot pages 8929-8933 until the post-cutover monitoring window is complete.
4. Later cleanup: remove or override the legacy `/how-to-apply/` -> `/become-a-member/` redirect so it goes directly to `/membership/apply/`.
