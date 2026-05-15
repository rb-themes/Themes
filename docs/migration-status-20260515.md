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
- Elementor frontend preview for `home-new` may still serve a stale rendered copy until Elementor/cache data is regenerated from wp-admin; verify regenerated preview HTML before publishing.

## Current Blockers Before Cutover

- The homepage media URL blocker and obvious placeholder/typo blockers are cleared in stored draft data, but the homepage still needs human editorial review before publishing.
- The staging/imported About page has safe placeholder labels replaced, but repeated headings and section copy still need editorial review before publishing.
- Staging News page has almost no page-level Elementor layout data, but relevant Elementor archive and loop templates have now been imported as draft templates for review.
- Legal pages must be checked against `CYGMA/Docs/` before replacing production pages.
- Elementor/cache regeneration should be run from wp-admin and previews rechecked before any final publish or cutover.

## Next Recommended Actions

1. Review production draft previews in wp-admin while logged in.
2. Regenerate Elementor/cache data from wp-admin and recheck migrated draft previews for stale rendered output.
3. Continue editorial/legal review in Elementor or source docs before publishing any migrated page.
4. Review imported draft Elementor templates, then publish/apply conditions only during the approved cutover window.
5. Test the draft news archive and loop templates against the 9 real production posts.
6. After content QA, publish target pages, enable maintenance mode, enable redirects/news routing, run final checks, then disable maintenance mode.