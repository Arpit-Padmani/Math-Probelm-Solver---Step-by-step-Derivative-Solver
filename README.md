
# Math Problem Solver – Step-by-step Derivative Solver

This is a web-based application that allows users to input mathematical expressions and get step-by-step solutions for derivatives. It includes a user-friendly dashboard and a secure login system.

## 🔍 Features

- 🧮 **Step-by-step Derivative Solving**  
  Automatically solves derivatives and provides a breakdown of the solution.

- 👤 **User Authentication System**  
  Secure login, logout, and registration functionality.

- 📊 **User Dashboard**  
  Personalized dashboard to manage and view solved problems.

- 🎨 **Simple UI**  
  Clean and minimal interface using custom CSS.

## 📂 Project Structure
```
📁 Math-Problem-Solver
├── index.php                # Home page
├── dashboard.php            # User dashboard
├── signup.php               # Sign-up page
├── style.css                # Main stylesheet
├── user-icon.png            # User icon image
└── auth/                    # Authentication and database logic
    ├── db.php               # Database connection
    ├── insert_expression.php # Handles insertion of expressions
    ├── login.php            # Login script
    ├── logout.php           # Logout script
    └── register.php         # Registration script
```




## ⚙️ Technologies Used

- **Frontend**: HTML, CSS
- **Backend**: PHP
- **Database**: MySQL

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/Arpit-Padmani/Math-Probelm-Solver.git
cd Math-Probelm-Solver
````

### 2. Set Up Database

* Create a MySQL database.
* Import the SQL script from the `auth/db.php` file or your SQL backup.

### 3. Configure Database

Edit `auth/db.php` with your local database credentials:

```php
$conn = mysqli_connect("localhost", "your_username", "your_password", "your_database");
```

### 4. Run the Project

* Deploy on a local server using XAMPP, WAMP, or any PHP server.
* Access the project via `http://localhost/Math-Probelm-Solver/index.php`.

## 🛡️ License

This project is open-source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

**Developed by Arpit Padmai**
