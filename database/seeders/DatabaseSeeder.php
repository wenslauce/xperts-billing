<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Pricing;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles and permissions, and the default super-admin
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // --- Test Users ---

        // Create the customer user
        $customerUser = User::firstOrCreate(
            ['email' => 'chengowensy@gmail.com'],
            [
                'name' => 'Wensley Customer',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $customerUser->assignRole('customer');

        // Create a Customer record for them
        Customer::firstOrCreate(
            ['user_id' => $customerUser->id],
            [
                'company_name' => 'Wensley Technologies',
                'phone' => '+254700000000',
                'country' => 'KE',
            ]
        );

        // --- Sample Hosting Products ---

        $starter = Product::firstOrCreate(
            ['slug' => 'starter-hosting'],
            [
                'name' => 'Starter',
                'type' => 'hosting',
                'description' => "100 GB Storage\n10 GB Bandwidth\n1 Website\nFree SSL Certificate",
                'directadmin_package' => 'starter',
                'is_active' => true,
            ]
        );
        Pricing::firstOrCreate(
            ['product_id' => $starter->id, 'billing_cycle' => 'monthly'],
            ['price' => 3.00, 'setup_fee' => 0, 'currency' => 'USD']
        );

        $pro = Product::firstOrCreate(
            ['slug' => 'pro-hosting'],
            [
                'name' => 'Pro Plan',
                'type' => 'hosting',
                'description' => "Unlimited Storage\n50 GB Bandwidth\nUnlimited Websites\nFree SSL + Domain",
                'directadmin_package' => 'pro',
                'is_active' => true,
            ]
        );
        Pricing::firstOrCreate(
            ['product_id' => $pro->id, 'billing_cycle' => 'monthly'],
            ['price' => 15.00, 'setup_fee' => 0, 'currency' => 'USD']
        );

        $business = Product::firstOrCreate(
            ['slug' => 'business-hosting'],
            [
                'name' => 'Business Plan',
                'type' => 'hosting',
                'description' => "Unlimited Storage\n250 GB Bandwidth\nUnlimited Websites\nPriority Support",
                'directadmin_package' => 'business',
                'is_active' => true,
            ]
        );
        Pricing::firstOrCreate(
            ['product_id' => $business->id, 'billing_cycle' => 'monthly'],
            ['price' => 25.00, 'setup_fee' => 0, 'currency' => 'USD']
        );
    }
}