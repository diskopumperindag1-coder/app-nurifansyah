<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headerMenu = Menu::updateOrCreate(
            ['location' => 'header'],
            [
                'name' => 'Menu Utama (Header)',
                'is_active' => true,
            ]
        );

        $footerMenu = Menu::updateOrCreate(
            ['location' => 'footer'],
            [
                'name' => 'Menu Bawah (Footer)',
                'is_active' => true,
            ]
        );

        // Header Items
        $items = [
            ['label' => ['id' => 'Beranda', 'en' => 'Home'], 'url' => '/', 'type' => 'custom', 'sort_order' => 1],
            ['label' => ['id' => 'Tentang Kami', 'en' => 'About Us'], 'url' => '/pages/tentang-kami', 'type' => 'page', 'sort_order' => 2],
            ['label' => ['id' => 'Visi & Misi', 'en' => 'Vision & Mission'], 'url' => '/pages/visi-misi', 'type' => 'page', 'sort_order' => 3],
            ['label' => ['id' => 'Berita & Artikel', 'en' => 'News & Articles'], 'url' => '/posts', 'type' => 'custom', 'sort_order' => 4],
            ['label' => ['id' => 'Tanya Masyarakat', 'en' => 'Public Inquiries'], 'url' => '/questions', 'type' => 'custom', 'sort_order' => 5],
        ];

        foreach ($items as $item) {
            MenuItem::updateOrCreate(
                [
                    'menu_id' => $headerMenu->id,
                    'sort_order' => $item['sort_order'],
                ],
                [
                    'label' => $item['label'],
                    'url' => $item['url'],
                    'type' => $item['type'],
                    'is_active' => true,
                ]
            );
        }

        // Footer items
        $footerItems = [
            ['label' => ['id' => 'Kebijakan Privasi', 'en' => 'Privacy Policy'], 'url' => '/pages/kebijakan-privasi', 'type' => 'custom', 'sort_order' => 1],
            ['label' => ['id' => 'Syarat & Ketentuan', 'en' => 'Terms & Conditions'], 'url' => '/pages/syarat-ketentuan', 'type' => 'custom', 'sort_order' => 2],
            ['label' => ['id' => 'FAQ', 'en' => 'FAQ'], 'url' => '/faqs', 'type' => 'custom', 'sort_order' => 3],
        ];

        foreach ($footerItems as $fItem) {
            MenuItem::updateOrCreate(
                [
                    'menu_id' => $footerMenu->id,
                    'sort_order' => $fItem['sort_order'],
                ],
                [
                    'label' => $fItem['label'],
                    'url' => $fItem['url'],
                    'type' => $fItem['type'],
                    'is_active' => true,
                ]
            );
        }
    }
}
