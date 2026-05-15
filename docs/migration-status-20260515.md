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

## Production Draft Pages Created

Target-path drafts created without changing public production navigation or redirects:

| Source staging slug | Production draft ID | Production slug | Status | Notes |
| --- | ---: | --- | --- | --- |
| `about-us` | 8760 | `about` | Draft | Preview renders in production; contains staging placeholder sections that need editorial QA. |
| `become-a-member` | 8759 | `apply` | Draft | Parent page is production Membership page, so final path is intended as `/membership/apply/`. |
| `membership-policy` | 8758 | `membership-policy` | Draft | Ready for legal/content review before replacing old `membershippolicy`. |

Review-only drafts created with `-new` slugs to avoid changing live URLs:

| Source staging slug | Production draft ID | Production slug | Status | Notes |
| --- | ---: | --- | --- | --- |
| `our-members` | 8761 | `members-new` | Draft | Use to review new members directory design before replacing `/members/`. |
| `news` | 8762 | `news-new` | Draft | Staging page has no meaningful Elementor layout data; news archive still needs template work. |
| `code-of-conduct` | 8763 | `code-of-conduct-new` | Draft | Review against Notion/DOCX source before replacing live legal page. |
| `cookie-policy` | 8764 | `cookie-policy-new` | Draft | Review against Notion/DOCX source before replacing live legal page. |
| `home` | 8765 | `home-new` | Draft | Contains 44 staging upload URL references; do not publish before media cleanup. |
| `privacy-policy` | 8766 | `privacy-policy-new` | Draft | Review against Notion/DOCX source before replacing live legal page. |

## Validation Notes

- Production `/about/` draft preview renders and exposes the Elementor edit link.
- Public production remains unchanged: homepage returns 200 and `/about-us/` still returns 200.
- Redirects and `/news/[slug]/` routing remain disabled until target pages are published and QA passes.

## Current Blockers Before Cutover

- Staging home design includes media references to `cygma.bonafideshops.com/wp-content/uploads`; these must be replaced with production media URLs or imported media before publishing the new homepage.
- Staging About page includes placeholder labels/copy such as `Example` and repeated placeholder headings; editorial cleanup is needed before publishing.
- Staging News page has almost no Elementor layout data, so the final news archive/single post design still needs Elementor Theme Builder work.
- Legal pages must be checked against `CYGMA/Docs/` before replacing production pages.

## Next Recommended Actions

1. Review production draft previews in wp-admin while logged in.
2. Clean staging placeholder content or edit the production drafts directly in Elementor.
3. Import or replace homepage media assets so no staging upload URLs remain.
4. Build the Elementor Theme Builder templates for news archive and single posts using the 9 real production posts.
5. After content QA, publish target pages, enable maintenance mode, enable redirects/news routing, run final checks, then disable maintenance mode.