<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_empty_input()
    {
        $response = $this->postJson('/api/register');
        $response->assertStatus(422)->assertJsonStructure(['message']);
    }

    public function test_invalid_input()
    {
        $data = [
            'email' => $this->faker->name,
            'password' => $this->faker->password,
        ];

        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(422)->assertJsonStructure(['message']);
    }

    public function test_register_with_success()
    {

        $formData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password(8)),
            'device_name' => 'device',
        ];


        $response = $this->postJson('/api/register', $formData);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'name', 'email', 'created_at'])
            ->assertJson(['email' => $formData['email'], 'name' => $formData['name']]);
    }


    public function test_user_already_registered()
    {
        $formData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8),
            'device_name'=> "device",

        ];

        $this->postJson('/api/register', $formData);

        $response = $this->postJson('/api/register', $formData);

        $response->assertStatus(409)
            ->assertJsonStructure(['message']);
    }
}
