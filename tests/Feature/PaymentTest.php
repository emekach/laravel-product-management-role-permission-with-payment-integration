<?php

namespace Tests\Feature;

use Tests\TestCase;


use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function testSuccessfulPayment()
    {
        // Create a user for the test
        $user = User::factory()->create();

        // Use Cashier to make a payment
        $response = $user->charge(1000, 'pm_test_placeholder123');

        // Assert that the payment was successful
        $this->assertTrue($response->successful());
    }
}
