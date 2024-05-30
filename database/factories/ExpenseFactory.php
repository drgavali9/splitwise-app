<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
            'description' => $this->faker->text(),
            'amount'      => $this->faker->randomFloat(),
            'split_type'  => $this->faker->randomNumber(),

            'group_id'     => Group::factory(),
            'paid_user_id' => User::factory(),
        ];
    }
}
