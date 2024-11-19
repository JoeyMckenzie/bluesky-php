# Changelog

## v0.1.3 - 2024-11-19

### What's New

- Refactors namespaces into bsky and atproto
- Adds more coverage for the graph bsky namespace

## v0.1.2 - 2024-11-18

### What's New

- Adds a new `ATProtoNamespace` client for the `com.atproto.*` namespace APIs
- Session creation has been moved to the new ATProto `Server` client

## v0.1.1 - 2024-11-18

### What's New

- Adds new endpoints in the graph resource
- Moves resources underneath their namespace for better organization, e.g.

```php
// Before
$client->feed()->getTimeline();

// Now becomes
$client->bsky()->feed()->getTimeline();



```
- Minor changes to examples

**Full Changelog**: https://github.com/JoeyMckenzie/bluesky-php/compare/v0.1.0...v0.1.1

## v0.1.0 - 2024-11-18

### What's New

- Initial release!
- 20 endpoints added for coverage
- Most feed and actor endpoints added
