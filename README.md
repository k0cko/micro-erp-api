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

## Testing the API

An interactive [Bruno](https://www.usebruno.com/) collection is included in the repository.

**Note:** Since there are no automated functional tests yet, this collection is the best way to verify system behavior manually.

**Getting started:**
1. Open Bruno and **Import Collection** using the `micro-erp-api.yml` file in the repo.
2. Select the **Micro ERP API Environment** from the dropdown in the top right.
3. Run the `POST /login` request (in the `Auth` folder) with the admin credentials you created during setup.
4. Copy the resulting token, paste it into the `token` environment variable, and hit save.

*(Note: There is also a Postman-compatible export (`micro-erp-api-postman.json`) included if that is your preferred API client.)*

## Roadmap

**In progress / coming next:**

* JWT token refresh
* Functional tests

**Planned:**

* Assign users to warehouses
* Audit log (created_by / updated_by)
* Login rate limiting
* Delivery / order imports
* Filtering (when frontend is started)
* React frontend

## Known limitations
* **Warehouse quantity reservation** — when a worker marks a purchase order item as "prepared", the warehouse quantities are not immediately reserved. This means another worker could allocate the same stock to a different order before the first one is completed.
* **No login rate limiting** — the authentication endpoint is currently not rate limited, meaning it is vulnerable to brute force attacks.
* **No functional tests** — the application behavior is currently verified manually only.