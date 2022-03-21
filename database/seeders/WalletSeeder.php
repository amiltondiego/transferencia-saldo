<?php

namespace Database\Seeders;

use App\Models\WalletType;
use App\Repositories\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallets')->insert([
            'payer_id' => $this->userRepository->findByEmail('user-login@gmail.com')?->id,
            'payee_id' => $this->userRepository->findByEmail('user-comum@gmail.com')?->id,
            'value' => 10000,
            'type' => WalletType::success(),
        ]);

        DB::table('wallets')->insert([
            'payer_id' => $this->userRepository->findByEmail('user-login@gmail.com')?->id,
            'payee_id' => $this->userRepository->findByEmail('lojista@gmail.com')?->id,
            'value' => 1000,
            'type' => WalletType::success(),
        ]);
    }
}
