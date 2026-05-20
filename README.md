# Micro ERP API

Micro ERP API is a back-end REST API for managing warehouse inventory, deliveries, and purchase orders. The architecture is influenced by concepts I am currently studying from "Advanced Web Application Architecture" and "Object Design Style Guide" by Matthias Noback.

## How it works

There are two types of users - Administrators and Workers. Administrators create Inquiries, which is the general name for incoming Deliveries and outgoing Purchase Orders. Workers check the inquiries assigned to their specific warehouse and process them. When a worker marks an Inquiry as completed, the system automatically updates the warehouse inventory (adding items for Deliveries, or removing them for Purchase Orders).

## Tech stack
* **PHP 8.4**
* **Symfony 8** with **Doctrine ORM**
* **Database:** MySQL
* **Caching:** Redis
* **Authentication:** JWT (LexikJWTAuthenticationBundle)
* **Infrastructure:** Docker & Nginx

## Architecture
The architecture follows Domain-Driven Design principles, so the code is organized around the business domain.
* **Entities:** Pure domain objects, no framework dependencies or infrastructure concerns.
* **Controllers:** Kept as thin as possible. They work only as a navigator that handles HTTP requests and pass the data forward to the service layer and return a response.
* **Service Classes:** Handle the business logic in isolation.
* **DTOs (Data Transfer Objects):** Objects that act as "dumb" data bags.
  * **Request DTOs:** Map incoming request payload.
  * **Response DTOs:** Shape outgoing data.
* **Mappers:** Clean data transformation between Entities and DTOs so that neither layer needs to know about the other's structure.
* **Repositories:** Encapsulate all database queries, keeping persistence logic out of services and controllers.

## How to run

**Prerequisites:** Docker

**Steps:**
1. Clone the repo `git clone git@github.com:k0cko/micro-erp-api.git`
2. Go into repository `cd micro-erp-api`
3. Copy .env.example to .env `cp .env.example .env`
4. Initialize the containers `docker compose up -d` (*JWT keys are generated automatically on container start*)
5. Enter the PHP container `docker exec -it micro-erp-php sh`
6. Run migrations `php bin/console doctrine:migrations:migrate`
7. Create the super admin with the custom command and follow the prompts `php bin/console app:create-super-admin`

## Roadmap

**In progress / coming next:**

* Stock management (warehouse quantities updated on inquiry completion)
* JWT token refresh
* Functional tests

**Planned:**

* Assign users to warehouses
* Audit log (created_by / updated_by)
* Login rate limiting
* Delivery / order imports
* Pagination and filtering (when frontend is started)
* React frontend

## Known limitations
* **Warehouse quantity reservation** — when a worker marks a purchase order item as "prepared", the warehouse quantities are not immediately reserved. This means another worker could allocate the same stock to a different order before the first one is completed.
* **No login rate limiting** — the authentication endpoint is currently not rate limited, meaning it is vulnerable to brute force attacks.
* **No functional tests** — the application behavior is currently verified manually only.