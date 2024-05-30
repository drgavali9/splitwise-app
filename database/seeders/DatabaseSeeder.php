<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(20)->create();

        tap(Group::factory(20)->create(), function ($groups) {
            $groups->each(function (Group $group) {
                $userCount = random_int(3, 7);
                $totalUserCount = User::count();

                $randomUserIds = [$group->owner_id];

                for ($i = 0; $i < $userCount; $i++) {
                    $randomUserIds[] = random_int(1, $totalUserCount);
                }

                $group->users()->sync($randomUserIds);
            });
        });

    }
}
