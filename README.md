# email-archive-poc

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

## Live Demo

https://email-archive-poc.apps.ellington.io/
