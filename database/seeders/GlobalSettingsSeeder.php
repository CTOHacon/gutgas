<?php

namespace Database\Seeders;

use App\Models\GlobalSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GlobalSettings::set('phone', '0 (800) 35-35-05');
        GlobalSettings::set('workTime_ua', 'з 9:00 до 18:00 Без вихідних');
        GlobalSettings::set('workTime_en', 'from 9:00 to 18:00 Without days off');

        GlobalSettings::set('email', 'info@gutgas.eu');

        GlobalSettings::set('whatsapp', 'https://wa.me/380800353505');
        GlobalSettings::set('instagram', 'https://www.instagram.com/gutgas.eu');
        GlobalSettings::set('facebook', 'https://www.facebook.com/gutgas.eu');
        GlobalSettings::set('telegram', 'https://t.me/gutgas_eu');

        GlobalSettings::set('headerMenu_ua', json_encode([
            [
                'title' => 'Головна',
                'url' => '/',
            ],
            [
                'title' => 'Каталог балонів',
                'url' => '/catalog',
            ],
            [
                'title' => 'Про компанію',
                'url' => '/about',
            ],
            [
                'title' => 'Інструкції',
                'url' => '/instructions',
            ],
        ]));

        GlobalSettings::set('headerMenu_en', json_encode([
            [
                'title' => 'Home',
                'url' => '/',
            ],
            [
                'title' => 'Catalog',
                'url' => '/catalog',
            ],
            [
                'title' => 'About',
                'url' => '/about',
            ],
            [
                'title' => 'Instructions',
                'url' => '/instructions',
            ],
        ]));

        GlobalSettings::set('footerMenu_ua', json_encode([
            [
                'name' => 'Головна',
                'items' => [
                    // Головна; Доставка і оплата; ;Повернення Товару; Політика конфіденційності; Про компанію; Інструкції
                    [
                        'title' => 'Головна',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Доставка і оплата',
                        'url' => '/delivery',
                    ],
                    [
                        'title' => 'Повернення Товару',
                        'url' => '/return',
                    ],
                    [
                        'title' => 'Політика конфіденційності',
                        'url' => '/privacy',
                    ],
                    [
                        'title' => 'Про компанію',
                        'url' => '/about',
                    ],
                    [
                        'title' => 'Інструкції',
                        'url' => '/instructions',
                    ],
                ]
            ],
            [
                'name' => 'Каталог',
                'items' => [
                    // Всі Балони
                    [
                        'title' => 'Всі Балони',
                        'url' => '/catalog',
                    ],
                ]
            ],
            [
                'name' => 'Інформація',
                // Головна; Доставка і оплата; Повернення Товару; Політика конфіденційності; Про компанію; Інструкції
                'items' => [
                    [
                        'title' => 'Головна',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Доставка і оплата',
                        'url' => '/delivery',
                    ],
                    [
                        'title' => 'Повернення Товару',
                        'url' => '/return',
                    ],
                    [
                        'title' => 'Політика конфіденційності',
                        'url' => '/privacy',
                    ],
                    [
                        'title' => 'Про компанію',
                        'url' => '/about',
                    ],
                    [
                        'title' => 'Інструкції',
                        'url' => '/instructions',
                    ],
                ]
            ]
        ]));

        GlobalSettings::set('footerMenu_en', json_encode([
            [
                'name' => 'Home',
                'items' => [
                    // Home; Delivery and payment; Return of goods; Privacy policy; About; Instructions
                    [
                        'title' => 'Home',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Delivery and payment',
                        'url' => '/delivery',
                    ],
                    [
                        'title' => 'Return of goods',
                        'url' => '/return',
                    ],
                    [
                        'title' => 'Privacy policy',
                        'url' => '/privacy',
                    ],
                    [
                        'title' => 'About',
                        'url' => '/about',
                    ],
                    [
                        'title' => 'Instructions',
                        'url' => '/instructions',
                    ],
                ]
            ],
            [
                'name' => 'Catalog',
                'items' => [
                    // All cylinders
                    [
                        'title' => 'All cylinders',
                        'url' => '/catalog',
                    ],
                ]
            ],
            [
                'name' => 'Information',
                // Home; Delivery and payment; Return of goods; Privacy policy; About; Instructions
                'items' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Delivery and payment',
                        'url' => '/delivery',
                    ],
                    [
                        'title' => 'Return of goods',
                        'url' => '/return',
                    ],
                    [
                        'title' => 'Privacy policy',
                        'url' => '/privacy',
                    ],
                    [
                        'title' => 'About',
                        'url' => '/about',
                    ],
                    [
                        'title' => 'Instructions',
                        'url' => '/instructions',
                    ],
                ]
            ]
        ]));

        GlobalSettings::set('footerText_ua', 'Український проєкт з газових балонів, що поєднує в собі неперевершений український дизайн та європейську якість. Ми пропонуємо надійні та інноваційні рішення для всіх ваших газових потреб.');
        GlobalSettings::set('footerText_en', 'Ukrainian project of gas cylinders, which combines unsurpassed Ukrainian design and European quality. We offer reliable and innovative solutions for all your gas needs.');


        GlobalSettings::set('productsCatalogSlug', '/catalog');
    }
}
