
#  MyPass PHP

A secure and simple web-based password manager written in plain PHP.  
No frameworks, minimal setup — just JSON, SQLite/MySQL, and design patterns.

---

##  Features

- User Registration & Login (with secure password hashing)
- Store sensitive data: logins, notes, cards
- Filter and search stored vault items
- Password masking with toggle visibility
- Add,  Edit, and  Delete items
- CSRF protection on forms
- Flash message system for success/error feedback
- Clean UI using pure HTML, CSS, and vanilla JS
- Password notes field for each entry

---

##  Implemented Design Patterns

### 1. Singleton
- Ensures a single instance of `SessionManager` manages all session operations securely.

### 2.  Builder Pattern – Password Generator
- A **PasswordDirector** and **PasswordBuilder** dynamically generate strong passwords.
- Users can configure:
  - Length (8–32)
  - Include Uppercase
  - Include Lowercase
  - Include Numbers
  - Include Symbols
- Uses JavaScript UI + `generate_password.php` backend for instant autofill.

### 3. Observer Pattern – Vault Alerts
- On saving a new item, `VaultSubject` notifies:
  - `WeakPasswordObserver` — warns about weak passwords (e.g., "123", "< 8 chars").
  - `ExpirationObserver` — (placeholder logic for now) notifies about expiring credentials.
- Future support for visual or email alerts.

### 4. Proxy Pattern – Secure Vault Access Control
-Implements controlled access to sensitive vault actions.
-Files added:
  - `VaultInterface.php`
  - `VaultAccessProxy.php`

### 5. Mediator Pattern – Centralized Form Validation
- Removes scattered validation throughout the form and centralizes the logic.
- Files added:
  - `Mediator.php`
  - `FormComponent.php`
  - `TitleComponent.php`
  - `PasswordComponent.php`
This pattern lets each validator communicate only through a central Mediator.

`Mediator handles`:
  - Empty title checks
  - Password formatting rules
  - Cross-field coordination if needed
  - All validation messages are returned together and displayed on screen.

### 6. Chain of Responsibility – Multi-Level Password Strength Checking

- Implements multiple password rule checks, each handled by an independent "handler".

- Files added:
  `BaseHandler.php`
  `LengthHandler.php`
  `NumberHandler.php`
  `UppercaseHandler.php`
  `SymbolHandler.php`

- The chain is built in order:
  `$length->setNext($number)->setNext($upper)->setNext($symbol);`

- This means:
 - LengthHandler → checks 8+ characters
  - NumberHandler → checks at least one digit
  - UppercaseHandler → checks capital letters
  - SymbolHandler → checks special characters

- If one fails, the chain stops and returns the first error:
  - Examples:
    - “Chain: Password must be at least 8 characters.”
    - “Chain: Password must include a number.”
    - “Chain: Password must contain a special symbol.”

- Used as an additional validation layer to enforce password security.
---

##  How to Run the App

### 1. Folder Setup
Place the project inside your local server directory:

C:/xampp/htdocs/MyPassProject/

Or for WAMP/MAMP/etc., use the appropriate `www/` folder.
2. Import database:

Open phpMyAdmin → create database → import schema.sql.

3. Start server:

Start Apache and MySQL through XAMPP.

4. Open app:
http://localhost/MyPassProject/src/register.php

HINT:
###  Database Setup
Run the SQL file provided (if any) to create the required table:

```sql
CREATE TABLE vault_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    type VARCHAR(50),
    title VARCHAR(100),
    username VARCHAR(100),
    password VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
tHEN  Register and Login

Open http://localhost/MyPassPHP/register.php

Then log in via http://localhost/MyPassPHP/login.php

4. Add Items

Use the Dashboard to add new vault entries, test the password generator, and see Observer alerts on weak/expiring data.

Folder Structure: 

MyPassProject/
├── src/
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── logout.php
│   ├── edit_item.php
│   ├── update_item.php
│   ├── delete_item.php
│   ├── generate_password.php
│   ├── assets/
│   │   ├── styles.css
│   │   └── script.js
│   └── utils/
│       ├── db.php
│       ├── SessionManager.php
│       ├── encryption.php
│       └── patterns/
│           ├── builder/
│           │   ├── PasswordBuilder.php
│           │   └── PasswordDirector.php
│           ├── observer/
│           │   ├── Observer.php
│           │   ├── Subject.php
│           │   ├── VaultObserver.php
│           │   ├── VaultSubject.php
│           │   ├── WeakPasswordObserver.php
│           │   └── ExpirationObserver.php
│           ├── Proxy/
│           │   ├── VaultInterface.php
│           │   └── VaultAccessProxy.php
│           ├── mediator/
│           │   ├── Mediator.php
│           │   ├── FormComponent.php
│           │   ├── TitleComponent.php
│           │   └── PasswordComponent.php
│           └── Chain/
│               ├── BaseHandler.php
│               ├── LengthHandler.php
│               ├── NumberHandler.php
│               ├── UppercaseHandler.php
│               └── SymbolHandler.php
├── db/
│   └── schema.sql
└── README.md
