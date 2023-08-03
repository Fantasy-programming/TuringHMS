<?php

// show page if get request

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once 'Views/auth/view.user.register.php';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];


    // Perform form validation
    $errors = array();

    if (empty($firstName) || empty($lastName) | empty($email) || empty($age) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    // if ($password !== $confirmPassword) {
    //     $errors[] = "Passwords do not match.";
    // }

    if (strlen($firstName) > 30) {
        $errors[] = "First name should be under 30 characters.";
    }

    if (strlen($lastName) > 30) {
        $errors[] = "Last name should be under 30 characters.";
    }


    // if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
    //     $errors[] = "First name should not contain special characters.";
    // }

    // if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
    //     $errors[] = "Last name should not contain special characters.";
    // }

    // if (!preg_match("/^[a-zA-Z0-9]*$/", $userName)) {
    //     $errors[] = "Username should not contain special characters.";
    // }

    // if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
    //     $errors[] = "Password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    // }

    if ($age < 14 || $age > 130) {
        $errors[] = "Age should be over 14 and under 130.";
    }

    if (count($errors) > 0) {

        // Display errors and redirect back to the signup page
        $errorString = implode("<br>", $errors);
        header("Location: /signup?error=$errorString");
        exit();
    }

    // Form validation passed, proceed with signup

    // Hash the password before storing it in the database

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database connection

    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $database = "medicaldb";


    $connection = new mysqli($host, $dbUsername, $dbPassword, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Insert the user data into the patient table
    $insertQuery = "INSERT INTO patients (first_name, last_name, email, age, role, password) VALUES ('$firstName', '$lastName', '$email', $age, '$role', '$hashedPassword')";

    if ($connection->query($insertQuery) === false) {
        die("Error inserting data into table: " . $connection->error);
    }

    // Close the database connection
    $connection->close();

    // Redirect to a success page or wherever you want the user to go after signup
    header("Location: /login?success=1");
    exit();
}
