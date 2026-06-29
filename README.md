# ORBIT App

Pure PHP 8.4 application for the ORBIT human connection platform.

## Purpose

This repo contains the protected member web app, separate from WordPress.

It will handle:

- Authentication.
- Profiles.
- Intent-led onboarding.
- Matching.
- Trust scoring.
- Messaging.
- Reports and moderation.
- Subscriptions.

## Requirements

- PHP 8.4+
- MySQL or MariaDB
- Composer
- Web server pointing to `/public`

## Local Setup

```bash
cp .env.example .env
composer install
```

Configure database credentials in `.env`.

## Security Notes

- Never commit `.env`.
- Web root must be `/public` only.
- Storage and config directories must not be publicly accessible.
