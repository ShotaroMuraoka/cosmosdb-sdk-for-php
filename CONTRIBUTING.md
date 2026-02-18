# Contributing

## Development Setup

```bash
composer install
composer analyse
composer test
```

## Pull Request Guidelines

- Follow PSR-12.
- Add or update tests for behavioral changes.
- Keep public API changes documented in `README.md` and `CHANGELOG.md`.
- Ensure all checks pass before opening a PR.

## Quality Gates

- `composer validate --strict`
- `composer analyse`
- `composer test`
