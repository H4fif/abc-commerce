<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = new \App\Models\User;
        $administrator->username = 'administrator';
        $administrator->name = 'Site Administrator';
        $administrator->email = 'admin@abc-commerce.com';
        $administrator->roles = json_encode(['admin']);
        $administrator->password = \Hash::make('admin');
        $administrator->avatar = 'unavailable.png';
        $administrator->address = 'Bandung';
        $administrator->save();
        $this->command->info('Admin user successfully created.');
    }
}
