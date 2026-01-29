# Laravel 12 API Rules & Guidelines

## 🚀 Foundational Context

This is a high-performance Laravel 12 API.

- **PHP**: 8.4
- **Framework**: Laravel 12
- **Testing**: Pest 4 / PHPUnit 12
- **Formatting**: Laravel Pint
- **Auth**: Laravel Sanctum

## 🛠 Project Standards

### 1. PHP & Laravel Conventions

- Always use **PHP 8.4** features (e.g., Constructor Property Promotion).
- **Strict Typing**: Always use explicit return type declarations and parameter type hints.
- **Control Structures**: Always use curly braces, even for single-line bodies.
- **Eloquent**:
    - Prefer `Model::query()` over `DB::`.
    - Use relationship methods with type hints.
    - Prevent N+1 issues with eager loading.
- **Validation**: Always use **Form Request** classes. No inline validation in controllers.
- **Architecture**: Stick to the streamlined Laravel 11+ structure (middleware in `bootstrap/app.php`, etc.).

### 2. Testing (Pest)

- Use **Pest** for all tests.
- Prefer **Feature tests** over Unit tests unless testing pure logic.
- Command: `php artisan test --compact`.

### 3. Formatting & Linting

- Always run **Laravel Pint** before finalizing changes.
- Command: `vendor/bin/pint --dirty`.

## 🤖 Antigravity Specifics

- **Artisan**: Use `php artisan make:...` for all boilerplate. Always pass `--no-interaction`.
- **Environment**: Use `config()` helper, never `env()` outside of config files.
- **Routing**: Use named routes and the `route()` assistant.

## 📝 Tone & Communication

- Be concise. Focus on implementation and logic over explaining basic concepts.
- If a frontend change isn't showing, suggest `npm run dev` or `npm run build`.
