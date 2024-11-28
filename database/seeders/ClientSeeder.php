<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Crypt;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Client::updateOrInsert(
            [
                'client_id' => 'Client1',
                'name' => 'Client 1 {name}',
                'description' => 'Client 1 {description}'
            ],
        );

    }
}

