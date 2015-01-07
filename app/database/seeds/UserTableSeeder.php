<?php

/**
 * Description of UserTableSeeder
 *
 * @author Jozef
 */
class UserTableSeeder extends Seeder {

    public function run() {
        DB::table('users')->delete();
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            User::create(array(
                'name' => $faker->name,
                'surname' => $faker->lastname,
                'login' => $faker->name . $faker->randomDigitNotNull,
                'email' => $faker->email,
                'password' => Hash::make('123456'),
                'confirmed' => 1,
            ));
        }
        User::create(array(
            'name' => 'Jozef',
            'surname' => 'Dúc',
            'email' => 'jozef.d13@gmail.com',
            'password' => Hash::make('123456'),
            'confirmed' => 1,
            'admin' => 1,
            'teacher' => 1,
        ));
    }

}
