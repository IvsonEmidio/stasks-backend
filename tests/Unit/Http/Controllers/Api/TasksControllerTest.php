<?php

namespace Http\Controllers\Api;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TasksControllerTest extends TestCase
{

    /**
     * Test the 'get' method on 'tasks' route
     * @return void
     */
    public function testGet(): void
    {
        //Get json
        $response = $this->getJson('http://127.0.0.1:8000/api/tasks');
        //Do some assertions
        $response
            ->assertJson(function (AssertableJson $json) {
                return $json->where('status', 1)
                    ->where('msg', "All data retrieved")
                    ->has('data.0', function ($json) {
                        return $json->whereType('id', 'integer')
                            ->whereType('creator_id', 'integer')
                            ->whereType('title', 'string')
                            ->whereType('note', 'string')
                            ->whereType('date', 'string')
                            ->whereType('time', 'string')
                            ->wheretype('status', 'integer')
                            ->etc();
                    });
            });
    }

    /**
     * Test the 'post' method on 'tasks' route
     * @return void
     */
    public function testCreate(): void
    {
        //Do request...
        $response = $this->post('http://127.0.0.1:8000/api/tasks', [
            'title' => 'buy new clothes',
            'note' => 'need to be blue',
            'date' => '2022/04/31',
            'time' => '17:00'
        ]);

        //Assert the incoming JSON.
        $response->assertJson(function (AssertableJson $json) {
            return $json->where('status', 1)
                ->where('msg', 'A new record has successfully created')
                ->has('data', function ($json) {
                    return $json
                        ->whereType('creator_id', 'integer')
                        ->whereType('id', 'integer')
                        ->whereType('title', 'string')
                        ->whereType('note', 'string')
                        ->whereType('date', 'string')
                        ->whereType('time', 'string')
                        ->wheretype('status', 'integer')
                        ->whereType('updated_at', 'string')
                        ->whereType('created_at', 'string')
                        ->etc();
                });
        });
    }

    /**
     * Test the 'delete' method on 'tasks' route
     * @return void
     */
    public function testCancel(): void
    {
        //Cancel the selected task...
        $response = $this->delete('http://127.0.0.1:8000/api/tasks?id=3');

        //Do some assertions on received JSON.
        $response->assertJson(function (AssertableJson $json) {
            return $json->where('status', 1)
                ->where('msg', 'Task successfully canceled')
                ->has('data', function ($json) {
                    return $json
                        ->whereType('creator_id', 'integer')
                        ->whereType('id', 'integer')
                        ->whereType('title', 'string')
                        ->whereType('note', 'string')
                        ->whereType('date', 'string')
                        ->whereType('time', 'string')
                        ->wheretype('status', 'integer')
                        ->whereType('updated_at', 'string')
                        ->whereType('created_at', 'string')
                        ->where('status', 0);
                });
        });


    }

    /**
     * Test the 'put' method on 'tasks' route
     * @return void
     */
    public function testUpdate(): void
    {
        //Request update...
        $response = $this->put('http://127.0.0.1:8000/api/tasks', [
            'id' => 21,
            'title' => 'buy new clothes',
            'note' => 'need to be blue',
            'date' => '2022/04/31',
            'time' => '17:00'
        ]);

        //Do some assertions on received JSON.
        $response->assertJson(function (AssertableJson $json) {
            return $json->where('status', 1)
                ->where('msg', 'All fields updated successfully')
                ->has('data', function ($json) {
                    return $json
                        ->whereType('creator_id', 'integer')
                        ->whereType('id', 'integer')
                        ->whereType('title', 'string')
                        ->whereType('note', 'string')
                        ->whereType('date', 'string')
                        ->whereType('time', 'string')
                        ->wheretype('status', 'integer')
                        ->whereType('updated_at', 'string')
                        ->whereType('created_at', 'string');
                });
        });

    }
}
