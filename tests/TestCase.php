<?php


namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Tasks;
use App\Models\User;
use Faker\Factory;
use FFI;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        Artisan::call('migrate:refresh');
    }

    public function createUser()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8)
        ];

        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password'])
        ]);

        $token = auth()->attempt([
            'email' => $userData['email'],
            'password' => $userData['password']
        ]);

        return ["token" => $token, "data" => $user];
    }

    public function createTask($user_id, $numberGen, $sleepForCheckDate = false)
    {
        for (
            $i = 0;
            $i < $numberGen;
            $i++
        ) {
            $taskData = [
                'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
                'user_id' => $user_id,
                'completed' => true,
            ];
            Tasks::create($taskData);
            if ($sleepForCheckDate) sleep(1);
        }
        return;
    }

}
