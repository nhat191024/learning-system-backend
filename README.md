## Running the Project with Docker

To set up and run this project using Docker, follow these steps:

### Prerequisites

Ensure you have the following installed on your system:

- Docker version 20.10 or higher
- Docker Compose version 1.29 or higher

### Environment Variables

The project requires specific environment variables to be set. You can define these in a `.env` file in the project root. Refer to the `.env.example` file for required variables and their formats.

### Build and Run Instructions

1. Build the Docker images and start the services:

   ```bash
   docker-compose up --build
   ```

2. Access the application at `http://localhost:9000`.

### Services and Ports

- **App Service**: Exposes port `9000` for PHP-FPM.
- **Database Service**: Uses MySQL and is accessible internally within the Docker network.

### Additional Configuration

- Ensure the `storage` and `bootstrap/cache` directories are writable by the web server.
- Run database migrations if necessary:

  ```bash
  docker-compose exec app php artisan migrate
  ```

For further details, refer to the project's documentation or contact the maintainers.

