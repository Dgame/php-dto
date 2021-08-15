# Changelog

## v0.3.0 - 15.08.2021

### Added

- [Transformations](/README.md/#Transformations) (e.g. [Cast](/README.md/#Cast))
- [Optional](/README.md/#Optional)
- [Path](/README.md/#Path)
- [SelfValidation](/README.md/#SelfValidation)
- [ValidationStrategy](/README.md/#ValidationStrategy)

### Changed

- **Renamed** trait `From` to `DataTransfer`
- **Improved** [Type](/README.md/#Type) so that it can even detect generic arrays
- Validations can now be configured with a [ValidationStrategy](/README.md/#ValidationStrategy) to fail fast (default) or to collect **all** failures.
  The non-fast failure collection and handling can be configured by either implementing your own [ValidationStrategy](/README.md/#ValidationStrategy) or by overriding `FailureCollection` and `FailureHandler`.

### Removed

- `Call`. [Cast](/README.md/#Cast) can be used for the most common tasks instead of `Call`.
