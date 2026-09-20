# RWD handbook viewer

This repository contains the working Markdown handbook for Rockett Web Design and a small PHP viewer for reading it in a browser.

## Run locally

Install the Markdown dependency once:

```sh
composer install
```

Then start PHP's built-in server from the repository root:

```sh
php -S localhost:8000
```

Open [http://localhost:8000](http://localhost:8000). The viewer reads the `.md` files directly, so edits appear on refresh.

If Composer dependencies are not installed yet, the site uses a small built-in Markdown fallback so the navigation and basic document formatting still work.
