<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'le.thi.be@sun-asterisk.com',
            'password' => bcrypt('123456')
        ]);

        $user->assignRole('admin');

        for ($i = 0;$i < 2; $i++) {
            $user = factory(User::class)->create([
                'name' => 'meber'.$i,
                'email' => 'le.thi.be-'.$i.'@sun-asterisk.com',
                'password' => '123456'
            ]);

            $user->assignRole('member');
        }
    }
}
