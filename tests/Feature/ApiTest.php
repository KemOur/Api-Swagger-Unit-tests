<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /*------[CREATE-TASK]-----*/
    public function test_create_task()
    {
        $user = $this->createUser();

        $task = [
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true)
        ];
        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->postJson('/api/tasks', $task);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'task' => ["id", "user_id", "body", "created_at", "updated_at", "completed"]
            ]);
    }

    public function test_invalid_token()
    {
        $task = [
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true)
        ];
        $response = $this->postJson('/api/tasks', $task);
        $response->assertStatus(401)->assertJsonStructure([
            "status",
            "message"
        ]);
    }


    /*------[DELETE-TASK]-----*/
    public function test_delete_invalid_token()
    {
        $response = $this->deleteJson('/api/tasks/1');
        $response->assertStatus(401)->assertJsonStructure(['status', 'message']);
    }

    public function test_task_do_not_exist()
    {
        $user = $this->createUser();


        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->deleteJson('/api/tasks/1');

        $response->assertStatus(404)->assertJsonStructure(['status', 'message']);
    }

    public function test_unauthorized_delete_task()
    {
        $user = $this->createUser();
        $user2 = $this->createUser();


        $this->createTask($user2['data']->id, 3);

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->deleteJson('/api/tasks/1');

        $response->assertStatus(403)->assertJsonStructure(['status', 'message']);
    }

    public function test_delete_task_with_success()
    {
        $user = $this->createUser();

        $this->createTask($user['data']->id, 3);


        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->deleteJson('/api/tasks/1');

        $response->assertStatus(200)->assertJsonStructure([
            'status',
            'task' => [
                'id',
                'body',
                'user_id',
                'created_at',
                'updated_at',
                'completed',
            ]
        ]);
    }

    /*--------[UPDATE-TASK]--------*/
    public function test_update_invalid_token()
    {
        $task = [
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
        ];

        $response = $this->putJson('/api/tasks/1', $task);
        $response->assertStatus(401)->assertJsonStructure(['status', 'message']);
    }

    public function test_update_task_do_not_exist()
    {
        $user = $this->createUser();

        $task = [
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->putJson('/api/tasks/1', $task);

        $response->assertStatus(404)->assertJsonStructure(['status', 'message']);
    }

    public function test_unauthorized_edit_task()
    {
        $user = $this->createUser();
        $user2 = $this->createUser();

        $this->createTask($user2['data']->id, 3);

        $task = [
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->putJson('/api/tasks/1', $task);

        $response->assertStatus(403)->assertJsonStructure(['status', 'message']);
    }

    public function test_empty_inputs()
    {
        $user = $this->createUser();

        $this->createTask($user['data']->id, 1);

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->putJson('/api/tasks/1');

        $response->assertStatus(200)->assertJsonStructure([
            'status',
            'task' => [
                'id',
                'body',
                'user_id',
                'created_at',
                'updated_at',
                'completed',
            ]
        ]);
    }

    public function test_update_invalid_input()
    {
        $user = $this->createUser();

        $this->createTask($user['data']->id, 1);

        $task = [
            'body' => true,
            'completed' => 'false'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->putJson('/api/tasks/1', $task);

        $response->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }

    public function test_update_task_with_success()
    {
        $user = $this->createUser();

        $this->createTask($user['data']->id, 3);

        $task = [
            'body' => $this->faker->sentence($nbWords = 20, $variableNbWords = true),
            'completed' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->putJson('/api/tasks/1', $task);

        $response->assertStatus(200)->assertJsonStructure([
            'status',
            'task' => [
                'id',
                'body',
                'user_id',
                'created_at',
                'updated_at',
                'completed',
            ]
        ]);
    }

    /*--------[TASKS]------- */
    public function test_task_invalid_token()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401)->assertJsonStructure(['status', 'message']);
    }

    public function test_get_all_tasks()
    {
        $user = $this->createUser();

        $this->createTask($user['data']['id'], 3);

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'tasks' => [
                    [
                        'id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at',
                        'completed',
                    ]
                ]
            ]);
    }

    public function test_get_all_tasks_by_completed()
    {
        $user = $this->createUser();

        $this->createTask($user['data']['id'], 3);

        $response = $this->withHeader('Authorization', 'Bearer ' . $user['token'])
        ->getJson('/api/tasks?completed=true');


        $contentResponse = json_decode($response->getContent(), true);

        foreach ($contentResponse['tasks'] as $task) {
            if ($task['completed'] != true) $this->fail('the task is not in "completed"');
        }
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'tasks' => [
                    [
                        'id',
                        'user_id',
                        'body',
                        'created_at',
                        'updated_at',
                        'completed',
                    ]
                ]
            ]);
    }
}
