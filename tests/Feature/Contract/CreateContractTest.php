<?php

namespace Tests\Feature\Contract;

use Illuminate\Foundation\Testing\DatabaseMigrations;
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

    protected function tableRoute()
    {
        return 'profile/my-contract';
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

    protected function getCreator()
    {
        return "0x858621C069DeEB1b1c69cAfA1f4A550dcFb734eb";
    }

    protected function getIRC()
    {
        return "0x5e8D72d05E7683b959741FA3858deD5C980506EF";
    }

    public function testGuestCannotViewAProfile()
    {
        $response = $this->get($this->contractRoute());

        $response->assertRedirect($this->guestMiddlewareRoute());
    }

    public function testUserCanViewAProfile()
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
            'id' => '1',
        ]);
        $response->assertSuccessful();
        $response->assertSee('id=\"fields\"', false);
    }

    public function testCheckFieldsTemplateFail()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->post($this->checkPostRoute(), [
            'address' => '444',
        ]);
        $response->assertSuccessful();
        $response->assertJsonFragment(['ret_status'=>'not ok']);
    }

    public function testCheckFieldsTemplateTrue()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->post($this->checkPostRoute(), [
            'address' => $this->getCreator(),
        ]);
        $response->assertSuccessful();
        $response->assertJsonFragment(['ret_status'=>'ok']);
    }

    public function testCreateContract()
    {
        $user = factory(User::class)->create([
            'id' => 1,
        ]);

        $response = $this->actingAs($user)->post($this->createPostRoute(), [
            'creator' => $this->getCreator(),
            'IRC_contract' => $this->getIRC(),
            'template_id' => '1',
            'test_user_id' => $user->id
        ]);
        $response->assertSuccessful();
        $response->assertJsonFragment(['ret_status'=>'ok']);
    }

    public function testGetIdContract()
    {
        $user = factory(User::class)->create([
            'id' => 1,
        ]);

        $response = $this->actingAs($user)->post($this->createPostRoute(), [
            'creator' => $this->getCreator(),
            'IRC_contract' => $this->getIRC(),
            'template_id' => '1',
            'test_user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->post($this->createPostRoute(), [
            'creator' => $this->getCreator(),
            'IRC_contract' => $this->getIRC(),
            'template_id' => '1',
            'test_user_id' => $user->id
        ]);
        $response->assertSuccessful();
        $response->assertSee('contract_id',false);
    }

    public function testGetContractTable()
    {
        $user = factory(User::class)->create([
            'id' => 1,
        ]);

        $response = $this->actingAs($user)->post($this->createPostRoute(), [
            'creator' => $this->getCreator(),
            'IRC_contract' => $this->getIRC(),
            'template_id' => '1',
            'test_user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->post($this->contractsPostRoute(), [
            'test_user_id' => $user->id
        ]);
        $response->assertSuccessful();
        $response->assertJsonFragment(['ret_status'=>'ok']);
        $response->assertSee('contracts_view',false);
    }

    public function testGetContractGuide()
    {
        $user = factory(User::class)->create([
            'id' => 1,
        ]);

        $response = $this->actingAs($user)->post($this->createPostRoute(), [
            'creator' => $this->getCreator(),
            'IRC_contract' => $this->getIRC(),
            'template_id' => '1',
            'test_user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get($this->tableRoute());
        $response->assertSuccessful();
        $response->assertSee('instruction_form',false);
    }

    public function testReclaimContract()
    {
        $user = factory(User::class)->create([
            'id' => 1,
        ]);

        $response = $this->actingAs($user)->post($this->createPostRoute(), [
            'creator' => $this->getCreator(),
            'IRC_contract' => $this->getIRC(),
            'template_id' => '1',
            'test_user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->post($this->removePostRoute(), [
            'id' => $response->json('contract_id'),
            'test_user_id' => $user->id
        ]);
        $response->assertSuccessful();
    }
}
