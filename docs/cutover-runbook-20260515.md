# CYGMA Cutover Runbook - 2026-05-15

This runbook is for the approved production cutover window from the current CYGMA design to the prepared Elementor redesign. Do not publish pages, apply Elementor conditions, or enable routing switches until the editorial/legal blockers are resolved and the cutover window is approved.

## Current Production Baseline

- Production URL: `https://cygma.eu`.
- Staging source URL: `https://cygma.bonafideshops.com`.
- Active theme: `hello-elementor`.
- Permalink structure: `/%postname%/`.
- Current WordPress reading settings: `show_on_front=page`, `page_on_front=5917`, `page_for_posts=2158`.
- Current front page: page 5917, title `HOME`, slug `home-1`, public URL `/`.
- Current posts page setting: page 2158, title `Our News`, slug `blog-grid`.
- Public `/news/` page: page 18, title `News Grid`, slug `news`.
- Migration Tools option key: `cygma_migration_tools_options`.
- Production switches must start off: `maintenance=false`, `redirects=false`, `news_routing=false`, `member_routing=false`.

## Approval Blockers

- Final human visual/editorial QA is still required for home, about, members, news, and legal pages.
- `Relocate to Cyprus` and `By-Laws` are approved to keep their `#` targets as designed in the new website for launch.
- Final legal read-through is required after the legal draft body import from local source documents.

## Draft Page Map

| Role | Draft ID | Draft slug | Target URL/action |
| --- | ---: | --- | --- |
| Home | 8765 | `home-new` | Publish and set `page_on_front` to 8765 for `/` |
| About | 8760 | `about` | Publish at `/about/`; redirect `/about-us/` |
| Members | 8761 | `members-new` | Replace or publish for `/members/` after final strategy confirmation |
| Membership application | 8759 | `apply` | Publish as child of page 15 to create `/membership/apply/` |
| News page | 8762 | `news-new` | Replace or publish for `/news/` after archive template confirmation |
| Membership Policy | 8758 | `membership-policy` | Publish at `/membership-policy/`; redirect `/membershippolicy/` |
| Code of Conduct | 8763 | `code-of-conduct-new` | Replace existing `/code-of-conduct/` content or slug during cutover |
| Cookie Policy | 8764 | `cookie-policy-new` | Replace existing `/cookie-policy/` content or slug during cutover |
| Privacy Policy | 8766 | `privacy-policy-new` | Replace existing `/privacy-policy/` content or slug during cutover |

For pages whose target URL already exists, prefer preserving the public URL and avoiding duplicate slug conflicts. The safest execution pattern is either an in-place Elementor/meta copy onto the existing page ID or a controlled slug swap while maintenance mode is enabled.

## Elementor Template Map

| Role | Draft ID | Slug | Condition/publish note |
| --- | ---: | --- | --- |
| Header | 8778 | `cygma-new-header` | Publish/apply global header condition after approval |
| Footer | 8776 | `cygma-new-footer` | Publish/apply global footer condition after approval |
| News Archive | 8775 | `cygma-new-news-archive` | Publish/apply news archive condition after post routing is ready |
| News Tag Archive | 8772 | `cygma-new-news-tag-page-archive` | Publish/apply tag archive condition after post routing is ready |
| News Featured Loop | 8774 | `cygma-new-news-featured-item` | Loop item dependency |
| News Card Loop | 8773 | `cygma-new-elementor-loop-item-1568` | Loop item dependency |
| Homepage News Loop | 8777 | `cygma-new-news-cards-for-homepage` | Loop item dependency |
| Recent News Loop | 8771 | `cygma-new-recent-news-cards-loop-template` | Loop item dependency |
| Member Detail | 8769 | `cygma-new-member-page-detail-template` | Confirm member CPT condition before publishing |
| Members Card Loop | 8770 | `cygma-new-members-card-loop-template` | Loop item dependency |
| Member Page, no image | 8767 | `cygma-new-members-detailed-page-no-image-template` | Confirm condition before publishing |
| Member Page, with image | 8768 | `cygma-new-members-detailed-page-with-image-template` | Confirm condition before publishing |

Use Elementor Theme Builder UI for final display conditions unless an authenticated REST payload is verified immediately before cutover. Existing production condition syntax uses values such as `include/general` and `include/singular/post`.

## Menu Update

- Header location: `menu-1` (`Header`) uses menu ID 2, `Top Menu`.
- Footer location: `menu-2` currently has no assigned menu.
- Menu item 8617, title `How to apply`, currently points to page 8575 at `/become-a-member/`.
- During cutover, update menu item 8617 to target `/membership/apply/` after page 8759 is published and verified.
- Current `Top Menu` order: Home 6508, About us 6507, Members 33, Membership 6779, How to apply 8617, News 8441.

## Routing Controls

The admin plugin `CYGMA Migration Tools` provides gated switches that are off by default:

- `maintenance`: returns 503 and noindex headers for public visitors while logged-in administrators can continue QA.
- `redirects`: enables core 301 redirects and 410 removals.
- `news_routing`: makes posts canonical at `/news/[slug]/` and redirects old root post URLs.
- `member_routing`: makes `memberships` CPT items canonical at `/members/[slug]/` and redirects old `/memberships/[slug]/` URLs.

The cutover-only MU plugin equivalents are versioned in `wp-content/mu-plugins/`. Deploy MU plugins only during an approved cutover window because MU routing plugins execute automatically when present.

## Core Redirect Map

301 redirects implemented in the migration tools:

- `/about-us/` -> `/about/`
- `/become-a-member/` -> `/membership/apply/`
- `/how-to-apply/` -> `/membership/apply/`
- `/membershippolicy/` -> `/membership-policy/`
- `/contacts/` -> `/contact/`
- `/blog/` -> `/news/`
- `/blog-list/` -> `/news/`
- `/blog-grid/` -> `/news/` (extra implementation guard; not present in the source CSV)

410 removals implemented in the migration tools:

- `/cyprus/`
- `/team/`
- `/events/`
- `/faqs/`
- `/elementor-page-6064/`
- `/7330-2/`

Because `Relocate to Cyprus` is approved to keep its `#` target for launch, the `/cyprus/` 410 rule can remain unchanged unless a new approved CTA target is provided later.

## News URL Map

There are 9 published posts. When `news_routing` is enabled, each old root-level post URL should 301 to `/news/[slug]/`:

- `/cyprus-gamedev-track-at-gdcy-fest-2026/` -> `/news/cyprus-gamedev-track-at-gdcy-fest-2026/`
- `/cygma-opens-the-first-dedicated-teens-track-at-gdcy-fest-2026/` -> `/news/cygma-opens-the-first-dedicated-teens-track-at-gdcy-fest-2026/`
- `/cyprus-game-development-ecosystem-gains-recognition-through-xsolla-partnership/` -> `/news/cyprus-game-development-ecosystem-gains-recognition-through-xsolla-partnership/`
- `/cygma-names-mellow-contractor-operations-partner-to-support-game-studios-expanding-in-cyprus/` -> `/news/cygma-names-mellow-contractor-operations-partner-to-support-game-studios-expanding-in-cyprus/`
- `/cygma-names-unlimit-strategic-global-fintech-partner-for-member-studios/` -> `/news/cygma-names-unlimit-strategic-global-fintech-partner-for-member-studios/`
- `/ask-me-anything-industry-briefing-roundtable/` -> `/news/ask-me-anything-industry-briefing-roundtable/`
- `/cygma-featured-in-gamesbeat-cyprus-enters-the-european-industry-conversation/` -> `/news/cygma-featured-in-gamesbeat-cyprus-enters-the-european-industry-conversation/`
- `/andrey-ivashentsev-appointed-general-manager-of-cygma/` -> `/news/andrey-ivashentsev-appointed-general-manager-of-cygma/`
- `/cygma-registers-to-represent-the-game-development-industry-in-cyprus/` -> `/news/cygma-registers-to-represent-the-game-development-industry-in-cyprus/`

## Member URL Map

There are 23 published `memberships` items. When `member_routing` is enabled, each old `/memberships/[slug]/` URL should 301 to `/members/[slug]/`:

- `/memberships/brickworks-games-ltd/` -> `/members/brickworks-games-ltd/`
- `/memberships/chillbase-ltd/` -> `/members/chillbase-ltd/`
- `/memberships/eschatology-entertaiment/` -> `/members/eschatology-entertaiment/`
- `/memberships/gaijin-entertainment/` -> `/members/gaijin-entertainment/`
- `/memberships/gdcy/` -> `/members/gdcy/`
- `/memberships/helio-games/` -> `/members/helio-games/`
- `/memberships/horns-up-games-limited/` -> `/members/horns-up-games-limited/`
- `/memberships/kidit-cyprus/` -> `/members/kidit-cyprus/`
- `/memberships/kosmos-games-cyprus-ltd/` -> `/members/kosmos-games-cyprus-ltd/`
- `/memberships/made-on-earth-games-ltd/` -> `/members/made-on-earth-games-ltd/`
- `/memberships/mika-games/` -> `/members/mika-games/`
- `/memberships/abe-entertainment-limited/` -> `/members/abe-entertainment-limited/`
- `/memberships/my-games/` -> `/members/my-games/`
- `/memberships/mtag-publishing-ltd/` -> `/members/mtag-publishing-ltd/`
- `/memberships/novabits-ltd/` -> `/members/novabits-ltd/`
- `/memberships/owlcat-games/` -> `/members/owlcat-games/`
- `/memberships/ducky-ltd/` -> `/members/ducky-ltd/`
- `/memberships/playkot-ltd/` -> `/members/playkot-ltd/`
- `/memberships/redhill-games-cyprus-ltd/` -> `/members/redhill-games-cyprus-ltd/`
- `/memberships/robolab-private-institute/` -> `/members/robolab-private-institute/`
- `/memberships/studiofortytwo-ltd/` -> `/members/studiofortytwo-ltd/`
- `/memberships/wargaming/` -> `/members/wargaming/`
- `/memberships/zillion-whales/` -> `/members/zillion-whales/`

Pre-cutover check showed `/members/[slug]/` redirects to the homepage while `/memberships/[slug]/` works. Do not redirect member URLs until `member_routing` is deployed, enabled, flushed, and verified.

## Cutover Sequence

1. Create a fresh production database backup and files backup.
2. Confirm the worktree is clean and latest migration docs/code are pushed.
3. Confirm final human QA/legal approval and explicit approval to start the cutover window.
4. Enable maintenance mode from Tools -> CYGMA Migration or by creating `wp-content/maintenance.flag`.
5. Confirm logged-out production requests return 503 with noindex headers, while logged-in admin access still works.
6. Publish or swap approved draft pages in this order: target-path pages, legal pages, members/news pages, then homepage/front-page setting.
7. Publish approved Elementor loop dependencies, then header/footer/archive/single templates with reviewed conditions.
8. Update `Top Menu` item 8617 from `/become-a-member/` to `/membership/apply/`.
9. Enable `member_routing` and flush rewrite rules; verify `/members/gdcy/` returns the GDCy member page and `/memberships/gdcy/` 301s to it.
10. Enable `news_routing` and flush rewrite rules; verify `/news/cygma-registers-to-represent-the-game-development-industry-in-cyprus/` returns the post and the old root post URL 301s to it.
11. Enable `redirects` only after target pages and the `/cyprus/` decision are final.
12. Run Elementor Tools -> Clear Files & Data.
13. Run public QA checks while maintenance remains enabled for logged-out visitors.
14. Disable maintenance only after critical checks pass.

## Post-Cutover QA

- Verify public 200 routes: `/`, `/about/`, `/members/`, `/membership/`, `/membership/apply/`, `/news/`, `/membership-policy/`, `/privacy-policy/`, `/cookie-policy/`, `/code-of-conduct/`, `/contact/`.
- Verify 301 routes: `/about-us/`, `/become-a-member/`, `/how-to-apply/`, `/membershippolicy/`, `/contacts/`, `/blog/`, `/blog-list/`, `/blog-grid/`.
- Verify 410 routes only after approval: `/cyprus/`, `/team/`, `/events/`, `/faqs/`, `/elementor-page-6064/`, `/7330-2/`.
- Verify all 9 news post routes under `/news/[slug]/`.
- Verify a sample of member routes under `/members/[slug]/` and old `/memberships/[slug]/` redirects.
- Verify production is indexable after maintenance is disabled and staging remains noindex/nofollow.
- Verify forms and tracking remain present: GA4 `G-335ERSRPPM`, GTM `GTM-KWM977PP`, LinkedIn Partner ID `10127105`, and SendPulse form `[sendpulse-form id="8531"]` where required.
- Verify no staging domain remains in public HTML, CSS, images, canonicals, Open Graph data, or menus.
- Verify mobile layout manually in a real mobile viewport because earlier browser viewport sharing was unreliable.

## Rollback

If critical QA fails, keep maintenance enabled and do not expose a mixed state.

1. Disable switches in this order: `member_routing`, `news_routing`, `redirects`; keep `maintenance` enabled.
2. Restore the fresh pre-cutover production database backup.
3. Restore the fresh pre-cutover production files backup if files/plugins/templates changed.
4. Flush all caches and rewrite rules.
5. Confirm baseline routes return to the old design: `/`, `/about-us/`, `/members/`, `/news/`, `/membership/`, `/become-a-member/`, `/privacy-policy/`, `/cookie-policy/`, `/code-of-conduct/`, `/membershippolicy/`, `/contact/`.
6. Disable maintenance only after the old design is verified.
7. Record the blocker in `docs/migration-status-20260515.md` before retrying cutover.