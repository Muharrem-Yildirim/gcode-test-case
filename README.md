# Gcode Test Case [![tests](https://github.com/Muharrem-Yildirim/gcode-test-case/actions/workflows/main.yml/badge.svg)](https://github.com/Muharrem-Yildirim/gcode-test-case/actions/workflows/main.yml)

## Description

This project gets currencies from TCMB API and stores in local database. In the frontend you can see the list of currencies and filter the list.

## Deployment

1. Copy .env.example to .env
2. Run `composer install`
3. Run `npm run build`
4. Run `bash ./vendor/bin/sail up -d` or without sail `php artisan migrate && php artisan serve --port 8000`

Optionally you can run `php artisan app:import-today` to import today's data.

## Running Tests

1. Run `php artisan key:generate --env=testing`
2. Run `php artisan test`

## Screenshots

<div style="display: flex; justify-content: space-between;">
    <img src="https://github.com/Muharrem-Yildirim/gcode-test-case/blob/main/screenshots/screenshot_1.png?raw=true" style="width: 100%; height: auto;"/>
    <img src="https://github.com/Muharrem-Yildirim/gcode-test-case/blob/main/screenshots/screenshot_2.png?raw=true"  style="width: 100%; height: auto;"/>
    <img src="https://github.com/Muharrem-Yildirim/gcode-test-case/blob/main/screenshots/screenshot_3.png?raw=true" style="width: 100%; height: auto;"/>
</div>

## What I used in this project

-   Laravel
-   TCMB API
-   MySQL
-   Docker
-   Composer
-   NPM
-   GitHub Actions
-   Sail
-   Interia.js/React.js
-   Tailwind CSS
-   Shadcn UI
-   React Query
