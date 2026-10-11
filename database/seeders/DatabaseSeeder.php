<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Country;
use App\Models\Currency;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete enterprise demo data.
     */
    public function run(): void
    {
        // 1. Admin Users
        $this->call(AdminUserSeeder::class);
        $admin = User::where('email', 'admin@beta-industrial.com')->first();

        // 2. Currencies & Countries
        $egp = Currency::firstOrCreate(['code' => 'EGP'], ['symbol' => 'EGP', 'is_active' => true]);
        $sar = Currency::firstOrCreate(['code' => 'SAR'], ['symbol' => 'SAR', 'is_active' => true]);

        $egypt = Country::firstOrCreate(['iso2' => 'EG'], [
            'name' => 'Egypt',
            'currency_id' => $egp->id,
            'is_active' => true,
        ]);

        $ksa = Country::firstOrCreate(['iso2' => 'SA'], [
            'name' => 'Saudi Arabia',
            'currency_id' => $sar->id,
            'is_active' => true,
        ]);

        // 3. Services (Dual Language)
        $servicesData = [
            [
                'slug' => 'engineering-tekla-bim',
                'title' => [
                    'en' => 'Tekla 3D Detailing & Structural Engineering',
                    'ar' => 'التصميم الهندسي والنمذجة ثلاثية الأبعاد (Tekla & BIM)'
                ],
                'description' => [
                    'en' => 'Complete structural steel design, connection engineering, and Tekla Structures 3D detailing conforming to AISC and Eurocodes.',
                    'ar' => 'تصميم المنشآت المعدنية الشامل وهندسة الوصلات وتفصيل نماذج تكلا ثلاثية الأبعاد وفقاً للمواصفات الأمريكية والأوروبية.'
                ]
            ],
            [
                'slug' => 'heavy-steel-fabrication',
                'title' => [
                    'en' => 'Heavy Structural Steel Fabrication',
                    'ar' => 'تصنيع المنشآت والهياكل الفولاذية الثقيلة'
                ],
                'description' => [
                    'en' => 'Automated cutting, CNC drilling, submerged arc welding, and assembly of heavy columns, girders, and complex structural members.',
                    'ar' => 'قص وتخريم آلي باستخدام CNC واللحام الآلي بالقوس المغمور وتجميع الأعمدة والكمرات الفولاذية الضخمة بأعلى معايير الدقة.'
                ]
            ],
            [
                'slug' => 'surface-treatment-galvanizing',
                'title' => [
                    'en' => 'Surface Blasting & Hot-Dip Galvanizing',
                    'ar' => 'معالجة الأسطح والجلفنة على الساخن'
                ],
                'description' => [
                    'en' => 'SA 2.5 abrasive shot blasting, multi-coat epoxy primer and polyurethane coating, and ISO 1461 hot-dip galvanizing.',
                    'ar' => 'سفح الرمال الميكانيكي بدرجة SA 2.5، وتطبيق دهانات الإيبوكسي والبولي يوريثان، والجلفنة بالغمس الساخن المعتمدة دولياً.'
                ]
            ],
            [
                'slug' => 'site-erection-heavy-rigging',
                'title' => [
                    'en' => 'Site Erection & Heavy Rigging',
                    'ar' => 'التركيب الميداني ورفع الهياكل الثقيلة'
                ],
                'description' => [
                    'en' => 'Turnkey on-site installation by certified erection engineers with precision surveying, heavy mobile cranes, and zero-incident safety protocols.',
                    'ar' => 'تركيب ميداني متكامل بإشراف مهندسين متخصصين ورافعات ثقيلة مع الالتزام التام بأعلى معايير السلامة والصحة المهنية.'
                ]
            ],
        ];

        foreach ($servicesData as $s) {
            Service::firstOrCreate(['slug' => $s['slug']], [
                'public_id' => (string) Str::ulid(),
                'title' => $s['title'],
                'description' => $s['description'],
                'is_active' => true,
            ]);
        }

        // 4. Products (Dual Language & Specifications Builder)
        $productsData = [
            [
                'slug' => 'heavy-structural-columns-beams',
                'title' => [
                    'en' => 'Heavy Structural Columns & Beams',
                    'ar' => 'أعمدة وكمرات الهياكل الإنشائية الثقيلة'
                ],
                'description' => [
                    'en' => 'High-yield ASTM steel framing for industrial complexes, mega factories, and multi-story infrastructure.',
                    'ar' => 'هياكل فولاذية عالية المقاومة طبقا لمواصفات ASTM مخصصة للمصانع الكبرى والمستودعات والمباني متعددة الطوابق.'
                ],
                'specifications' => [
                    'steel_grade' => 'ASTM A36 / A992 / S355JR',
                    'yield_strength' => '355 MPa min',
                    'finish' => 'Hot-Dip Galvanized / Multi-Coat Epoxy',
                    'welding_code' => 'AWS D1.1 / EN 1090-2 EXC3'
                ]
            ],
            [
                'slug' => 'clear-span-roof-trusses',
                'title' => [
                    'en' => 'Clear-Span Roof Trusses',
                    'ar' => 'جمالونات الأسقف ذات البحور الواسعة'
                ],
                'description' => [
                    'en' => 'Long-span structural trusses designed for column-free distribution warehouses and aircraft hangars up to 60 meters.',
                    'ar' => 'جمالونات فولاذية واسعة البحر مصممة لتوفير مساحات تخزين ومستودعات خالية من الأعمدة الداخلية حتى 60 متراً.'
                ],
                'specifications' => [
                    'steel_grade' => 'S275 / S355 Structural Hollow Sections',
                    'span_range' => '20m - 65m Clear Span',
                    'finish' => 'Industrial Protective Polyurethane Coating',
                    'deflection_limit' => 'L / 360 conforming to BS 5950'
                ]
            ],
            [
                'slug' => 'built-up-crane-girders',
                'title' => [
                    'en' => 'Built-Up Crane Girders & Plate Work',
                    'ar' => 'كمرات الأوناش الملحومة والمسطحات الثقيلة'
                ],
                'description' => [
                    'en' => 'Custom welded plate girders engineered for heavy industrial overhead cranes with high fatigue resistance.',
                    'ar' => 'كمرات صفائحية ملحومة آلياً مخصصة لمسارات الأوناش العلوية الثقيلة ذات التحمل العالي للأحمال الديناميكية.'
                ],
                'specifications' => [
                    'steel_grade' => 'High-Strength Heavy Plate ASTM A572 Gr. 50',
                    'welding_process' => 'Automated Submerged Arc Welding (SAW)',
                    'testing' => '100% Ultrasonic & Magnetic Particle NDT',
                    'capacity' => 'Supports Overhead Cranes up to 80 Metric Tons'
                ]
            ],
            [
                'slug' => 'pre-engineered-steel-buildings',
                'title' => [
                    'en' => 'Pre-Engineered Steel Buildings (PEB)',
                    'ar' => 'المباني الفولاذية سابقة الهندسة (PEB)'
                ],
                'description' => [
                    'en' => 'Optimized rigid frame steel systems with insulated sandwich panel claddings for rapid commercial deployment.',
                    'ar' => 'أنظمة إطارات صلبة متطورة مع ألواح تكسية عازلة (ساندوتش بانل) للتركيب السريع في المشاريع الصناعية والتجارية.'
                ],
                'specifications' => [
                    'primary_framing' => 'Tapered Built-Up I-Sections',
                    'secondary_framing' => 'Cold-Formed Galvanized Z & C Purlins',
                    'cladding' => 'PIR Fire-Rated Insulated Sandwich Panels',
                    'wind_speed_design' => 'Up to 160 km/h regional codes'
                ]
            ],
        ];

        foreach ($productsData as $p) {
            Product::firstOrCreate(['slug' => $p['slug']], [
                'public_id' => (string) Str::ulid(),
                'title' => $p['title'],
                'description' => $p['description'],
                'specifications' => $p['specifications'],
                'is_active' => true,
            ]);
        }

        // 5. Announcements & News
        $announcementsData = [
            [
                'slug' => 'beta-awarded-ain-sokhna-petrochemical-contract',
                'type' => 'press_release',
                'title' => [
                    'en' => 'BETA Awarded 4,500-Ton Heavy Steel Expansion in Ain Sokhna',
                    'ar' => 'بيتا تفوز بعقد توريد وتصنيع 4500 طن هياكل معدنية بمجمع العين السخنة'
                ],
                'excerpt' => [
                    'en' => 'BETA Industrial secures landmark contract to fabricate and install high-tonnage pipe racks and heavy structures in Suez Economic Zone.',
                    'ar' => 'بيتا الصناعية توقع عقداً بارزاً لتصنيع وتركيب مسارات الأنابيب والهياكل الثقيلة بالمنطقة الاقتصادية لقناة السويس.'
                ],
                'content' => [
                    'en' => 'BETA Industrial has officially been awarded the primary structural steel package for the new chemical processing terminal in Ain Sokhna, Egypt. The scope entails engineering 3D Tekla modeling, 4,500 tons of heavy welded plate girders, hot-dip galvanizing, and turnkey erection within an accelerated 9-month timeframe.',
                    'ar' => 'فازت شركة بيتا للصناعات المعدنية رسمياً بحزمة الأعمال الإنشائية الرئيسية لمجمع البتروكيماويات بالعين السخنة. يشمل نطاق العمل النمذجة الهندسية وتصنيع 4500 طن من المنشآت الفولاذية الثقيلة والجلفنة والتركيب في فترة قياسية مدتها 9 أشهر.'
                ],
                'published_at' => now()->subDays(5),
            ],
            [
                'slug' => 'saudi-arabia-riyadh-fabrication-hub-expansion',
                'type' => 'news',
                'title' => [
                    'en' => 'BETA Strengthens Logistics Hub & MODON Operations in Riyadh KSA',
                    'ar' => 'بيتا تعزز عملياتها اللوجستية وتوسع شراكاتها في المدن الصناعية (مدن) بالرياض'
                ],
                'excerpt' => [
                    'en' => 'Strategic expansion in Saudi Arabia to support giga-projects and industrial logistics warehousing across the Kingdom.',
                    'ar' => 'توسع استراتيجي لشركة بيتا في المملكة العربية السعودية لدعم المشاريع العملاقة والمستودعات اللوجستية.'
                ],
                'content' => [
                    'en' => 'As part of our commitment to Saudi Vision 2030, BETA Industrial has expanded its technical coordination center in Riyadh, partnering with regional logistics developers to supply certified heavy structural frames and rapid-erection warehouse solutions.',
                    'ar' => 'ضمن التزامنا برؤية المملكة 2030، قامت بيتا بتوسيع مركز التنسيق الهندسي والميداني في الرياض لتوريد وتركيب الهياكل الفولاذية للمستودعات اللوجستية الكبرى وفق أعلى المواصفات السعودية SASO.'
                ],
                'published_at' => now()->subDays(12),
            ],
            [
                'slug' => 'iso-3834-and-en-1090-welding-quality-accreditation',
                'type' => 'news',
                'title' => [
                    'en' => 'BETA Renews ISO 3834-2 & EN 1090-2 Global Welding Certifications',
                    'ar' => 'بيتا تجدد اعتمادات الجودة العالمية للحام والإنتاج الإنشائي ISO 3834-2 و EN 1090-2'
                ],
                'excerpt' => [
                    'en' => 'International audits confirm rigorous quality assurance, non-destructive testing (NDT), and certified welding coordination.',
                    'ar' => 'تأكيد الالتزام بأعلى معايير الجودة العالمية والفحص غير الإتلافي للحامات الهياكل الإنشائية الثقيلة.'
                ],
                'content' => [
                    'en' => 'Our manufacturing plants have successfully completed the comprehensive audit for ISO 3834-2 (Comprehensive Quality Requirements for Fusion Welding of Metallic Materials) and EN 1090-2 Execution Class EXC3.',
                    'ar' => 'أتمت مصانع بيتا بنجاح التدقيق السنوي الشامل لشهادة الجودة العالمية للحام المنصهر للمواد المعدنية ومطابقة شهادة تصنيع الهياكل الفولاذية الأوربية حتى الدرجة EXC3.'
                ],
                'published_at' => now()->subDays(20),
            ],
        ];

        foreach ($announcementsData as $a) {
            Announcement::firstOrCreate(['slug' => $a['slug']], [
                'public_id' => (string) Str::ulid(),
                'type' => $a['type'],
                'title' => $a['title'],
                'excerpt' => $a['excerpt'],
                'content' => $a['content'],
                'published_at' => $a['published_at'],
                'is_active' => true,
            ]);
        }

        // 6. Job Postings
        $jobsData = [
            [
                'title' => [
                    'en' => 'Senior Tekla Structural Detailer / Modeler',
                    'ar' => 'مهندس أول نمذجة وتفصيل إنشائي (Tekla Structures)'
                ],
                'country_id' => $egypt->id,
                'description' => [
                    'en' => 'Minimum 5+ years experience in 3D modeling of heavy industrial structural steel, connection details, and generating shop drawings.',
                    'ar' => 'خبرة لا تقل عن 5 سنوات في النمذجة ثلاثية الأبعاد للمنشآت الفولاذية وتفاصيل الوصلات وإصدار المخططات التنفيذية.'
                ]
            ],
            [
                'title' => [
                    'en' => 'Lead Site Erection Engineer (Riyadh & Eastern Province)',
                    'ar' => 'مهندس موقع أول - تركيبات هياكل معدنية (الرياض والمنطقة الشرقية)'
                ],
                'country_id' => $ksa->id,
                'description' => [
                    'en' => 'Hands-on supervision of heavy steel erection, crane rigging calculations, bolt torque verification, and site HSE protocols in KSA.',
                    'ar' => 'الإشراف الميداني على تركيب الهياكل المعدنية وحسابات حمولة الرافعات واختبارات العزم وتطبيق معايير السلامة بالمملكة.'
                ]
            ],
            [
                'title' => [
                    'en' => 'Certified QA/QC Welding Inspector (CSWIP 3.1 / CWI)',
                    'ar' => 'مهندس جودة وتفتيش لحام معتمد (CSWIP 3.1 / CWI)'
                ],
                'country_id' => $egypt->id,
                'description' => [
                    'en' => 'Perform visual inspection, NDT witnessing (UT, MPI), welder performance qualifications, and documentation for client handover.',
                    'ar' => 'مراقبة جودة اللحامات وإجراء الفحوصات غير الإتلافية ومتابعة اختبارات اللحامين وتجهيز وثائق تسليم الجودة.'
                ]
            ]
        ];

        foreach ($jobsData as $j) {
            JobPosting::create([
                'public_id' => (string) Str::ulid(),
                'title' => $j['title'],
                'country_id' => $j['country_id'],
                'description' => $j['description'],
                'is_active' => true,
            ]);
        }

        // 7. Seed Sample Quote Requests and Quotations for Dashboard Analytics
        $firstService = Service::first();
        $firstProduct = Product::first();
        $secondProduct = Product::skip(1)->first();

        $quotesToSeed = [
            [
                'client_name' => 'Eng. Khaled El-Sayed',
                'company_name' => 'Arab Contractors JV',
                'email' => 'k.elsayed@arabcontractors.example.com',
                'phone' => '+20 100 123 4567',
                'country_id' => $egypt->id,
                'currency_id' => $egp->id,
                'service_id' => $firstService->id,
                'product_id' => $firstProduct->id,
                'client_message' => 'Requesting supply and erection of 850 tons of heavy structural steel columns and roof trusses for Suez warehouse.',
                'status' => 'accepted',
                'factory_cost' => 38000000.00,
                'selling_price' => 49500000.00,
                'created_at' => now()->subMonths(3),
            ],
            [
                'client_name' => 'Fahad Al-Otaibi',
                'company_name' => 'Red Sea Infrastructure Co.',
                'email' => 'fahad@redsea-infra.example.com',
                'phone' => '+966 50 987 6543',
                'country_id' => $ksa->id,
                'currency_id' => $sar->id,
                'service_id' => $firstService->id,
                'product_id' => $secondProduct->id,
                'client_message' => 'Fabrication and supply of 1,200 tons clear-span trusses for logistics park in Jeddah.',
                'status' => 'accepted',
                'factory_cost' => 6200000.00,
                'selling_price' => 8400000.00,
                'created_at' => now()->subMonths(2),
            ],
            [
                'client_name' => 'Eng. Ahmed Zaki',
                'company_name' => 'Orascom Construction',
                'email' => 'a.zaki@orascom.example.com',
                'phone' => '+20 122 345 6789',
                'country_id' => $egypt->id,
                'currency_id' => $egp->id,
                'service_id' => $firstService->id,
                'product_id' => $firstProduct->id,
                'client_message' => 'Built-up crane girders for Ain Sokhna rolling mill expansion.',
                'status' => 'quoted',
                'factory_cost' => 24500000.00,
                'selling_price' => 32000000.00,
                'created_at' => now()->subMonths(1),
            ],
            [
                'client_name' => 'Sultan Al-Ghamdi',
                'company_name' => 'Nesma & Partners',
                'email' => 'sultan.g@nesma.example.com',
                'phone' => '+966 55 432 1098',
                'country_id' => $ksa->id,
                'currency_id' => $sar->id,
                'service_id' => $firstService->id,
                'product_id' => $secondProduct->id,
                'client_message' => 'Supply and galvanizing for 650 tons industrial steel structures near Riyadh.',
                'status' => 'reviewing',
                'factory_cost' => 3100000.00,
                'selling_price' => 4200000.00,
                'created_at' => now()->subDays(10),
            ],
            [
                'client_name' => 'Dr. Mahmoud Radwan',
                'company_name' => 'Hassan Allam Holding',
                'email' => 'm.radwan@hassanallam.example.com',
                'phone' => '+20 111 888 9999',
                'country_id' => $egypt->id,
                'currency_id' => $egp->id,
                'service_id' => $firstService->id,
                'product_id' => $firstProduct->id,
                'client_message' => 'Inquiry for high-yield ASTM A992 structural columns for data center in New Capital.',
                'status' => 'new',
                'factory_cost' => 0.00,
                'selling_price' => 0.00,
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($quotesToSeed as $q) {
            $qr = QuoteRequest::create([
                'public_id' => (string) Str::ulid(),
                'client_name' => $q['client_name'],
                'company_name' => $q['company_name'],
                'email' => $q['email'],
                'phone' => $q['phone'],
                'country_id' => $q['country_id'],
                'service_id' => $q['service_id'],
                'product_id' => $q['product_id'],
                'client_message' => $q['client_message'],
                'status' => $q['status'],
                'submitted_at' => $q['created_at'],
                'created_at' => $q['created_at'],
            ]);

            if ($q['selling_price'] > 0) {
                Quotation::create([
                    'public_id' => (string) Str::ulid(),
                    'quote_request_id' => $qr->id,
                    'admin_user_id' => $admin->id,
                    'currency_id' => $q['currency_id'],
                    'factory_cost' => $q['factory_cost'],
                    'selling_price' => $q['selling_price'],
                    'status' => $q['status'] === 'accepted' ? 'accepted' : 'sent_to_client',
                    'admin_notes' => 'Costing breakdown calculated via internal fabrication rate matrix.',
                    'created_at' => $q['created_at'],
                ]);
            }
        }

        // 8. Seed Sample Job Applications
        $firstJob = JobPosting::first();
        if ($firstJob) {
            JobApplication::create([
                'public_id' => (string) Str::ulid(),
                'job_posting_id' => $firstJob->id,
                'applicant_name' => 'Eng. Mostafa Kamal',
                'email' => 'mostafa.kamal@example.com',
                'phone' => '+20 102 334 5566',
                'rating' => 5,
                'status' => 'interviewing',
                'submitted_at' => now()->subDays(3),
            ]);

            JobApplication::create([
                'public_id' => (string) Str::ulid(),
                'job_posting_id' => $firstJob->id,
                'applicant_name' => 'Eng. Youssef Nabil',
                'email' => 'youssef.n@example.com',
                'phone' => '+20 114 778 9900',
                'rating' => 4,
                'status' => 'reviewed',
                'submitted_at' => now()->subDays(1),
            ]);
        }
    }
}
