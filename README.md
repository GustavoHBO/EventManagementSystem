# Event Management System

[Event Management System] is a [brief description of your project]. It is built using the Laravel PHP framework and provides [mention the primary features and purpose of the project].

## Table of Contents

- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

## Getting Started

### Prerequisites

Before you begin, ensure you have met the following requirements:

- You have installed the latest version of [Composer](https://getcomposer.org/).
- You have installed the latest version of [PHP](https://www.php.net/).
- You have installed the latest version of [Git](https://git-scm.com/).
- You have installed the latest version of [Node.js](https://nodejs.org/en/).
- You have installed the latest version of [NPM](https://www.npmjs.com/).
- You have installed the latest version of [MySQL](https://www.mysql.com/).
- You have installed the latest version of [Redis](https://redis.io/).

### Installation

To install [Event Management System], follow these steps:

1. **Clone this repository:**

   ```bash
   git clone git@github.com:GustavoHBO/EventManagementSystem.git
   ```

2. **Install project dependencies:**

   ```bash
   sail up
   ```

3. **Copy the example environment file:**

   ```bash
   cp .env.example .env
   ```

4. **Generate an application key:**

   ```bash
   sail artisan key:generate
   ```

5. **Configure your `.env` file** with the necessary settings such as the database connection, mail, and other service configurations.

6. **Migrate the database:**

   ```bash
   sail artisan migrate
   ```

7. **Seed the database (if needed):**

   ```bash
   sail artisan db:seed
   ```

## Usage

To start the development server, run the following command:

   ```bash
   sail up -d
   ```

To stop the development server, run the following command:

   ```bash
    sail down
  ```

## Configuration

To configure [Event Management System], follow these steps:

1. **Configure your `.env` file** with the necessary settings such as the database connection, mail, and other service configurations.
2. **Configure your `config` files** with the necessary settings such as the database connection, mail, and other service configurations.
3. **Configure your `resources` files** with the necessary settings such as the database connection, mail, and other service configurations.
4. **Configure your `routes` files** with the necessary settings such as the database connection, mail, and other service configurations.
5. **Configure your `app` files** with the necessary settings such as the database connection, mail, and other service configurations.

To login as an administrator, use the following credentials:

```bash
Email: ENV('ADMIN_EMAIL')
Password: ENV('ADMIN_PASSWORD')
```

This command using CURL:

```bash
curl --location --request POST 'http://localhost:8000/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "ENV('ADMIN_EMAIL')",
    "password": "ENV('ADMIN_PASSWORD')"
}'
```

Get the full rotes in this document Postman:

```bash
https://api.postman.com/collections/30167526-da1e8899-a9ba-4f68-b4f5-9ce3bfe5267c?access_key=PMAT-01HE1B78MPJZD1PKPG7XYF3SN0
```
Each route has the headers configured with the token, so you can test the routes without having to log in again.
The adicional data is sent in headers, look the pre-request and post-request in each route.

## Testing

To run the tests, use the following command:

```bash
sail test
```

## Deployment

Run the following command to deploy [Event Management System]:

```bash
sail up -d
```

## Contributing

Contributions are welcome! Please see our [contributing guidelines](CONTRIBUTING.md).

## License

This project is licensed under the [GNU 3.0] License - see the [LICENSE](LICENSE) file for details.
