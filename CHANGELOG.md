# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Fixed

- Fixed database migrations to ensure correct order of table drops during rollback.
- Corrected foreign key naming in `service_complaints_images` table.
- Removed redundant `image` column from `car_service_images` table.
- Added missing `down()` method to `settings` migration.
- Synchronized Models and Controllers with recent schema changes.

### Added

- Created GitHub documentation: `README.md`, `CONTRIBUTING.md`, `CHANGELOG.md`, `LICENSE`.
- Added GitHub Actions workflow for automated testing.
