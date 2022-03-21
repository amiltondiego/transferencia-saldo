<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\UserCommon;
use App\Models\UserShopkeeper;
use App\Models\UserType;

class UserRepository
{
    public function findByEmail(string $email): ?UserInterface
    {
        /** @var ?User */
        $findUser = User::where('email', $email)->first();

        if (is_null($findUser)) {
            return null;
        }

        $userType = $findUser->type;

        switch ($userType) {
            case UserType::shopkeeper():
                $user = new UserShopkeeper($findUser->toArray());

                break;
            case UserType::common():
                $user = new UserCommon($findUser->toArray());

                break;
            default:
                $user = null;

                break;
        }

        return $user;
    }
}
