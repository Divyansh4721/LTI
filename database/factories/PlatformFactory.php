<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PlatformFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'test',
            'issuer' => 'test_issuer',
            'platform_client_id' => "Client1",
            'private_key' => "---Private Key---$!qawsqa@123@#werty",
            'public_key' => "---Public Key---$!qawsqa@123@#werty",
            // 'mgh_client_id' => "Client1",
            'tool_proxy' => "test",
            'jwkseturl' => "test",
            'access_token' => "test",
            'authorization_url' => "test",
            'protected' => 1,
            'enabled' => 1,
            'created_by' => 1
            ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        // return $this->state(fn (array $attributes) => [
        //     'email_verified_at' => null,
        // ]);
    }
}

?>