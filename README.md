# Project Builder (`build.awwwesome.cz`)

A URL-based installation script generator. Calling an endpoint returns a ready-to-run bash script that you can execute directly in your terminal.

## Quick Start

Navigate to the directory where you want to create your project and run:

```bash
curl -sL https://build.awwwesome.cz/laravel/my-project | bash
```

By default, this creates a new Laravel project with **PHP 8.5** and **`pgsql`**, **`redis`**, and **`mailpit`** services.

## URL Input Parameters

### 1. Project Name (URL Path)
```text
https://build.awwwesome.cz/laravel/<project-name>
```
- Allowed characters: `a-z`, `A-Z`, `0-9`, `-`, `_` (max 64 characters).

### 2. Query Parameters

| Parameter | Description | Default Value | Example |
| :--- | :--- | :--- | :--- |
| `with` | Comma-separated list of Docker services for Laravel Sail, or `none` | `pgsql,redis,mailpit` | `?with=pgsql,redis` |

## Usage Examples

- **Default setup (`pgsql`, `redis`, `mailpit`):**
  ```bash
  curl -sL https://build.awwwesome.cz/laravel/my-project | bash
  ```

- **PostgreSQL and Redis only:**
  ```bash
  curl -sL "https://build.awwwesome.cz/laravel/my-project?with=pgsql,redis" | bash
  ```

- **Add an extra service (e.g. Meilisearch):**
  ```bash
  curl -sL "https://build.awwwesome.cz/laravel/my-project?with=pgsql,redis,mailpit,meilisearch" | bash
  ```

- **No additional services:**
  ```bash
  curl -sL "https://build.awwwesome.cz/laravel/my-project?with=none" | bash
  ```

> **Note:** When using query parameters (`?...`), wrap the URL in quotes so your shell passes it properly to `curl`.
