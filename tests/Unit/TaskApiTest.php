<?php

namespace Tests\Unit;

use App\Model\Label;
use App\Model\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public $label;

    public function setUp(): void
    {
        parent::setUp();
        $user = factory(\App\User::class)->create();
        $user->save();
        $this->user = $user;
        $label = factory(\App\Model\Label::class)->create();
        $label->save();
        $this->label = $label;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->createTask();
        $this->listTask();
        $this->showTask();
        $this->updateTask();
        $this->statusTask();
        $this->labelAssignTask();
        $this->removeTask();
        $this->user->delete();
    }

    public function createTask()
    {
        $postData = [
            'title' => Str::random(10),
            'description' => Str::random(20)
        ];
        $postResponse = $this->json('POST', route('api_task.store'), $postData, ['Authorization' => 'Bearer ' . $this->user->token]);
        $postResponse->assertStatus(200);
    }

    public function listTask()
    {
        $tasksCount = $this->count(Label::all());
        $response = $this->json('GET', route('api_task.index'), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals($tasksCount, $this->count($response['data']));
    }

    public function showTask()
    {
        $tasksCount = $this->count(Task::all());
        $response = $this->json('GET', route('api_task.show', Task::all()->first()['id']), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

    public function updateTask()
    {
        $response = $this->json('PUT', route('api_task.update', Task::all()->first()['id']), ['title' => Str::random(10) , 'description' => Str::random(20)], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

    public function removeTask()
    {
        $response = $this->json('DELETE', route('api_task.destroy', Task::all()->first()['id']), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

    public function statusTask()
    {

        $response = $this->json('GET', route('api_taskStatus', Task::all()->first()['id']), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

    public function labelAssignTask()
    {
        $labelsArray = [];
        $labels = Label::all();
        foreach ($labels as $label) {
            $labelsArray[] = $label->id;
        }
        $response = $this->json('GET', route('api_taskStatus', Task::all()->first()['id']), ['labels' => $labelsArray], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

}
