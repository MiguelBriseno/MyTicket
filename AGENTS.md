# AGENTS.md - Developer Guidelines for This Project

## Project Overview
This is a Laravel 12 PHP application with Filament admin panel, Livewire for dynamic frontend, Spatie media library for file attachments, and Spatie permissions for role-based access control.

## Technology Stack
- **PHP**: 8.2+ (Laravel 12)
- **Frontend**: Livewire, Tailwind CSS 4, Vite
- **Database**: SQLite (testing), configurable for other databases
- **Admin Panel**: Filament 3.x
- **Media**: Spatie Media Library
- **Auth**: Spatie Permissions

## Build / Lint / Test Commands

### Running Tests
```bash
# Run all tests
composer test
# or: php artisan test

# Run a single test class
./vendor/bin/phpunit tests/Feature/ExampleTest.php
php artisan test tests/Feature/ExampleTest.php

# Run a single test method
./vendor/bin/phpunit --filter test_the_application_returns_a_successful_response
php artisan test --filter test_the_application_returns_a_successful_response

# Run specific test suite
./vendor/bin/phpunit --testsuite Unit
./vendor/bin/phpunit --testsuite Feature
```

### Code Style (Laravel Pint)
```bash
# Run Pint to fix code style issues
./vendor/bin/pint

# Run Pint in check mode (without fixing)
./vendor/bin/pint --test
```

### Asset Building
```bash
# Build frontend assets for production
npm run build

# Run development server with hot reload
npm run dev

# Full setup (installs deps, builds assets)
composer setup
```

### Development Server
```bash
# Run dev environment (PHP server + queue + logs + Vite)
composer dev

# Or manually:
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
npm run dev
```

## Code Style Guidelines

### PHP Version & Strictness
- Minimum PHP 8.2
- Enable strict types in all new files: `declare(strict_types=1);`
- Use return type declarations and union types where appropriate

### Naming Conventions
- **Classes**: PascalCase (e.g., `TicketController`, `CreateTicket`)
- **Methods**: camelCase (e.g., `getTickets()`, `markAsResolved()`)
- **Variables**: camelCase (e.g., `$ticket`, `$departmentId`)
- **Constants**: SCREAMING_SNAKE_CASE (e.g., `DEFAULT_STATUS`)
- **Database tables/columns**: snake_case (e.g., `created_at`, `assigned_to`)
- **Routes**: kebab-case (e.g., `portal.tickets`)

### File Organization
- One class per file
- PSR-4 autoloading: `App\Models\Ticket` → `app/Models/Ticket.php`
- Use subdirectories for related classes (e.g., `app/Filament/Resources/`)

### Import Statements
- Use fully qualified class names or proper `use` imports
- Group imports: Laravel framework → Third-party → Application
- Sort alphabetically within groups
```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Ticket;
```

### Code Formatting (follows Laravel Pint)
- 4-space indentation (no tabs)
- Single quotes for strings unless interpolation needed
- Trailing commas in multi-line arrays/methods
- Maximum line length ~100 characters
- Use phpdoc comments for complex methods, public APIs

### Types & Type Hints
- Use return types on all methods: `public function getId(): int`
- Use union types for nullable/optional: `public function find(?int $id)`
- Use typed properties where possible
- Use `void` for methods with no return value

### Models & Eloquent
- Define `$fillable` for mass assignment
- Define `$casts` for attribute casting (datetime, boolean, etc.)
- Define `$hidden` for attributes excluded from serialization
- Use relationships with proper return types
- Always use dependency injection for model repositories

### Controllers
- Use controller methods with proper return types
- Return views, JSON responses, or redirects
- Use form requests for validation logic
- Leverage dependency injection

### Livewire Components
- Use typed public properties for component state
- Define validation rules in `$rules` property
- Use `WithFileUploads` trait for file handling
- Follow the component lifecycle hooks appropriately

### Error Handling
- Use try/catch for operations that may fail
- Use Laravel's exception handling and logging
- Throw exceptions for unexpected states
- Use validation errors with `$this->validate()` in Livewire

### Blade Templates
- Use Blade components for reusable UI
- Keep logic out of views (use View Composers if needed)
- Use lowercase-kebab for component names
- Prefer Alpine.js over inline scripts

### Database & Migrations
- Use migrations for all schema changes
- Keep migrations atomic and reversible
- Use foreign key constraints
- Use appropriate column types (bigIncrements for IDs)
- Add indices for frequently queried columns

### Git Conventions
- Use meaningful commit messages
- Create feature branches for new features
- Run Pint and tests before committing

## Project Structure
```
app/
├── Filament/          # Admin panel resources and pages
├── Http/Controllers/  # HTTP controllers
├── Livewire/          # Livewire components
├── Models/            # Eloquent models
├── Notifications/     # Notification classes
├── Policies/          # Authorization policies
├── Providers/         # Service providers
routes/
├── web.php           # Web routes
├── console.php       # Artisan commands
config/              # Configuration files
tests/
├── Feature/          # Feature tests
├── Unit/             # Unit tests
database/
├── migrations/       # Database migrations
```

## Common Tasks Reference
- Create model: `php artisan make:model ModelName`
- Create migration: `php artisan make:migration create_tickets_table`
- Create controller: `php artisan make:controller ControllerName`
- Create Livewire: `php artisan make:livewire ComponentName`
- Create Filament resource: `php artisan make:filament-resource ResourceName`

## Environment Setup
```bash
# Initial setup
composer install
cp .env.example .env  # or copy from existing .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```