# email-archive-poc

[![Laravel](https://github.com/aaronellington/email-archive-poc/actions/workflows/laravel.yml/badge.svg)](https://github.com/aaronellington/email-archive-poc/actions/workflows/laravel.yml)

## Development Environment Setup

```shell
# Check out the script for details about how it's being started
ops/bin/dev-start

# Enable Live Reload
vendor/bin/sail npm run dev

# Run Worker (if not using sync driver)
vendor/bin/sail php artisan queue:work

# Test API endpoint
ops/bin/upload
```

## Helpful Links

-   [Live Demo](https://email-archive-poc.apps.ellington.io/)
-   [Commit](https://github.com/aaronellington/email-archive-poc/commit/f9b1655993b19d610c5aa0abd96dd8c406a67f57) where most of the work for the task was completed

## Notes

-   I'm sure it will be obvious from looking at this that it's my first Laravel project but creating this taught me a lot about how the stack works and I'm excited to learn more about it.
-   I probably could have taken more advantage of Livewire/Volt components to make some portions a little less repetitive.
-   Right now I'm polling to notice when the worker is complete but if I had more time I would have liked to setup Laravel Echo for real time updates.
