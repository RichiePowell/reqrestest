<?php

namespace RichiePowell\UserManagement;

use Generator;

class UserPaginator implements \IteratorAggregate
{
    private UserService $userService;
    private int $currentPage = 1;
    private bool $hasMoreResults = true;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieve the next page of users as a Generator.
     *
     * @return Generator|User[]
     */
    public function getNextPage(): Generator
    {
        if ($this->hasMoreResults) {
            $users = $this->userService->getUsers($this->currentPage);

            if (count($users) === 0) {
                $this->hasMoreResults = false;
            } else {
                $this->currentPage++;

                foreach ($users as $user) {
                    yield $user;
                }
            }
        }
    }

    /**
     * Determine if there are more results to return.
     *
     * @return bool
     */
    public function hasMoreResults(): bool
    {
        return $this->hasMoreResults;
    }

    /**
     * Get an iterator for the UserPaginator.
     *
     * @return Generator|User[]
     */
    public function getIterator(): Generator
    {
        while ($this->hasMoreResults) {
            foreach ($this->getNextPage() as $user) {
                yield $user;
            }
        }
    }
}