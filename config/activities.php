<?php

$documentationIncluded = '(documentation included)';

return [
    'intro' => [
        'title' => 'OUTDOOR ACTIVITIES',
        'description' => 'Experience the thrill of rafting, flying fox, paintball, ATV & offroad, as well as team building activities that bring unforgettable moments of adventure and togetherness.',
    ],

    'cards' => [
        'arung-jeram' => [
            'title' => 'RAFTING',
            'fallback_images' => [
                'images/aktivitas-galeri/arungjeram1.jpg',
                'images/aktivitas-galeri/arungjeram2.jpg',
            ],
            'facilities_text' => 'Guide & lifeguard, first aid kit/safety equipment, rinse area, insurance, local transportation & instructor, documentation (including photos and videos).',
            'age_text' => 'Min. 10 years old',
            'duration_text' => '5 Km (~90 minutes)',
            'details_url' => 'https://boomrafting.id',
            'details_label' => 'boomrafting.id',
            'camping_package_text' => 'Special package price for Pineus Tilu guests who also book rafting. Rafting discount Rp 50,000/boat. Requirements: Pineus Tilu Guest & Follow IG @boomrafting',
            'meal_package_text' => 'Rafting package + Complete Nasi Liwet, served in a pot. Discount Rp 30,000/pot.',
            'meal_prices' => [
                ['label' => 'Pot No. 5', 'price' => 190000, 'note' => '(4 people)'],
                ['label' => 'Pot No. 6', 'price' => 245000, 'note' => '(5 people)'],
                ['label' => 'Pot No. 7', 'price' => 300000, 'note' => '(6 people)'],
            ],
            'variant_order' => [
                '1 Boat < 4 people',
                '1 Boat 4 people',
                '1 Boat 5 people',
                '1 Boat 6 people',
            ],
            'variant_notes' => [
                '1 Boat < 4 people' => '(documentation excluded)',
                '1 Boat 4 people' => $documentationIncluded,
                '1 Boat 5 people' => $documentationIncluded,
                '1 Boat 6 people' => $documentationIncluded,
            ],
        ],

        'flying-fox' => [
            'title' => 'FLYING FOX',
            'fallback_images' => ['images/aktivitas-galeri/flyingfox.jpg'],
            'facilities_text' => 'Safety harness, instructor, ticket, First Aid Kit / Safety equipment.',
            'age_text' => 'Min. 6 years old',
            'duration_text' => '200 meters long with 12 meters height',
            'price_note_template' => '(min :min pax)',
        ],

        'offroad' => [
            'title' => 'OFFROAD',
            'fallback_images' => ['images/aktivitas-galeri/offroad.jpg'],
            'facilities_text' => 'Offroad unit (Land Rover), including driver, instructor, First Aid Kit / Safety equipment, insurance, local transportation and ticket.',
            'age_text' => 'Min. 4 years old',
            'duration_text' => '9 Km (~120 minutes)',
            'price_note_template' => '(max :max people/unit)',
        ],

        'fun-atv' => [
            'title' => 'FUN ATV',
            'fallback_images' => ['images/aktivitas-galeri/funatv.jpg'],
            'facilities_text' => 'ATV unit, helmet, instructor, First Aid Kit / Safety equipment, insurance & ticket.',
            'age_text' => 'Min. 13 years old (as driver) / Min. 5 years old (as passenger)',
            'duration_text' => '4 Km (~60 minutes)',
            'variant_labels' => [
                'single' => 'Single (1 pax)',
                'double' => 'Double (2 pax)',
            ],
            'variant_notes' => [
                'single' => '(one)',
                'double' => '(two)',
            ],
        ],

        'paintball' => [
            'title' => 'PAINTBALL',
            'fallback_images' => ['images/aktivitas-galeri/paintball.jpg'],
            'facilities_text' => 'Uniform, protective vest, mask/goggles, paintball marker/gun, 30 bullets; including instructor, ticket, First Aid Kit / Safety equipment.',
            'age_text' => 'Min. 13 years old',
            'duration_text' => 'Until bullets run out',
            'price_note_template' => '(min :min pax)',
        ],

        'team-building' => [
            'title' => 'TEAM BUILDING',
            'fallback_images' => ['images/aktivitas-galeri/teambuilding.jpeg'],
            'facilities_text' => 'Equipment, including instructor, sound system & First Aid Kit/Safety equipment.',
            'age_text' => 'Min. 11 years old',
            'duration_text' => '~120 minutes',
        ],
    ],

    'information' => [
        'pickup' => [
            'title' => 'PICKUP & DROP-OFF SERVICE',
            'body_template' => 'Except for White Water Rafting & Offroad, pickup/drop-off fee from the camping area to the outbound arena is charged at :price/car, maximum 10 people.',
        ],
        'cancellation' => [
            'title' => 'CANCELLATION',
            'body' => 'For Team Building & Offroad, registration must be made no later than D‑3 (3 days before the event). If cancellation is made on the day of the event, 50% of the fee is non-refundable. Refunds are processed within a maximum of 14 business days.',
        ],
        'insurance' => [
            'title' => 'INSURANCE',
            'intro' => 'All participants of White Water Rafting, Paintball, Flying Fox, Offroad, ATV activities are covered by insurance:',
            'items' => [
                ['label' => 'Death not caused by accident', 'amount' => 5000000],
                ['label' => 'Death caused by accident', 'amount' => 15000000],
                ['label' => 'Permanent disability due to accident (max)', 'amount' => 20000000],
                ['label' => 'Medical treatment due to accident (max)', 'amount' => 1000000],
            ],
        ],
    ],

    'around_cards' => [
        [
            'image' => 'images/aktivitas-galeri/aktivitas1.png',
            'title' => 'Jogging with Fresh Air',
            'description' => 'Enjoy a refreshing jog surrounded by pine trees and mountain breeze — perfect for relaxation and staying fit.',
        ],
        [
            'image' => 'images/aktivitas-galeri/aktivitas2.jpg',
            'title' => 'Situ Cileunca',
            'description' => 'A beautiful lake for boating activities, light rafting, and peaceful moments by the water. Located ±3km from the camping area.',
        ],
        [
            'image' => 'images/aktivitas-galeri/aktivitas3.jpg',
            'title' => 'Sunrise Point Cukul',
            'description' => 'Sunrise spot above tea plantations & misty hills — a must-visit location for photography enthusiasts. ±12km from the camping area.',
        ],
        [
            'image' => 'images/aktivitas-galeri/aktivitas4.webp',
            'title' => 'Recreation Area',
            'description' => 'A popular destination with family rides and interesting photo spots, located about ±10km from the camping area.',
        ],
        [
            'image' => 'images/aktivitas-galeri/aktivitas5.jpg',
            'title' => 'Tea and Coffee Plantation',
            'description' => 'Expansive green tea plantations ideal for walking, learning tea/coffee processing, and relaxing around the camping area.',
        ],
        [
            'image' => 'images/aktivitas-galeri/aktivitas6.jpg',
            'title' => 'Hot Springs Area',
            'description' => 'Natural hot spring pools in the mountain area — the perfect place to relax after outdoor activities. ±13km from the camping area.',
        ],
    ],
];
