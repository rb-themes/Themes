# CYGMA Cutover Approval Packet - 2026-05-15

This packet summarizes what still needs human approval before the production cutover can begin. It also records the automated checks completed after the cutover runbook and member-routing controls were prepared.

## Decision Items

### Relocate to Cyprus CTA

Current state:

- Draft page: `home-new` page 8765.
- Visible CTA text: `Relocate to Cyprus`.
- Current draft target: `#`.
- Existing production page `/cyprus/` returns 200, but the redirect map currently marks `/cyprus/` as 410 Gone.
- `/relocate-to-cyprus/`, `/relocate/`, and `/why-cyprus/` are not live target pages.

Approval options:

- Use `/cyprus/` only if the 410 rule is removed from both migration redirect implementations and the page is editorially approved.
- Use another approved live URL such as `/contact/` or `/membership/` as a temporary destination.
- Keep the CTA hidden or remove it for cutover until a dedicated relocation page exists.

Recommendation: do not enable the redirect map until this conflict is resolved.

### By-Laws CTA

Current state:

- Draft page: `about` page 8760.
- Visible CTA text: `By-Laws`.
- Current draft target: `#`.
- Local workspace search found no By-Laws/statutes/constitution/memorandum source file.
- Production WordPress media search found no matching By-Laws/statutes document.

Approval options:

- Provide the final By-Laws PDF/DOCX and approve uploading it to WordPress media.
- Provide a final URL if the document is hosted elsewhere.
- Hide or remove the CTA for cutover if the document is not ready.

Recommendation: do not publish the About page until this CTA is resolved or intentionally removed.

## Automated QA Completed

- Searched local project files for By-Laws/statutes/legal source candidates; no relevant file found.
- Searched production WordPress media/pages/posts for By-Laws/statutes/legal source candidates; no matching media document found.
- Re-audited draft Elementor data for pages 8758-8766.
- Confirmed remaining visible draft `#` placeholders are limited to `Relocate to Cyprus` on home and `By-Laws` on about; other `#` links in preview output are from admin/cookie tooling.
- Confirmed no staging-domain references in the audited draft page data.
- Confirmed no `/2026/04/` references remain in rendered draft previews.
- Confirmed all 9 draft page previews return 200 with no broken images and no desktop horizontal overflow in the current browser viewport.
- Confirmed production public routes remain unchanged while all migration switches are off.

## Members Page Asset Fix

The automated audit found 12 stale `/2026/04/` asset references inside custom CSS on draft page 8761, `members-new`. These references were relative CSS URLs, so they were not caught by earlier full-URL image audits.

Fix applied:

- Downloaded 4 missing outline SVGs from staging into `deploy/member-outline-assets-20260515/`.
- Uploaded the outline SVGs to production media IDs 8925-8928.
- Patched draft page 8761 custom CSS from stale `/2026/04/` references to resolving production `/2026/05/` media URLs.
- Re-ran Elementor Tools -> Clear Files & Data.

Validated member CSS assets:

- `membership-symbol-01-outline-2.svg` -> 200.
- `membership-symbol-02-outline-2.svg` -> 200.
- `membership-symbol-03-outline-2.svg` -> 200.
- `membership-symbol-04-outline-2.svg` -> 200.
- `membership-symbol-01.svg` -> 200.
- `membership-symbol-02.svg` -> 200.
- `membership-symbol-03.svg` -> 200.
- `membership-symbol-04.svg` -> 200.
- `left-arrow.svg` -> 200.
- `right-arrow.svg` -> 200.

## Live Route Baseline After This Stage

- `/` -> 200.
- `/about-us/` -> 200.
- `/members/` -> 200.
- `/membership/` -> 200.
- `/become-a-member/` -> 200.
- `/news/` -> 200.
- `/membership/apply/` -> 404 before cutover.
- `/memberships/gdcy/` -> 200 before cutover.
- `/members/gdcy/` -> 301 to `/` before cutover.

These results are expected before publishing draft pages or enabling migration routing switches.

## Approval Gate

Cutover can proceed after these approvals are recorded:

1. Final home/about/members/news/legal visual and editorial approval.
2. Final `Relocate to Cyprus` CTA decision.
3. Final `By-Laws` CTA document/URL/removal decision.
4. Explicit approval to start the controlled cutover window.