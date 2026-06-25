# Plan: Remove Nginx, Migrate to php:8.2-apache

## Overview

Replace the current two-container setup (PHP-FPM + Nginx) with a single-container Apache approach using `php:8.2-apache` image. This eliminates the Nginx dependency entirely while keeping the same external port (`8080`).

## Current Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    docker-compose.yml                    │
│                                                         │
│  ┌──────────────┐    ┌──────────────┐   ┌────────────┐ │
│  │   app:9000   │◄───│ nginx:8080   │   │  db:3306   │ │
│  │ (php:8.2-fpm)│    │ (nginx:alpine)│   │ (mysql:8.0)│ │
│  └──────────────┘    └──────────────┘   └────────────┘ │
│       PHP-FPM          Web Server         Database      │
└─────────────────────────────────────────────────────────┘
```

## Target Architecture

```
┌──────────────────────────────────────────────────────┐
│                   docker-compose.yml                  │
│                                                      │
│  ┌──────────────────────┐   ┌────────────────────┐  │
│  │   app:8080 (port 80)  │   │     db:3306        │  │
│  │ (php:8.2-apache)     │   │   (mysql:8.0)      │  │
│  │  ┌──────────────────┐ │   └────────────────────┘  │
│  │  │ Apache + mod_php │ │        Database           │
│  │  └──────────────────┘ │                           │
│  │   Web Server + PHP    │                           │
│  └──────────────────────┘                            │
└──────────────────────────────────────────────────────┘
```

## Changes Required

### 1. Dockerfile — Switch to Apache base image

**File:** [`Dockerfile`](docker-compose.yml:1)

| Change | Details |
|--------|---------|
| Base image | `FROM php:8.2-fpm` → `FROM php:8.2-apache` |
| Enable mod_rewrite | Add `RUN a2enmod rewrite` (required for Laravel `.htaccess` URL rewriting) |
| Configure VirtualHost | Copy a custom Apache vhost config that sets DocumentRoot to `/var/www/public` with `AllowOverride All` |
| Remove `EXPOSE 9000` | Apache listens on port 80 by default |
| Remove `CMD ["php-fpm"]` | `php:8.2-apache` has its own entrypoint that starts Apache |

A new Apache vhost config file will be created at [`docker/apache/000-default.conf`](docker/apache/) and copied into the image.

### 2. docker-compose.yml — Remove Nginx, Update App

**File:** [`docker-compose.yml`](docker-compose.yml:1)

| Change | Details |
|--------|---------|
| Remove `webserver` service | Entire nginx service block deleted |
| Update `app` ports | `"9000:9000"` → `"8080:80"` (Apache on 80, expose as 8080 externally) |
| Remove `working_dir` | Already set in Dockerfile's `WORKDIR /var/www` |
| Remove `depends_on: - db` from webserver | No longer needed since webserver is gone |

### 3. Remove Nginx configuration directory

**Path:** [`docker/nginx/`](docker/nginx/)

Delete the entire `docker/nginx/` directory with its `default.conf` file.

### 4. Add Apache virtual host configuration

**New file:** [`docker/apache/000-default.conf`](docker/apache/000-default.conf)

This will contain the Apache VirtualHost configuration pointing to `/var/www/public` with `AllowOverride All` to support Laravel's `.htaccess` routing.

## Files Modified

| File | Action |
|------|--------|
| [`Dockerfile`](Dockerfile) | Modified |
| [`docker-compose.yml`](docker-compose.yml) | Modified |
| [`docker/nginx/default.conf`](docker/nginx/default.conf) | Deleted |
| [`docker/nginx/`](docker/nginx/) | Deleted (directory) |
| [`docker/apache/000-default.conf`](docker/apache/) | Created (new) |

## Verification

After changes are applied:
1. Run `docker compose up -d --build` — should build and start only `app` and `db` services
2. Access `http://localhost:8080` — Laravel app should be served directly by Apache
3. Laravel routes should work correctly via Apache's `mod_rewrite` processing the `.htaccess` file
