# wordpress-seeder

This WordPress plugin can be used via wp-cli to create seed data in the development environment.

## Features

- Seed users
- Seed terms
- Seed posts
- Seed comments

## Requirements

- WordPress
- WP-CLI
- Composer

## How to use

1. Clone the repository into your WordPress plugins directory:

   ```sh
   git clone https://github.com/MasakiAraki/wordpress-seeder.git wp-content/plugins/wordpress-seeder
   ```

2. Include the following in the `composer.json`

```json
"autoload": {
"psr-4": {
  "WordPressSeeder\\": "wp-content/plugins/wordpress-seeder/"
}
},
"require": {
"fakerphp/faker": "^1.23"
}
```

3. After updating `composer.json`, run the following command to install dependencies and update the autoloader:

```sh
composer install
```

4. Active the plugin

```sh
wp plugin activate wordpress-seeder
```

5. Run the command to create the seed

```sh
wp seed all
```
