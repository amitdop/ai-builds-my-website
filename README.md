# AI Builds My Website

An AI-assisted website builder prototype for small businesses. An owner enters business details, uploads supporting materials, reviews an AI-generated content draft, edits it, and publishes an approved snapshot at a public URL.

## What works today

- Create a business profile with its name, city, and optional website/social links.
- Upload up to 10 PDF or image files per request (10 MB each).
- Extract text from readable PDFs. Photos are stored, but are not analyzed; scanned PDFs have no OCR.
- Generate a source-grounded website content draft using Google Gemini.
- Review reported missing details and conflicts, then edit the tagline and facts.
- Publish a reviewed snapshot at `/sites/{slug}`. Draft edits do not change the public version until republished.
- Reopen a matching business profile instead of creating a duplicate when the same browser session submits the same name and city (case and surrounding whitespace are ignored).

## How it works

1. **Routes** in `routes/web.php` connect browser URLs to controllers.
2. **Controllers** validate requests and coordinate profile creation, uploads, drafting, editing, and publishing.
3. **Models** represent businesses and their uploaded documents in MySQL/MariaDB.
4. **Services** extract PDF text and call Gemini to create a validated draft.
5. **Blade views** render the owner workspace and public website.

The `businesses` table stores entered details, draft JSON, and a separate published-content snapshot. The `documents` table records uploaded files and extracted text. Files are stored through Laravel's public storage disk.

## Requirements

- PHP 8.3 or later with the extensions required by Laravel and PDO MySQL
- Composer
- Node.js and npm
- MySQL or MariaDB
- A Google Gemini API key to use AI draft generation (optional for the rest of the app)

## Local setup

Run these commands from the project directory:

```bash
composer install
if [ ! -f .env ]; then cp .env.example .env; fi
```

This keeps an existing `.env` file intact. Edit `.env` on your own machine. Set the database connection details for a MySQL/MariaDB database and set `APP_URL` to the URL where you will run the app. To enable draft generation, add your own `GEMINI_API_KEY`. **Never commit `.env` or share your API key.**

Create the configured database and ensure the database user can access it. Then run:

```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

Open the address printed by Artisan. In Codespaces, make port 8000 public using the Ports panel and open its forwarded URL.

## Use the app

1. Create a business profile.
2. Upload PDFs, price lists, or photos. Only readable PDF text is extracted.
3. Choose **Generate a draft**. The app sends the business name, city, and up to 10 recent extracted documents (up to 10,000 characters per document) to Gemini.
4. Review the draft, missing information, conflicts, and source evidence. Edit it if needed.
5. Tick the review confirmation and publish. The public page is available at `/sites/{slug}`.
6. Publish again after changing the draft to update the public snapshot.

## Privacy, safety, and current limitations

- Uploaded document text is treated as untrusted evidence, not as instructions to the AI. AI output is validated against source evidence; the app does not intentionally fill unknown business facts with invented specifics.
- Unresolved conflicting facts and source evidence are omitted from public pages. Owner-edited facts are marked as business-provided.
- Social links are stored but are **not scraped or opened**. The draft identifies provided links as unread.
- Image analysis and OCR for scanned PDFs are not implemented.
- Gemini free-tier data handling is controlled by Google. Avoid uploading confidential material; Google's free tier may use submitted content to improve its products.
- There is no user account system. Edit access is tied to the browser session that created the profile. Clearing or losing that session makes the profile read-only. A different browser session may create a separate profile with the same name and city.
- The public site is served by this Laravel application. A Codespaces forwarded URL is for development/demo use, not permanent production hosting.

## Checks

Run the automated tests and build the frontend assets:

```bash
php artisan test
npm run build
```

## Project decisions

See [DECISIONS.md](DECISIONS.md) for the reasoning behind source-grounded generation, session ownership, duplicate handling, and publication snapshots.
