<?php

namespace Tests\Unit;

use App\Model\Label;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LabelApiTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public $labelName;

    public function setUp(): void
    {
        parent::setUp();
        $user = factory(\App\User::class)->create();
        $user->save();
        $this->user = $user;
        $this->labelName = Str::random(10);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->createLabel();
        $this->listLabel();
        $this->showLabel();
        $this->updateLabel();
        $this->removeLabel();
        $this->user->delete();
    }

    public function createLabel()
    {
        $postData = [
            'name' => $this->labelName
        ];
        $postResponse = $this->json('POST', route('api_label.store'), $postData, ['Authorization' => 'Bearer ' . $this->user->token]);
        $postResponse->assertStatus(200);
    }

    public function listLabel()
    {
        $labelsCount = $this->count(Label::all());
        $response = $this->json('GET', route('api_label.index'), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals($labelsCount, $this->count($response['data']));
    }

    public function showLabel()
    {
        $labelsCount = $this->count(Label::all());
        $response = $this->json('GET', route('api_label.show', Label::all()->first()['id']), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

    public function updateLabel()
    {
        $labelsCount = $this->count(Label::all());
        $response = $this->json('PUT', route('api_label.update', Label::all()->first()['id']), ['name' => Str::random(10)], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

    public function removeLabel()
    {
        $labelsCount = $this->count(Label::all());
        $response = $this->json('DELETE', route('api_label.destroy', Label::all()->first()['id']), [], ['Authorization' => 'Bearer ' . $this->user->token])->decodeResponseJson();
        $this->assertEquals(true, $response['success']);
    }

}
