<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        User::updateOrInsert(
            ['username' => 'VinodHarti'],[
            'first_name' => 'Vinod',
            'last_name' => 'Harti',
            'email' => Hash::make('vinod.harti@learningmate.com'),
            'password' => Hash::make('pvt9986350404'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'billwanathpal'],[
            'first_name' => 'Billwa Nath',
            'last_name' => 'Pal',
            'email' => Hash::make('billwanath.pal@learningmate.com'),
            'password' => Hash::make('P@$$w0rd'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'sunild'],[
            'first_name' => 'Sunil',
            'last_name' => 'Dhakad',
            'email' => Hash::make('sunil.dhakad@learningmate.com'),
            'password' => Hash::make('S@123456'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'Admin'],[
            'first_name' => 'Admin',
            'last_name' => 'LTI',
            'email' => Hash::make('admin_lti@learningmate.com'),
            'password' => Hash::make('Admin@LTI'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'Admin2'],[
            'first_name' => 'Admin2',
            'last_name' => 'LTI',
            'email' => Hash::make('admin2@learningmate.com'),
            'password' => Hash::make('Admin2@LTI'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'mghltiapp'],[
            'first_name' => 'MC',
            'last_name' => 'Hill',
            'email' => Hash::make('kellsee.chu@mheducation.com'),
            'password' => Hash::make('K@mh$tion23'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'silverchair'],[
            'first_name' => 'Silver',
            'last_name' => 'chair',
            'email' => Hash::make('rbritton@silverchair.com'),
            'password' => Hash::make('S@chair$ttion23'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'prathamesh.surve'],[
            'first_name' => 'Prathamesh',
            'last_name' => 'Surve',
            'email' => Hash::make('prathamesh.surve@learningmate.com'),
            'password' => Hash::make('P@$$w0rd'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'rutik.kapadnis'],[
            'first_name' => 'Rutik',
            'last_name' => 'Kapadnis',
            'email' => Hash::make('rutik.kapadnis@learningmate.com'),
            'password' => Hash::make('P@$$w0rd'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'pranali.sonare'],[
            'first_name' => 'Pranali',
            'last_name' => 'Sonare',
            'email' => Hash::make('pranali.sonare@learningmate.com'),
            'password' => Hash::make('P@$$w0rd'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'lti_cybersec'],[
            'first_name' => 'LTI Cyber',
            'last_name' => 'Security',
            'email' => Hash::make('svc-whitehat-scanner@mheducation.com'),
            'password' => Hash::make('m4rk@1xjtklm9uk'),
            'status' => 'active',
            ],
        );

        User::updateOrInsert(
            ['username' => 'aditya.sarkar'],[
            'first_name' => 'Aditya',
            'last_name' => 'Sarkar',
            'email' => Hash::make('aditya.sarkar@learningmate.com'),
            'password' => Hash::make('Slick50!'),
            'status' => 'active',
            ],
        );
    }
}

