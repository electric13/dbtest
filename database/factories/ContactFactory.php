<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

	$faker = \Faker\Factory::create();
	// generate 3 random colors

	$colors = collect(range(1, 3))->map(function() use ($faker) {
	    return $faker->colorName;
	})->toArray();
	
	return [
	    'name' => $faker->name,
	    'phone' => $faker->e164PhoneNumber,
	    'address' => $faker->address,
	    'favorites' => ['colors' => $colors],
	];
    }
}
