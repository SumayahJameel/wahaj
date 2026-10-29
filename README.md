# ✨ Wahaj · وَهَج

### Every quote is a glow of light.

Wahaj (وَهَج) is a bilingual quote-sharing platform built with Laravel, where words become little sparks of inspiration. Visitors can explore quotes shared by others, while registered users can share their own words, manage their quotes, and *light* the quotes that resonate with them.

> **وَهَج:** اتّقاد النار وتوهّجها — وكل اقتباس هنا وَهَجٌ من نور.

**Live Demo:** [wahaj.alwaysdata.net](https://wahaj.alwaysdata.net)

**Project:** Al-Qimmah Bootcamp — Programming & Development Track  
**Organized by:** Tuwaiq Academy × Umm Al-Qura University  
**Year:** 2026

Wahaj was developed as a final bootcamp project, starting from the provided QuoteIt starter and evolving into a platform with its own visual identity, bilingual experience, and quote-lighting feature.

---

## Features

- **Explore Quotes** — Browse public quotes with their authors, publication dates, and light counts, without needing an account.
- **User Authentication** — Register, log in, and log out through validated forms with helpful field hints.
- **Share Quotes** — Create quotes through a dialog featuring a live preview and character counter. Quote length is limited to 3–155 characters.
- **Manage Your Quotes** — Edit or delete your own quotes while keeping other users' content protected.
- **Light a Quote** — Like or unlike quotes with an instant counter update and no full-page reload.
- **Sort & Discover** — Browse quotes by *Newest* or *Most Lit*, with 9 quotes per page.
- **Arabic & English** — Switch between a fully localized Arabic RTL interface and an English LTR interface.
- **Responsive Design** — Enjoy a layout designed for different screen sizes, enhanced by animated sky lanterns.

## Tech Stack

| Technology | Purpose |
|---|---|
| Laravel 13 | Backend framework and application logic |
| PHP 8.4 | Server-side development |
| SQLite | Relational database |
| Blade | Server-rendered views, layouts, and reusable components |
| Tailwind CSS 4 | Styling and responsive UI |
| Vite | Frontend asset development and bundling |
| JavaScript | Interactive UI behavior and asynchronous quote actions |
| Pest | Feature testing |

### Blade Architecture

The interface uses Blade template inheritance and reusable components to keep the views organized and maintainable.

- **Template inheritance:** `@extends`, `@yield`, and `@section`
- **Reusable components:** `x-navbar`, `x-brand`, `x-quote-dialog`, and `x-brand-lantern`

## Database Design

Wahaj uses three database tables:

- **`users`** — Stores registered user accounts.
- **`quotes`** — Stores quote content and its relationship to the author.
- **`likes`** — A pivot table connecting users and quotes through a many-to-many relationship.

### Relationships & Data Integrity

- A user can create multiple quotes.
- A user can like multiple quotes, and each quote can be liked by multiple users.
- A unique constraint on `(user_id, quote_id)` prevents duplicate likes by the same user.
- Deleting a user removes their associated quotes and likes.
- Deleting a quote removes its associated likes.

## Getting Started

### Prerequisites

Make sure you have the following installed:

- PHP 8.4
- Composer
- Node.js and npm

### Installation

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd Wahaj
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Install frontend dependencies:

   ```bash
   npm install
   ```

4. Create your environment file:

   ```bash
   cp .env.example .env
   ```

   On Windows Command Prompt, you can use:

   ```cmd
   copy .env.example .env
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Configure the database in `.env` to use SQLite, then create the database file if it does not already exist:

   ```bash
   php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
   ```

7. Run the migrations:

   ```bash
   php artisan migrate
   ```

8. Build the frontend assets:

   ```bash
   npm run build
   ```

9. Start the local development server:

   ```bash
   php artisan serve
   ```

   Open the local URL displayed in your terminal.

> **Note:** These instructions assume the project is configured to use SQLite at `database/database.sqlite`. Review `.env.example` and the project's database configuration if your setup differs.

## Testing

Run the test suite with:

```bash
php artisan test
```

The project uses Pest for feature tests.

## Project Highlights

Wahaj brings together practical Laravel development and a distinctive product identity:

- Authentication and user-specific content management.
- Relational data modeling with many-to-many relationships.
- Database constraints and cascading deletes for data integrity.
- Interactive quote actions without full-page reloads.
- Bilingual localization with RTL and LTR layouts.
- Reusable Blade components and responsive styling.

## Credits

Developed as a final project for the **Al-Qimmah Bootcamp — Programming & Development Track**, in collaboration between **Tuwaiq Academy** and **Umm Al-Qura University**, 2026.

**Wahaj · وَهَج — Every quote is a glow of light.**
