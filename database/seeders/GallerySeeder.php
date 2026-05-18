<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Gallery;
use App\Models\Outbound;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create all areas
        $areaPT1 = Area::firstOrCreate(
            ['slug' => 'pineus-tilu-1'],
            [
                'name' => 'Pineus Tilu 1',
                'description' => 'Pineus Tilu 1 camping ground area',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
            ]
        );

        $areaPT2 = Area::firstOrCreate(
            ['slug' => 'pineus-tilu-2'],
            [
                'name' => 'Pineus Tilu 2',
                'description' => 'Pineus Tilu 2 camping ground area',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
            ]
        );

        $areaPT3 = Area::firstOrCreate(
            ['slug' => 'pineus-tilu-3-vip'],
            [
                'name' => 'Pineus Tilu 3 VIP',
                'description' => 'Pineus Tilu 3 VIP camping ground area',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
            ]
        );

        $areaPT4 = Area::firstOrCreate(
            ['slug' => 'pineus-tilu-4'],
            [
                'name' => 'Pineus Tilu 4',
                'description' => 'Pineus Tilu 4 camping ground area',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
            ]
        );

        $areaCabinVIP = Area::firstOrCreate(
            ['slug' => 'pineus-tilu-cabin'],
            [
                'name' => 'Pineus Tilu Cabin VIP',
                'description' => 'Pineus Tilu Cabin VIP',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
            ]
        );

        $areaCabinVVIP = Area::firstOrCreate(
            ['slug' => 'pineus-tilu-cabin-vvip'],
            [
                'name' => 'Pineus Tilu Cabin VVIP',
                'description' => 'Pineus Tilu Cabin VVIP',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
            ]
        );

        // Dashboard Galleries
        $dashboardGalleries = [
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/suasana.JPG',
                'description' => 'Pineus Tilu Ambiance - Hero Image',
                'type' => 'dashboard_header',
            ],
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/fullmap.jpeg',
                'description' => 'Pineus Tilu Location Map',
                'type' => 'dashboard_map',
            ],
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/tenda.jpg',
                'description' => 'Glamping Tent',
                'type' => 'dashboard_galeri',
            ],
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/pemulihan.jpg',
                'description' => 'Recovery Area',
                'type' => 'dashboard_galeri',
            ],
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/aktifitas.JPG',
                'description' => 'Activities',
                'type' => 'dashboard_galeri',
            ],
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/apiunggun.jpg',
                'description' => 'Bonfire',
                'type' => 'dashboard_galeri',
            ],
            [
                'area_id' => null,
                'image_path' => 'images/dashboard/siap.jpg',
                'description' => 'Ready-to-Use Tent',
                'type' => 'dashboard_galeri',
            ],
        ];

        // Area Pineus Tilu 1 Galleries
        $areaPT1Galleries = [
            // Header
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/PT1.webp',
                'description' => 'Header Image - Pineus Tilu 1',
                'type' => 'header',
            ],
            // Skema Deck
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/deck/pt1_deck.svg',
                'description' => 'Skema Deck - Pineus Tilu 1',
                'type' => 'skema_deck',
            ],
            // Tent
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/tent/4.0.webp',
                'description' => 'Tent - Pineus Tilu 1 - 1',
                'type' => 'tent',
            ],
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/tent/4.2.jpg',
                'description' => 'Tent - Pineus Tilu 1 - 2',
                'type' => 'tent',
            ],
            // Gallery
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/galeri1-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 1 - 1',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/galeri2-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 1 - 2',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/galeri3-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 1 - 3',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/galeri4-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 1 - 4',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/galeri5-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 1 - 5',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT1->id,
                'image_path' => 'images/area-galeri/pt-1/galeri6-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 1 - 6',
                'type' => 'galeri',
            ],
        ];

        // Area Pineus Tilu 2 Galleries
        $areaPT2Galleries = [
            // Header
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-2/main.jpg',
                'description' => 'Header Image - Pineus Tilu 2',
                'type' => 'header',
            ],
            // Skema Deck
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/deck/pt2_deck.svg',
                'description' => 'Skema Deck - Pineus Tilu 2',
                'type' => 'skema_deck',
            ],
            // Tent
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/tent/4.0.webp',
                'description' => 'Tent - Pineus Tilu 2 - 1',
                'type' => 'tent',
            ],
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/tent/4.2.jpg',
                'description' => 'Tent - Pineus Tilu 2 - 2',
                'type' => 'tent',
            ],
            // Gallery
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-1/galeri1-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 2 - 1',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-1/galeri2-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 2 - 2',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-1/galeri3-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 2 - 3',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-1/galeri4-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 2 - 4',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-1/galeri5-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 2 - 5',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT2->id,
                'image_path' => 'images/area-galeri/pt-1/galeri6-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 2 - 6',
                'type' => 'galeri',
            ],
        ];

        // Area Pineus Tilu 3 VIP Galleries
        $areaPT3Galleries = [
            // Header
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/main.jpg',
                'description' => 'Header Image - Pineus Tilu 3 VIP',
                'type' => 'header',
            ],
            // Skema Deck
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/deck/pt3vip_deck.svg',
                'description' => 'Skema Deck - Pineus Tilu 3 VIP',
                'type' => 'skema_deck',
            ],
            // Tent
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/tent/5.2.png',
                'description' => 'Tent - Pineus Tilu 3 VIP',
                'type' => 'tent',
            ],
            // Gallery
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/gallery1.png',
                'description' => 'Galeri Pineus Tilu 3 VIP - 1',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/gallery2.png',
                'description' => 'Galeri Pineus Tilu 3 VIP - 2',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/gallery3.png',
                'description' => 'Galeri Pineus Tilu 3 VIP - 3',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/gallery4.png',
                'description' => 'Galeri Pineus Tilu 3 VIP - 4',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/gallery5.png',
                'description' => 'Galeri Pineus Tilu 3 VIP - 5',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT3->id,
                'image_path' => 'images/area-galeri/pt-3/gallery6.png',
                'description' => 'Galeri Pineus Tilu 3 VIP - 6',
                'type' => 'galeri',
            ],
        ];

        // Area Pineus Tilu 4 Galleries
        $areaPT4Galleries = [
            // Header
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-4/main.jpg',
                'description' => 'Header Image - Pineus Tilu 4',
                'type' => 'header',
            ],
            // Skema Deck
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/deck/pt4_deck.svg',
                'description' => 'Skema Deck - Pineus Tilu 4',
                'type' => 'skema_deck',
            ],
            // Tent
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/tent/6.3.png',
                'description' => 'Tent - Pineus Tilu 4',
                'type' => 'tent',
            ],
            // Gallery
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-1/galeri1-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 4 - 1',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-1/galeri2-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 4 - 2',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-1/galeri3-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 4 - 3',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-1/galeri4-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 4 - 4',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-1/galeri5-pt1.jpeg',
                'description' => 'Galeri Pineus Tilu 4 - 5',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaPT4->id,
                'image_path' => 'images/area-galeri/pt-1/galeri6-pt1.jpg',
                'description' => 'Galeri Pineus Tilu 4 - 6',
                'type' => 'galeri',
            ],
        ];

        // Cabin VIP Galleries
        $cabinVIPGalleries = [
            // Header
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/main.webp',
                'description' => 'Header Image - Pineus Tilu Cabin',
                'type' => 'header',
            ],
            // Skema Deck
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/deck/ptcabinvip_deck.svg',
                'description' => 'Skema Deck - Pineus Tilu Cabin',
                'type' => 'skema_deck',
            ],
            // Gallery
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/galericabinvip1.jpg',
                'description' => 'Galeri Cabin VIP - 1',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/galericabinvip2.jpg',
                'description' => 'Galeri Cabin VIP - 2',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/galericabinvip3.jpg',
                'description' => 'Galeri Cabin VIP - 3',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/galericabinvip4.jpg',
                'description' => 'Galeri Cabin VIP - 4',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/galericabinvip5.jpeg',
                'description' => 'Galeri Cabin VIP - 5',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin/galericabinvip6.jpg',
                'description' => 'Galeri Cabin VIP - 6',
                'type' => 'galeri',
            ],
        ];

        // Cabin VVIP Galleries
        $cabinVVIPGalleries = [
            // Header
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/main.jpeg',
                'description' => 'Header Image - Pineus Tilu Cabin VVIP',
                'type' => 'header',
            ],
            // Skema Deck
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/deck/ptcabinvvip_deck.svg',
                'description' => 'Skema Deck - Pineus Tilu Cabin VVIP',
                'type' => 'skema_deck',
            ],
            // Gallery
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/livingroom.jpeg',
                'description' => 'Galeri Cabin VVIP - Living Room',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/bedroom.jpeg',
                'description' => 'Galeri Cabin VVIP - Bedroom',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/toilet.jpeg',
                'description' => 'Galeri Cabin VVIP - Toilet',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/kitchen.jpeg',
                'description' => 'Galeri Cabin VVIP - Kitchen',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/diningroom.jpeg',
                'description' => 'Galeri Cabin VVIP - Dining Room',
                'type' => 'galeri',
            ],
            [
                'area_id' => $areaCabinVVIP->id,
                'image_path' => 'images/area-galeri/pt-cabin-vvip/teras.jpeg',
                'description' => 'Galeri Cabin VVIP - Teras',
                'type' => 'galeri',
            ],
        ];

        // Combine all galleries
        $allGalleries = array_merge(
            $dashboardGalleries,
            $areaPT1Galleries,
            $areaPT2Galleries,
            $areaPT3Galleries,
            $areaPT4Galleries,
            $cabinVIPGalleries,
            $cabinVVIPGalleries
        );

        // Insert all galleries
        foreach ($allGalleries as $gallery) {
            Gallery::updateOrCreate(
                [
                    'image_path' => $gallery['image_path'],
                    'area_id' => $gallery['area_id'],
                ],
                $gallery
            );
        }

        // ============================================
        // Outbound Activity Galleries
        // ============================================
        
        // Get outbound IDs
        $rafting = Outbound::where('slug', 'arung-jeram')->first();
        $flyingFox = Outbound::where('slug', 'flying-fox')->first();
        $offroad = Outbound::where('slug', 'offroad')->first();
        $funAtv = Outbound::where('slug', 'fun-atv')->first();
        $paintball = Outbound::where('slug', 'paintball')->first();
        $teamBuilding = Outbound::where('slug', 'team-building')->first();

        $outboundGalleries = [];

        // Rafting galleries
        if ($rafting) {
            $outboundGalleries = array_merge($outboundGalleries, [
                [
                    'outbound_id' => $rafting->id,
                    'image_path' => 'images/aktivitas-galeri/arungjeram1.jpg',
                    'description' => 'Rafting Activity',
                    'type' => 'outbound',
                ],
                [
                    'outbound_id' => $rafting->id,
                    'image_path' => 'images/aktivitas-galeri/arungjeram2.jpg',
                    'description' => 'Rafting Activity 2',
                    'type' => 'outbound',
                ],
            ]);
        }

        // Flying Fox galleries
        if ($flyingFox) {
            $outboundGalleries = array_merge($outboundGalleries, [
                [
                    'outbound_id' => $flyingFox->id,
                    'image_path' => 'images/aktivitas-galeri/flyingfox.jpg',
                    'description' => 'Flying Fox Activity',
                    'type' => 'outbound',
                ],
            ]);
        }

        // Offroad galleries
        if ($offroad) {
            $outboundGalleries = array_merge($outboundGalleries, [
                [
                    'outbound_id' => $offroad->id,
                    'image_path' => 'images/aktivitas-galeri/offroad.jpg',
                    'description' => 'Offroad Activity',
                    'type' => 'outbound',
                ],
            ]);
        }

        // Fun ATV galleries
        if ($funAtv) {
            $outboundGalleries = array_merge($outboundGalleries, [
                [
                    'outbound_id' => $funAtv->id,
                    'image_path' => 'images/aktivitas-galeri/funatv.jpg',
                    'description' => 'Fun ATV Activity',
                    'type' => 'outbound',
                ],
            ]);
        }

        // Paintball galleries
        if ($paintball) {
            $outboundGalleries = array_merge($outboundGalleries, [
                [
                    'outbound_id' => $paintball->id,
                    'image_path' => 'images/aktivitas-galeri/paintball.jpg',
                    'description' => 'Paintball Activity',
                    'type' => 'outbound',
                ],
            ]);
        }

        // Team Building galleries
        if ($teamBuilding) {
            $outboundGalleries = array_merge($outboundGalleries, [
                [
                    'outbound_id' => $teamBuilding->id,
                    'image_path' => 'images/aktivitas-galeri/teambuilding.jpeg',
                    'description' => 'Team Building Activity',
                    'type' => 'outbound',
                ],
            ]);
        }

        // Insert outbound galleries
        foreach ($outboundGalleries as $gallery) {
            Gallery::updateOrCreate(
                [
                    'image_path' => $gallery['image_path'],
                    'outbound_id' => $gallery['outbound_id'],
                ],
                array_merge($gallery, ['area_id' => null])
            );
        }

        $this->command->info('Gallery seeder completed!');
        $this->command->info('- Dashboard galleries: ' . count($dashboardGalleries));
        $this->command->info('- Area PT1 galleries: ' . count($areaPT1Galleries));
        $this->command->info('- Area PT2 galleries: ' . count($areaPT2Galleries));
        $this->command->info('- Area PT3 VIP galleries: ' . count($areaPT3Galleries));
        $this->command->info('- Area PT4 galleries: ' . count($areaPT4Galleries));
        $this->command->info('- Cabin VIP galleries: ' . count($cabinVIPGalleries));
        $this->command->info('- Cabin VVIP galleries: ' . count($cabinVVIPGalleries));
        $this->command->info('- Outbound galleries: ' . count($outboundGalleries));
    }
}
