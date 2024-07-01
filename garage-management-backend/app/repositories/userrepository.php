<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository
{
    // Check the provided email and password
    function checkEmailPassword($email, $password)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            if (!$user || !$this->verifyPassword($password, $user->password)) {
                return false;
            }

            // Remove the password hash before returning the user object
            $user->password = "";
            return $user;
        } catch (PDOException $e) {
            // It's generally a good idea to handle exceptions more gracefully
            // This could be logging to a file or another storage mechanism
            error_log('Failed to check email and password: ' . $e->getMessage());
            return false;
        }
    }

    // Verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    // Insert a new user into the database
    function insert($user)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO users (firstname, lastname, email, password, user_role) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $user->firstname,
                $user->lastname,
                $user->email,
                $this->hashPassword($user->password),
                $user->user_role
            ]);
            if ($result) {
                $user->id = $this->connection->lastInsertId();
                $user->password = "";
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Failed to insert user: ' . $e->getMessage());
            return false;
        }
    }

    // Check if an email already exists in the database
    function emailExists($email)
    {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Failed to check if email exists: ' . $e->getMessage());
            return false;
        }
    }

    // Hash the password (using bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
