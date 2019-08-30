<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->user();
        $this->program();
        $this->options();
        $this->call(FeeSeeder::class);
        $this->call(ItemClassSeeder::class);
    }

    public function user()
    {
        $admin = new \App\Models\User();
        $admin->create([
            'name' => 'admin',
            'phone'=> '123456789',
            'email' => 'admin@gmail.com',
            'role'  => \App\Models\User::ROLE_ADMIN,
        ]);

        $user = new \App\Models\UserLogin();
        $user->create([
            'type' => 1,
            'user_id'   => 1,
            'loginId' => 'admin@gmail.com',
            'password'  => bcrypt('admin123'),
        ]);
    }
    public function program()
    {
        $programs = [
            [
                'name'  => 'G0',
                'time'  => '0',
                'fee'   => '1000000'
            ],
            [
                'name'  => 'G1',
                'time'  => '0',
                'fee'   => '2000000'
            ],
            [
                'name'  => 'G2',
                'time'  => 16,
                'fee'   => '2500000'
            ],
            [
                'name'  => 'G3',
                'time'  => 16,
                'fee'   => '3000000'
            ]
        ];
        $program = new \App\Models\Program();
        $program->insert($programs);
    }
    public function options()
    {
        $option = new \App\Models\Options();
        $data = [
            [
                'key'   => 'ticket_price_in_hours',
                'value' => 150000
            ],
            [
                'key'   => 'ticket_price_out_hours',
                'value' => 200000
            ]
        ];

        $option->insert($data);
    }
}
