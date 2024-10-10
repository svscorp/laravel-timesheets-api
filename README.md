# Laravel API App

## Prerequisites

1. Ensure you have the Laravel application set up and running or
utilise Docker (see "**Environment setup**") to have fresh Laravel and Database up & running
2. The database is migrated and seeded with test data (follow the "**Migration and Seeding the Database**" sub-article).
3. You have a tool like Postman or cURL to make API requests.

### Environment setup

Execute `./vendor/bin/sail up`. It will bring all necessary components - laraverl and postgresql containers and launch the project.

### Migration and Seeding the Database

Execute the commands:
1. `./vendor/bin/sail artisan migrate`
2. `./vendor/bin/sail artisan db:seed`

After running this, your database should be populated with:
* 10 users
* 5 projects
* Each project will have 1-3 random users assigned to it
* Each user will have 1-5 timesheets, associated with random projects they're assigned to

## Base URL

Replace `{base_url}` in the following examples with your actual API base URL (e.g., `http://localhost/api`).

## API Calls Requirements

1. For **all** API calls, the `Accept` header must be present: `Accept: application/json`
2. For **non-public** API calls, the `Authorization` header must be present: ` Authorization: Bearer {token}`

## Postman

For your convenience the Postman API collection is created. Simply import `Laravel-Timesheets-API.postman_collection.json` into your Postman, update the collection level variable `bearerToken` and that's it :)

## Authentication

Note! after you register (step 1) new user and log in (step 2) with it, use the `access token` token provided in the response to update postman's
collection variable `bearerToken`. Then your API call will work.

If you are not using postman, then use provided bearerToken to authenticate manual API requests.

1. Register a new user
   ```
   POST {base_url}/register
   Headers: Accept: application/json
   Body: {
     "first_name": "John",
     "last_name": "Doe",
     "email": "john@example.com",
     "password": "password123",
     "date_of_birth": "1990-01-01",
     "gender": "male"
   }
   ```

2. Login with valid credentials
   ```
   POST {base_url}/login
   Headers: Accept: application/json
   Body: {
     "email": "john@example.com",
     "password": "password123"
   }
   ```
   
This will return you the access token, i.e.: `{"access_token":"2|cvPAhcJKFD9f3gDdSSalyvu5gwpkc2aJ9wtzxJxH0ebebb60","token_type":"Bearer"}`

3. Attempt to login with invalid credentials
   ```
   POST {base_url}/login
   Headers: Accept: application/json
   Body: {
     "email": "john@example.com",
     "password": "wrongpassword"
   }
   ```

4. Access protected route with valid token
   ```
   GET {base_url}/users
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

5. Attempt to access protected route without token
   ```
   GET {base_url}/users
   Headers: Accept: application/json
   ```

6. Logout
   ```
   POST {base_url}/logout
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

## Users

1. List all users (with pagination)
   
   Default: 1st page
   ```
   GET {base_url}/users
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

   2nd page
   ```
   GET {base_url}/users?page=2
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

   Change amount of records per page
   ```
   GET {base_url}/users?page=2&per_page=10
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

2. Retrieve a single user
   ```
   GET {base_url}/users/{id}
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

3. Create a new user
   ```
   POST {base_url}/users
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   Body: {
     "first_name": "Jane",
     "last_name": "Doe",
     "email": "jane@example.com",
     "password": "password123",
     "date_of_birth": "1992-05-15",
     "gender": "female"
   }
   ```

4. Update an existing user
   ```
   PUT {base_url}/users/{userId}
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   Body: {
     "first_name": "Jane",
     "last_name": "Smith"
   }
   ```

5. Delete a user
   ```
   POST {base_url}/users/{userId}
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

6. Filter users
   ```
   GET {base_url}/users?gender=female
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

## Projects

1. List all projects (with pagination)
   ```
   GET {base_url}/projects
   Headers: Authorization: Bearer {token}
   Headers: Accept: application/json
   ```

2. Retrieve a single project (including associated users and timesheets)
   ```
   GET {base_url}/projects/{id}
   Headers: Authorization: Bearer {token}
   ```

3. Create a new project
   ```
   POST {base_url}/projects
   Headers: Authorization: Bearer {token}
   Body: {
     "name": "New Project",
     "department": "IT",
     "start_date": "2023-06-01",
     "end_date": "2023-12-31",
     "status": "active"
   }
   ```

4. Update an existing project
   ```
   POST {base_url}/projects/{id}
   Headers: Authorization: Bearer {token}
   Body: {
     "name": "Updated Project Name",
     "status": "completed"
   }
   ```

5. Delete a project
   ```
   POST {base_url}/projects/{id}
   Headers: Authorization: Bearer {token}
   ```

6. Filter projects
   ```
   GET {base_url}/projects?name=Project&status=active
   Headers: Authorization: Bearer {token}
   ```

## Timesheets

1. List all timesheets (with pagination)
   ```
   GET {base_url}/timesheets?page=2
   Headers: Authorization: Bearer {token}
   ```

2. Retrieve a single timesheet (including associated user and project)
   ```
   GET {base_url}/timesheets/{id}
   Headers: Authorization: Bearer {token}
   ```

3. Create a new timesheet
   ```
   POST {base_url}/timesheets
   Headers: Authorization: Bearer {token}
   Body: {
     "task_name": "New Task",
     "date": "2023-06-01",
     "hours": 5.5,
     "user_id": 1,
     "project_id": 1
   }
   ```

4. Update an existing timesheet
   ```
   PUT {base_url}/timesheets/{id}
   Headers: Authorization: Bearer {token}
   Body: {
     "task_name": "Updated Task Name",
     "hours": 6.0
   }
   ```

5. Delete a timesheet
   ```
   DELETE {base_url}/timesheets/{id}
   Headers: Authorization: Bearer {token}
   ```

6. Filter timesheets
   ```
   GET {base_url}/timesheets?task_name=Task&user_id=1
   Headers: Authorization: Bearer {token}
   ```

## Unit Tests

Few example unit tests created. In order to run them, execute `./vendor/bin/sail artisan test`

## Notes

- Replace `{id}`, `{project_id}`, `{user_id}`, and `{token}` with actual values in your tests.
- For filtering, you can combine multiple parameters as needed (AND logic applied).

