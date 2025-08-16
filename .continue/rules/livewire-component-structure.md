---
globs: app/Livewire/**/*.php
description: Enforces a consistent directory structure for Livewire components
  to improve maintainability.
---

Organize Livewire components into logical directories (e.g., `app/Livewire/` or `src/Livewire/`). Each component should reside in its own directory, containing the component class (`.php`) and its corresponding view (`.blade.php`). Use PascalCase for component names (e.g., `UserProfile`) and kebab-case for the corresponding view file (e.g., `user-profile.blade.php`).