# Wahaj · وَهَج

**Every quote is a glow of light.** Wahaj is a bilingual (Arabic / English) quote-sharing platform built with Laravel. Anyone can read the quotes people share; registered users can share their own, edit or delete them, and *light* (like) the quotes that moved them.

> وَهَج: اتّقاد النار وتوهّجها — وكل اقتباس هنا وَهَجٌ من نور.

**Live demo:** https://wahaj.alwaysdata.net

Final project for the Al-Qimmah Bootcamp · Programming & Development Track (Tuwaiq Academy × Umm Al-Qura University), 2026. The project started from the provided *QuoteIt* starter and was rebuilt with its own identity.

## Features

- Browse every quote as a guest, with the author, the date, and how many times it was lit.
- Sign up, log in, and log out with validated forms and helpful field hints.
- Share a quote (3–155 characters) from a dialog with a live preview and a character counter.
- Edit and delete your own quotes only; other users can just read them.
- **New feature — Light a quote:** like or unlike any quote once, with a live counter and no page reload.
- Sort by *Newest* or *Most lit*, with 9 quotes per page.
- Full Arabic (right-to-left) and English interface, switchable from the header.
- Responsive design with animated sky lanterns.

## Tech stack

- Laravel 13 · PHP 8.4 · SQLite
- Blade template inheritance (`@extends`, `@yield`, `@section`) and Blade components (`x-navbar`, `x-brand`, `x-quote-dialog`, `x-brand-lantern`)
- Tailwind CSS 4 · Vite · plain JavaScript
- Pest feature tests

## Database

Three tables: `users`, `quotes`, and the `likes` pivot table (many-to-many between users and quotes). A user has many quotes; deleting a user deletes their quotes and likes, and deleting a quote deletes its likes. A unique `(user_id, quote_id)` pair stops double likes.
rtisan view:cache
   ```

