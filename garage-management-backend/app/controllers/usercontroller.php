<?php

namespace Controllers;

use Exception;
use Services\UserService;
use Utils\JwtHandler;

class UserController extends Controller
{
    private $userService;
    private $jwtHandler;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->jwtHandler = new JwtHandler();
    }

    public function register()
    {
        $user = $this->createObjectFromPostedJson('Models\\User');
        if ($user === null) {
            $this->respondWithError(400, "Invalid user data provided.");
            return;
        }

        // Basic validation
        if (empty($user->email) || empty($user->password) || empty($user->firstname) || empty($user->lastname)) {
            $this->respondWithError(400, "All fields are required.");
            return;
        }

        // Check if email already exists
        if ($this->userService->emailExists($user->email)) {
            $this->respondWithError(409, "Email already in use.");
            return;
        }

        // Hash password
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);

        // Set default role as guest
        $user->user_role = 'guest';

        // Save user
        $result = $this->userService->createUser($user);
        
        $token = $this->jwtHandler->jwtEncodeData($_SERVER['HTTP_HOST'], ['id' => $user->id, 'email' => $user->email, 'role' => $user->user_role]);

        if ($result) {
            $this->respond([
                'message' => 'Registration successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'email' => $user->email,
                    'role' => $user->user_role
                ]
            ]);
        } else {
            $this->respondWithError(500, "Failed to register user.");
        }
    }

    public function login()
    {
        try {
            $userCredentials = $this->createObjectFromPostedJson('Models\\User');
            $user = $this->userService->authenticate($userCredentials->email, $userCredentials->password);

            if (!$user) {
                $this->respondWithError(401, 'Invalid email or password');
                return;
            }

            $jwtHandler = new JwtHandler();
            $token = $jwtHandler->jwtEncodeData($_SERVER['HTTP_HOST'], ['id' => $user->id, 'email' => $user->email, 'role' => $user->user_role]);

            // Prepare user details to return with response
            $userDetails = [
                'id' => $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'role' => $user->user_role
            ];

            $this->respond([
                'token' => $token,
                'user' => $userDetails
            ]);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
