<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Models\UserCommon;
use App\Models\UserShopkeeper;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @internal
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testFindByEmailReturnNull()
    {
        $repository = new UserRepository;

        $this->assertNull($repository->findByEmail('not-found'));

    }

    public function testFindByEmailReturnUserShopkeeper()
    {
        $this->seed();

        $repository = new UserRepository;

        $this->assertInstanceOf(UserShopkeeper::class, $repository->findByEmail('lojista@gmail.com'));

    }

    public function testFindByEmailReturnUserCommon()
    {
        $this->seed();

        $repository = new UserRepository;

        $this->assertInstanceOf(UserCommon::class, $repository->findByEmail('user-comum@gmail.com'));

    }

    public function testFindByEmailReturnUserWithTypeNotDefined()
    {
        User::create([
            'name' => 'user with type not defined',
            'type' => 3,
            'indentify_register' => 12345678912,
            'email' => 'user-with-type-not-defined@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $repository = new UserRepository;

        $this->assertNull($repository->findByEmail('user-with-type-not-defined@gmail.com'));

    }

}
