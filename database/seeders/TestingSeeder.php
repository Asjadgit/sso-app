<?php

namespace Database\Seeders;

use App\Models\CentralUser;
use App\Models\User;
use App\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = CentralUser::create([
        //     'global_id' => 'acme',
        //     'name' => 'example',
        //     'email' => 'example@test.com',
        //     'password' => Hash::make('12345678')
        // ]);

        // $tenant = Tenant::create([
        //     'id' => $user->name,
        // ]);

        // $domain = $tenant->domains()->create([
        //     'domain' => $user->name . '.' . config('app.domain'),
        // ]);

        $user = CentralUser::where('name', 'example')->first();
        $tenant = Tenant::where('id', 'example')->first();

        // // if ($tenant) {
        tenancy()->initialize($tenant);

        //     // Create the same user in tenant DB
        User::create([
            'global_id' => $user->global_id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password
        ]);

        // $user->update([
        //     'name' => 'John Foo', // synced
        // ]);
        // // }
    }
}
