# Transaction Approval System API

This is a Laravel-based transaction approval system that allows for the creation and management of expenses with a defined multi-stage approval process.

## Features

-   **Approver Management**: Create and manage individuals who can approve expenses.
-   **Configurable Approval Stages**: Define a sequential flow of approvers required for an expense to be approved.
-   **Expense Submission**: Submit new expenses with an initial "pending" status.
-   **Expense Approval**: Approvers can approve expenses, with the system enforcing the predefined approval sequence. An approver cannot approve the same expense twice.
-   **Automatic Status Update**: Expenses automatically change their status to "approved" once all required approvers in the sequence have approved them.
-   **Expense Details**: Retrieve detailed information about an expense, including its current status and a list of all recorded approvals.

## API Endpoints

The application exposes the following API endpoints:

### Approvers

-   `POST /api/approvers`
    -   **Description**: Creates a new approver.
    -   **Request Body**:
        ```json
        {
            "name": "string"
        }
        ```
    -   **Response**: `201 Created` with the new approver's data.

### Approval Stages

-   `POST /api/approval-stages`
    -   **Description**: Adds a new approver to the approval sequence.
    -   **Request Body**:
        ```json
        {
            "approver_id": "integer"
        }
        ```
    -   **Response**: `201 Created` with the new approval stage's data.
-   `PUT /api/approval-stages/{id}`
    -   **Description**: Updates an existing approval stage.
    -   **Parameters**: `id` (integer) - The ID of the approval stage to update.
    -   **Request Body**:
        ```json
        {
            "approver_id": "integer"
        }
        ```
    -   **Response**: `200 OK` with the updated approval stage's data, or `404 Not Found` if the stage does not exist.

### Expenses

-   `POST /api/expense`

    -   **Description**: Submits a new expense for approval.
    -   **Request Body**:
        ```json
        {
            "amount": "integer"
        }
        ```
    -   **Response**: `201 Created` with the new expense's data (initial status will be 'menunggu persetujuan').

-   `PATCH /api/expense/{id}/approve`

    -   **Description**: Allows an approver to approve a specific expense. This endpoint enforces the approval sequence and prevents duplicate approvals from the same approver for the same expense.
    -   **Parameters**: `id` (integer) - The ID of the expense to approve.
    -   **Request Body**:
        ```json
        {
            "approver_id": "integer"
        }
        ```
    -   **Response**:
        -   `200 OK` with the updated expense data if approved successfully.
        -   `422 Unprocessable Entity` if the approver has already approved the expense, or if the approval sequence is not followed, or if the approver ID is invalid.
        -   `404 Not Found` if the expense does not exist.

-   `GET /api/expense/{id}`
    -   **Description**: Retrieves the details of a specific expense, including its status and a list of all associated approvals.
    -   **Parameters**: `id` (integer) - The ID of the expense to retrieve.
    -   **Response**: `200 OK` with the expense details, or `404 Not Found` if the expense does not exist.

## Installation and Setup

1.  **Clone the repository**:

    ```bash
    git clone https://github.com/arizaakmal/transaction-approval.git
    cd transaction-approval
    ```

2.  **Install PHP dependencies**:

    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies**:

    ```bash
    npm install
    ```

4.  **Copy `.env.example` to `.env`**:

    ```bash
    cp .env.example .env
    ```

5.  **Generate application key**:

    ```bash
    php artisan key:generate
    ```

6.  **Configure your database** in the `.env` file. For example, using MySQL:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=transaction-approval
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    Make sure the database `transaction-approval` exists in your MySQL server.  
    You can create it using a MySQL client or with the following command:

    ```sql
    CREATE DATABASE `transaction-approval`;
    ```

7.  **Run migrations and seed the database**:

    ```bash
    php artisan migrate --seed
    ```

    This will create the necessary tables and populate initial data for statuses, approvers, and approval stages.

8.  **Start the development server**:
    ```bash
    php artisan serve
    ```

The application will be accessible at `http://127.0.0.1:8000` (or another port if specified by `php artisan serve`). The API documentation can be accessed at `/api/documentation`.

## Running Tests

To run the feature tests for the expense approval flow:

```bash
php artisan test
```
