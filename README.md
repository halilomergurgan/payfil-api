## Payfil API
This project is a payment gateway API that processes payments using valid credit card information, integrates with products, and records transactions. The API is built using Laravel, Dockerized with Laravel Sail, and supports user roles like admin and user.

# Setup Instructions
To set up the project from scratch, you can use the provided setup.sh script. This script will automatically spin up the Docker containers, install dependencies, migrate the database, seed necessary data, and start the queue worker.

# Prerequisites
Docker installed on your system (you don't need PHP, MySQL, or Composer locally).

# Setup Process
1. Clone the repository
2. Run the setup script
```bash
   sh setup.sh
```
This script will:

- Start Docker containers with MySQL, Redis, and PHP.
- Install Composer dependencies.
- Create the .env file if it doesn't already exist.
- Generate the application key.
- Run database migrations and seeders.
- Start the queue worker to handle payment jobs.

3. After the setup is complete, the application should be running on http://localhost.


## API Documentation

### POST /api/v1/process-payment
**Description**: Processes a payment and dispatches it to a queue for further handling. It validates the card and processes the payment with product details.

#### Request Body:

```json
{
    "cardNumber": "4111111111111111",
    "expiryDate": "12/25",
    "cvv": "123",
    "amount": 100.50,
    "currency": "USD",
    "fullName": "John Doe",
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        },
        {
            "product_id": 3,
            "quantity": 1
        }
    ]
}
```

#### Response:

**200 OK**: Payment processing started.

```json
{
    "message": "Payment processing started."
}
```
**422 Unprocessable Entity**: Validation error or invalid card.

```json
{
    "error": "Card validation failed."
}
```

**500 Internal Server Error**: Unexpected error during payment processing.

### Valid Card Numbers

| Card Number       | Description        |
|-------------------|--------------------|
| 4111111111111111  | Visa (Valid)       |
| 5555555555554444  | Mastercard (Valid) |

### Invalid Card Numbers

| Card Number       | Description        |
|-------------------|--------------------|
| 1234567890123456  | Invalid Card       |
| 0000000000000000  | Invalid Card       |

### BIN Card Providers and Banks

| BIN Number | Card Provider | Bank             |
|------------|---------------|------------------|
| 411111     | Visa          | Provider Bank 1  |
| 411112     | Visa          | Provider Bank 1  |
| 511111     | Mastercard    | Provider  Bank 2 |
| 511112     | Mastercard    | Provider  Bank 2 |
| **Others** | Various       | Provider Bank 3  |

### Other API Endpoints

- **GET /api/v1/transactions**: Retrieves a list of transactions for the authenticated user.

- **GET /api/v1/transaction/{id}**: Retrieves details of a specific transaction by ID.

- **POST /api/v1/login**: Authenticates a user and provides a token.

#### Request Body:

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```
#### Response:

```json
{
    "token": "your-auth-token"
}
```
- **GET /api/v1/me:**: Retrieves the authenticated user's information.

- **GET /api/v1/logout**: Logs out the user..

### Users and Roles

For testing purposes, the following credentials are available:

#### Admin User:
- **Email**: `admin@example.com`
- **Password**: `password`

#### Demo User:
- **Email**: `demo@example.com`
- **Password**: `password`

### User Roles:
- **admin**: Has permission to manage payments and view transactions.
- **user**: Can only view their own transactions.

### Queue and Jobs

The API uses a queue system to handle payment processing. Once a payment is initiated, the `ProcessPaymentJob` is dispatched to the queue. The `setup.sh` script automatically starts the queue worker during setup.

If you need to manually run the queue worker, use the following command:

```bash
./vendor/bin/sail artisan queue:work
```

### Running Tests

The project includes a suite of tests for validating the payment process and user authentication. To run the tests, use the following command:

```bash
./vendor/bin/sail artisan test
```

### Technologies Used

The following technologies were used in this project:

- **Laravel 10**: PHP framework for building the API and handling application logic.
- **PHP 8.2**: The programming language powering the backend.
- **Docker with Laravel Sail**: A lightweight command-line interface for interacting with Docker.
- **MySQL**: The database used to store transaction, user, and product data.
- **Redis**: Used for managing queues and caching.
- **Spatie Permissions**: A package used for managing roles and permissions in the application.

