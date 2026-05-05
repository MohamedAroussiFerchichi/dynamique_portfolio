# Portfolio Administration Panel - Project Presentation

## Step 1: Architecture & Purpose
*   **Goal:** A dynamic backend for a portfolio website where an administrator can manage their projects, certificates, and personal information without touching the code.
*   **Stack:** PHP (Backend), MySQL (Database), HTML/CSS (Frontend). 
*   **Core Concept:** The application uses PHP sessions to protect administrative pages. Only logged-in users can view and edit the content.

## Step 2: The Database (The Foundation)
*   **Core Files:** `portfolio.sql` & `db.php`
*   The system uses relational tables to store data dynamically: typically `admin` (users), `projets` (projects), and `certificats` (certificates).
*   `db.php` contains the connection logic (`mysqli_connect`) that links the PHP application to the MySQL database, centralizing the configuration.

## Step 3: Security & Authentication
*   **Core Files:** `login.php`, `inscription.php`, `logout.php`.
*   **Authentication Flow:** 
    *   `login.php` checks if a user is already logged in using `$_SESSION['admin']`.
    *   When an admin registers (`inscription.php`), passwords are cryptographically hashed (e.g., `password_hash()`) before entering the database.
    *   During login (`login.php`), the system verifies the password against the hash using `password_verify()`.
    *   `session_start()` initiates the session to keep the user authenticated across different protected pages.
    *   **SQL Injection Prevention:** Input data is sanitized using `mysqli_real_escape_string()` before being queried.

## Step 4: Routing and Layout
*   **Core Files:** `menu.php`, `css/style.css`.
*   **Structure:** `menu.php` contains the navigation template and layout structure. It is included on every protected page to ensure a consistent interface, preventing code duplication (DRY principle).

## Step 5: CRUD Operations (Core Functionality)
The application manages data through Create, Read, Update, and Delete operations. 

**Example: Managing Projects**
1.  **READ (`projets.php`):** Connects to the database, executes `SELECT * FROM projets`, and iterates through the results using a `while` loop to display them dynamically.
2.  **CREATE (`ajouter_projet.php`):** Provides an HTML form. On submission, it captures POST data and executes an `INSERT INTO` SQL query to add a new record.
3.  **UPDATE (`modifier_projet.php`):** Retrieves existing project data via a `GET` parameter (ID), populates it inside a form, and executes an `UPDATE` query upon submission.
4.  **DELETE (`supprimer_projet.php`):** Intercepts an ID via the URL and executes a `DELETE FROM` query to remove the specific record.

*(These exact same CRUD principles apply to the `certificats` entity as well.)*

## Step 6: Live Demonstration Plan
1.  **Database View:** Show the structure of the database tables in PhpMyAdmin.
2.  **Failed Login test:** Start at the login page (`login.php`) and enter an incorrect password to demonstrate error handling.
3.  **Successful Login:** Log in with correct credentials to reach the dashboard (`index.php`).
4.  **Creation:** Navigate to the Projects/Certificates page and add a new test entry.
5.  **Modification:** Edit the newly created entry to demonstrate the UPDATE functionality.
6.  **Deletion:** Delete the entry to demonstrate the DELETE functionality.
7.  **Logout:** Terminate the session and show the redirection back to the login page.