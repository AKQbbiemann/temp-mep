<?php

namespace Database\Factories;

use App\Models\EmployeeChange;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeChangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeChange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randStart = mt_rand(0, 100);
        $start = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->addDays($randStart)->format('Y-m-d');
        if(rand(0,1) == 1){
            $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->addDays(mt_rand($randStart + 1, 120))->format('Y-m-d');
            $change = (0 - mt_rand(100,300)) / 100;
        }else{
            $end = null;
            $change = mt_rand(100,2000) / 100;
        }
        return [
            'start_date' => $start,
            'end_date' => $end,
            'change' => $change,
            'reason' => $this->faker->text(80),
        ];
    }
}
