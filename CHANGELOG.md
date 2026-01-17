# Changelog

All notable changes to the Goklean project will be documented in this file.

## [v1.1.0] - 2026-01-17

### 🛠 Refactored Database Layer

- **Migration Fix**: Corrected the `down()` sequence in `service_complaints` to prevent foreign key errors during rollback.
- **Naming Standardization**: Renamed foreign keys in images tables to follow singular naming conventions (e.g., `service_complaint_id`).
- **Schema Cleanup**: Removed redundant `image` column in `car_service_images` in favor of `image_path`.
- **Stability**: Added missing `down()` methods to core migrations like `settings`.

### 🏗 Architecture & DX

- **GitHub Documentation**: Added comprehensive `README.md`, `CONTRIBUTING.md`, and `CHANGELOG.md`.
- **CI/CD**: Implemented GitHub Actions workflow for automated testing with PostgreSQL.
- **Model Sync**: Updated Eloquent relationships and `$fillable` attributes to match revised schema.

## [v1.0.0] - Initial Release

- Core booking and scheduling engine.
- Razorpay and Wallet integration.
- Service unit mobile API.
- Customer management portal.
