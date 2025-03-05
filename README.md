# StreamPlus - Multi-Step Onboarding Application

## Features

- Multi-step onboarding process
- Dynamic form fields based on country selection
- Real-time validation
- CSRF protection
- Responsive design with Bootstrap
- Internationalization support
- Session-based user data management

## Requirements

- PHP 8.1 or higher
- Symfony 7.0 or higher
- MySQL 8.0 or higher
- Composer
- Node.js and npm (for asset management)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/streamplus.git
cd streamplus
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Create the database:
```bash
php bin/console doctrine:database:create
```

5. Run migrations:
```bash
php bin/console doctrine:migrations:migrate
```

6. Build assets:
```bash
npm run build
```

7. Start the development server:
```bash
symfony server:start
```

## Project Structure

```
streamplus/
├── assets/
│   ├── controllers/         # Stimulus controllers
│   └── styles/             # CSS files
├── config/                 # Symfony configuration
├── migrations/            # Database migrations
├── public/               # Public assets
├── src/
│   ├── Controller/       # Controllers
│   ├── Entity/          # Doctrine entities
│   ├── Form/            # Form types
│   ├── Repository/      # Doctrine repositories
│   ├── Service/         # Services
│   └── Validator/       # Custom validators
├── templates/           # Twig templates
│   └── onboarding/      # Onboarding step templates
└── translations/        # Translation files
```

## Onboarding Steps

1. **User Information**
   - Name
   - Email
   - Phone
   - Subscription Type (Free/Premium)

2. **Address Information**
   - Dynamic fields based on country selection
   - Country-specific validation
   - Real-time field updates

3. **Payment Information** (Premium only)
   - Credit Card Number
   - Expiration Date
   - CVV

4. **Confirmation**
   - Review of all entered information
   - Final submission

## Development

### Features

1. Create/update entity in `src/Entity/`
2. Create form type in `src/Form/`
3. Update controller in `src/Controller/`
4. Create/update template in `templates/onboarding/`
5. Add translations in `translations/`


### Code Style

The project follows Symfony coding standards. To check your code:

```bash
php vendor/bin/php-cs-fixer fix --dry-run
```

To automatically fix code style issues:

```bash
php vendor/bin/php-cs-fixer fix
```
