<?php

namespace Http\Controllers\Api;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TasksControllerTest extends TestCase
{

    public function testGet()
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
                    }
                    );
            }
            );
    }

    public function testCreate()
    {
        //Request create new task
        $response = $this->post('http://127.0.0.1:8000/api/tasks', [
            'title' => 'buy new clothes',
            'note' => 'need to be blue',
            'date' => '2022/04/31',
            'time' => '17:00'
        ]);

        //Assert statuses
        $response->assertJson(function (AssertableJson $json) {
            return $json->where('status', 1)
                ->where('msg', 'A new record has successfully created')->etc();
        });
    }

    public function testCancel()
    {
        $response = $this->delete('http://127.0.0.1:8000/api/tasks?id=3');

        $response->assertJson(function (AssertableJson $json) {
            return $json->where('status', 1)
                ->where('msg', 'Task successfully canceled')->etc();
        });
    }

}
