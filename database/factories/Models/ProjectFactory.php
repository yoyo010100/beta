<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => \Illuminate\Support\Str::ulid(),
            'title' => ['en' => 'Project Title', 'ar' => 'اسم المشروع'],
            'slug' => 'project-'.str_random(8),
            'category' => 'industrial',
            'client_name' => 'Client Name',
            'description' => ['en' => 'Project description', 'ar' => 'وصف المشروع'],
            'image_path' => 'https://picsum.photos/seed/project/800/400',
            'is_active' => true,
            'completed_at' => null,
        ];
    }
}
