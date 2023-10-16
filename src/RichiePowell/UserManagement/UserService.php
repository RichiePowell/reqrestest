<?php

namespace RichiePowell\UserManagement;

use GuzzleHttp\Client;

class UserService
{
    private Client $httpClient;
    private string $baseUrl = 'https://reqres.in/api/users';

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Retrieve a single user by ID.
     *
     * @param int $userId
     * @return User
     * @throws \Exception if the user is not found
     */
    public function getUserById(int $userId): User
    {
        $url = $this->baseUrl . "/$userId";

        try {
            $response = $this->httpClient->get($url);
            $userData = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200) {
                $name = $userData['data']['first_name'] . ' ' . $userData['data']['last_name'];
                return new User($userData['data']['id'], $name, null);
            } else {
                throw new \Exception("User not found");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error fetching user: " . $e->getMessage());
        }
    }

    /**
     * Retrieve a paginated list of users from Reqres.in API.
     *
     * @param int $page
     * @param int $resultsPerPage
     * @return User[] An array of User objects
     * @throws \Exception if there is an error
     */
    public function getUsers(int $page = 1, int $resultsPerPage = 3): array
    {
        $url = $this->baseUrl . "?page=$page&per_page=$resultsPerPage";

        try {
            $response = $this->httpClient->get($url);
            $usersData = json_decode($response->getBody(), true);
            $users = [];

            if ($response->getStatusCode() === 200) {
                foreach ($usersData['data'] as $userData) {
                    $name = $userData['first_name'] . ' ' . $userData['last_name'];
                    $users[] = new User($userData['id'], $name, null);
                }

                return $users;
            } else {
                throw new \Exception("Error fetching users");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error fetching users: " . $e->getMessage());
        }
    }

    /**
     * Create a new user.
     *
     * @param string $name
     * @param string $job
     * @return User
     * @throws \Exception if there is an error
     */
    public function createUser(string $name, string $job): User
    {
        $data = [
            'name' => $name,
            'job' => $job
        ];

        try {
            $response = $this->httpClient->post($this->baseUrl, ['json' => $data]);
            $userData = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 201) {
                return new User($userData['id'], $userData['name'], $userData['job']);
            } else {
                throw new \Exception("Error creating a new user");
            }
        } catch (\Exception $e) {
            throw new \Exception("Error creating a new user: " . $e->getMessage());
        }
    }
}