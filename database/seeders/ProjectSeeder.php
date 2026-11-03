<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'public_id' => \Illuminate\Support\Str::ulid(),
                'title' => ['en' => 'Al-Zamil Logistics Hub', 'ar' => 'مركز لوجستيات الزامل'],
                'slug' => 'al-zamil-logistics-hub',
                'category' => 'industrial',
                'client_name' => 'Al-Zamil Group',
                'description' => ['en' => '10,000 sqm heavy steel warehouse facility engineered with high wind-load tolerance in Jeddah, KSA', 'ar' => 'مستودع صلب ثقيل بمساحة 10,000 متر مربع مصمم لتحمل الرياح العالية في جدة'],
                'image_path' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'completed_at' => now()->subMonths(2),
            ],
            [
                'public_id' => \Illuminate\Support\Str::ulid(),
                'title' => ['en' => 'Cairo Executive Tower', 'ar' => 'برج القاهرة التنفيذي'],
                'slug' => 'cairo-executive-tower',
                'category' => 'commercial',
                'client_name' => 'Cairo Developments',
                'description' => ['en' => 'Complex internal structural mezzanine and architectural steel framing for multi-story headquarters', 'ar' => 'ميزانين إنشائي داخلي معقد وإطار صلب Architectural للمقر الرئيسي'],
                'image_path' => 'https://images.unsplash.com/photo-1541888087401-443b35f60641?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'completed_at' => now()->subMonths(1),
            ],
            [
                'public_id' => \Illuminate\Support\Str::ulid(),
                'title' => ['en' => 'Highway Flyover Beams', 'ar' => 'كمرات الكباري والطرُق'],
                'slug' => 'highway-flyover-beams',
                'category' => 'infra',
                'client_name' => 'Ministry of Transport',
                'description' => ['en' => 'Heavy plate girder fabrication and galvanizing for critical transport infrastructure', 'ar' => 'تصنيع وتصفيح الكمرات اللوحية الثقيلة للبنية التحتية الحيوية للنقل'],
                'image_path' => 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&w=800&q=80',
                'is_active' => true,
                'completed_at' => now()->subWeek(1),
            ],
        ];

        foreach ($projects as $project) {
            \App\Models\Project::create($project);
        }
    }
}