<?php

namespace Tests\Feature\Contract;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateContractTest extends TestCase
{
    use RefreshDatabase;

    protected function contractRoute()
    {
        return 'profile/create-contract';
    }

    protected function templatesPostRoute()
    {
        return 'contract/get_list_templates';
    }

    protected function templatePostRoute()
    {
        return 'contract/get_template';
    }

    protected function checkPostRoute()
    {
        return 'contract/check_fields';
    }

    protected function createPostRoute()
    {
        return 'contract/create';
    }

    protected function contractsPostRoute()
    {
        return 'contract/get_contracts';
    }

    protected function removePostRoute()
    {
        return 'contract/remove';
    }

    protected function instructionPostRoute()
    {
        return 'contract/get_instruction';
    }

    protected function guestMiddlewareRoute()
    {
        return route('login');
    }

    public function testUserCannotViewAProfileWhenNotAuthenticated()
    {
        $response = $this->get($this->contractRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCanViewAProfileWhenAuthenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get($this->contractRoute());

        $response->assertSuccessful();
        $response->assertViewIs('pages.profile');
    }

    public function testUserCanViewSelectTemplates()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->post($this->templatesPostRoute(), []);

        $response->assertSuccessful();
        $response->assertSee('id=\"select_template\"', false);
    }

    public function testUserCanSelectTemplate()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->post($this->templatePostRoute(), [
            'email' => $user->email,
            'password' => '$password',
            'remember' => 'on',
        ]);
        $response->assertSuccessful();
        $response->assertSee('id=\"fields\"', false);
    }
}
