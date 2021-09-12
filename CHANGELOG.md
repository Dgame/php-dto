# Changelog

## v0.4.0 - 12.09.2021

### Added

- [Boolean](/README.md/#Boolean)
- [Date](/README.md/#Date)
- [In](/README.md/#In)
- [Matches](/README.md/#Matches)
- [NotIn](/README.md/#NotIn)
- [Numeric](/README.md/#Numeric)
- [Trim](/README.md/#Trim)

### Changed

 - `finalize`of `DataTransferObject` is now private an will be called at the end of `from`.

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
