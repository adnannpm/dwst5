<?php

namespace Database\Factories;

use App\Models\categories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<categories>
 */
class categoriesFactory extends Factory
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
                'name' => 'Outgoing Transfer',
                'icon' => ' ',
                'type' => 'expense',
            ],
            [
                'name' => ' Incoming Transferr',
                'icon' => ' ',
                'type' => 'income',
            ],
            [
                'name' => 'Food',
                'icon' => ' ',
                'type' => 'income',
            ],
            [
                'name' => 'Salary',
                'icon' => ' ',
                'type' => 'expense',
            ],
        );  
    }
}
