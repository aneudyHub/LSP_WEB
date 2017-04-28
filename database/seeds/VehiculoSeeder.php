<?php

use Illuminate\Database\Seeder;
use App\Fabricante;
use App\Vehiculo;
use Faker\Factory as Faker;

class VehiculoSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $cantidad = Fabricante::all()->count();

        for($i = 0; $i < $cantidad; $i++)
        {
            Vehiculo::create
            ([
                'color'         => $faker->safeColorName(),
                'cilindraje'    => $faker->randomFloat(4, 1, 4),
                'potencia'      => $faker->randomNumber(),
                'peso'          => $faker->randomFloat(4, 1, 4),
                'fabricante_id' => $faker->numberBetween(1,$cantidad)
            ]);
        }
    }

}
