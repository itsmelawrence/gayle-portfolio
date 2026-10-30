# Gayle's Portfolio

A single-page portfolio site. `index.html` is fully self-contained (styles and scripts inline), so it can be hosted anywhere static, such as GitHub Pages.

## Structure

- `index.html`: the live site. Open it in a browser to preview.
- `src/build.py`: content for projects, services, experience, traits, and tools.
- `src/template.html`: layout, styles, and scripts.

## Editing content

Either edit `index.html` directly for small text changes, or edit `src/build.py` / `src/template.html` and regenerate:

```bash
cd src
python3 build.py      # writes src/index.html
cp index.html ../index.html
```

If you regenerate, re-apply any manual edits made directly to `index.html`.

## Before launch

1. Replace the highlighted placeholders (shown with a dashed underline): contact details, portrait, screenshots, case study challenges, and verified results only.
2. Contact form: create a free Formspree form and replace `YOUR_FORM_ID` in the form `action`. Until then the form shows a "not connected yet" notice.
3. Check the page title and meta description in the `<head>`.

## Deploying to GitHub Pages

Commit `index.html` to the repository root, then enable Pages under Settings > Pages (deploy from branch, root folder).
