# CYGMA WordPress Redesign

This repository is initialized from the current CYGMA WordPress snapshot and is scoped for redesign and theme development work.

## What is tracked

- `wp-content/themes/hitboox`
- `wp-content/themes/hitboox-child`

## What is intentionally excluded

- WordPress core (`wp-admin`, `wp-includes`, root core files)
- Environment-specific secrets such as `wp-config.php`
- Uploads, backups, logs, and other generated content
- Third-party plugins unless they later need custom code changes

## Suggested GitHub setup

Create a private GitHub repository named `CYGMA-old-new` and push this folder as the repository root.

```bash
git remote add origin <your-github-repo-url>
git add .
git commit -m "Initial CYGMA WordPress theme snapshot"
git push -u origin main
```

If the redesign expands beyond the current themes, update `.gitignore` to include any additional custom code that should be versioned.