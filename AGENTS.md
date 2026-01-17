---
trigger: always_on
---

# Project Context

## Domain Knowledge

### Overview
This project is a personal application designed to track and manage media events from a Plex Media Server. It listens for webhooks from Plex (e.g., when media is added or played), processes this information, and synchronizes it with external metadata sources like **TheTVDB**.

### Core Goals
- **Event Tracking**: Capture webhooks from Plex to know when new content is available or watched.
- **Metadata Synchronization**: automatically fetch details about shows and episodes from TheTVDB using the IDs provided by Plex.
- **User Progress**: Track individual user interaction with releases (e.g., watching an episode) via the `ReleaseUser` entity.

### Key Terminology
- **Series**: Represents a TV Show.
- **Release**: Represents a specific unit of media within a series, such as an Episode.
- **ReleaseUser**: A join entity tracking the relationship between a `User` and a `Release` (e.g., has the user watched this?).
- **Plex Event**: A webhook payload sent by Plex containing metadata about a specific media action.

---

## Frontend (Vue)

The frontend is a Single Page Application (SPA) built with **Vue 3**, **Vite**, **TypeScript**, and **Tailwind CSS**.

### Directory Structure
- `frontend/src/views/`: Contains page-level components (e.g., `HomeView.vue`, `LoginView.vue`).
- `frontend/src/components/`: Reusable UI components (e.g., `InputField.vue`, `PrimaryButton.vue`).
- `frontend/src/api.ts`: Centralized Axios instance for making API requests to the backend.
- `frontend/src/router.ts`: Vue Router configuration defining application routes.

### Key Components & Helpers
- **`api.ts`**: Use this for all HTTP interactions. It comes pre-configured with the base URL and necessary headers.
- **`InputField.vue`**: A standardized input component with support for labels, error messages, and slots (prefix/suffix). Use this for forms to maintain design consistency.
- **`PrimaryButton.vue`**: The standard action button for the application.


### Code Style
- **Component Order**: In `.vue` files, the `<script>` block must come before the `<template>` block. Standard order: `<script>`, `<template>`, `<style>`.
- **DRY Principle**: Extract repeated and large chunks of code into reusable functions, composables, or components to improve maintainability and readability.

### Development
- **Run Locally**: `npm run dev` (starts the Vite dev server).
- **Build**: `npm run build` (compiles assets to `dist/`).

---

## Backend (Symfony)

The backend is built with **Symfony** and **PHP 8.4**, using **Doctrine ORM** for persistence. It follows a structure that separates Domain interfaces from their implementations (e.g., TVDB integration).

### Directory Structure
- `src/Controller/`: HTTP entry points.
  - `PlexEventController`: Handles incoming Plex webhooks.
  - `LoginController` / `RegistrationController`: Authentication endpoints.
- `src/Entity/`: Doctrine entities mapping to the database.
- `src/Domain/`: Domain-centric interfaces and value objects (e.g., `Media\Show`, `Media\Episode`).
- `src/Services/Tvdb/`: Implementation of domain interfaces interacting with TheTVDB API.
- `src/Repository/`: Doctrine repositories for database queries.

### Entities
- **`User`**: App users. Implements Symfony Security interfaces.
- **`Series`**: Top-level media entity (TV Show).
- **`Release`**: Child media entity (Episode). Has a `ReleaseType` enum.
- **`ReleaseUser`**: Pivot table entity for user-specific data on a release.

### Reusable Logic & Services
- **`TvdbClient`** (`src/Services/Tvdb/TvdbClient.php`): Handles direct communication with TheTVDB API.
- **`EpisodeRepositoryInterface`** (`src/Domain/Media/`): Use this interface when needing episode data in business logic to decouple from the specific data source (DB vs API).
- **`PlexEventPayloadDto`**: A Data Transfer Object used in the controller to validate and structure the incoming webhook JSON.

---

## Infrastructure & Setup

### Docker
The project uses a custom `Dockerfile` with a multi-stage build process:
1. **composer**: Installs PHP dependencies.
2. **frontend**: Installs Node dependencies and builds frontend assets.
3. **base**: Sets up PHP 8.4 FPM (Alpine), Nginx, and Supervisor.
4. **app**: Combines PHP code and built frontend assets (`frontend/dist/` copied to public web root).

### Commands
- **Start Services**: `docker compose up -d`
- **Frontend Dev**: `cd frontend && npm run dev`
- **Backend Commands**: Run via the ID, e.g., `php bin/console list`.

### Environment
- Configuration is managed via `.env` files.
- `APP_ENV`: Defaults to `prod` in the Dockerfile.