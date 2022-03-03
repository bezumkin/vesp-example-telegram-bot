<?php

use App\Models\Vehicle;
use Faker\Factory;
use Faker\Provider\Fakecar;
use Phinx\Seed\AbstractSeed;

class Vehicles extends AbstractSeed
{
    public function run(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Fakecar($faker));

        for ($i = 0; $i < 1000; $i++) {
            $vehicle = [
                'brand' => $faker->vehicleBrand,
                'model' => $faker->vehicleModel,
                'type' => $faker->vehicleType,
                'fuel' => $faker->vehicleFuelType,
                'doors' => $faker->vehicleDoorCount,
                'seats' => $faker->vehicleSeatCount,
                'gearbox' => $faker->vehicleGearBoxType,
                'properties' => $faker->vehicleProperties ?: null,
            ];

            Vehicle::query()->create($vehicle);
        }
    }
}