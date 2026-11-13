<?php

namespace Database\Factories;

use App\Models\currencies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<currencies>
 */
class currenciesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public function configure()
    {
        return $this->sequence(
            [
                'name' => 'US Dollar',
                'symbol' => '$',
                'code' => 'USD'
            ],
            [
                'name' => 'Rupiah',
                'symbol' => 'Rp',
                'code' => 'RP'
            ],
            [
                'name' => 'Yen',
                'symbol' => 'Y',
                'code' => 'Y'
            ]
        );
    }
}
