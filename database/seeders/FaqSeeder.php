<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Seed the application's FAQ data.
     */
    public function run(): void
    {
        $faqs = [
            [
                'slug' => 'check-availability',
                'question' => 'How to check availability?',
                'answer' => '<p>To check tent availability updates, please visit the following link:</p><ul class="list-disc ml-5 mt-2 space-y-1"><li>Link: <a href="https://bit.ly/pineustilu-availabilities" target="_blank" rel="noopener" class="text-green-600 underline">bit.ly/pineustilu-availabilities</a><br><em>(You can scroll left or right to see availability)</em></li><li><strong>Green</strong> = Available</li><li><strong>Yellow</strong> = Available with special price</li><li><strong>Red</strong> = Fully booked</li></ul><p class="mt-3"><strong>Note:</strong> Availability can change quickly. For example, a tent may be available during the day but fully booked by evening or the next day, as bookings are open daily. Always check the calendar or close &amp; reopen the link to see the latest updates.</p>',
                'order_index' => 1,
            ],
            [
                'slug' => 'refundable-balance',
                'question' => 'We received an email about a refundable balance. Can it be saved first, or must it be refunded immediately?',
                'answer' => '<p>For overpayments, they can be saved first if you wish to use them for other additional orders. However, you can also request a refund.</p>',
                'order_index' => 2,
            ],
            [
                'slug' => 'play-in-river',
                'question' => 'Can we play in the river?',
                'answer' => '<p>Yes. Direct river access is only available in the public area of Pineus Tilu II.</p>',
                'order_index' => 3,
            ],
            [
                'slug' => 'best-views',
                'question' => 'Best views?',
                'answer' => '<p>Each deck and plot has its own unique features. Admin can only assist by providing documentation.</p>',
                'order_index' => 4,
            ],
            [
                'slug' => 'camp-as-couple',
                'question' => 'Can we camp as a couple?',
                'answer' => '<p>Sorry, unmarried couples are not permitted to stay together if there are only two of them.</p>',
                'order_index' => 5,
            ],
            [
                'slug' => 'riverside-location',
                'question' => 'Which ones are located by the riverside?',
                'answer' => '<p>All decks are located by the riverside. However, <strong>Deck 1, 2, 8, and 9 at Pineus Tilu I</strong>, as well as <strong>Cabin Deck at Pineus Tilu III VIP</strong>, are positioned at a higher elevation compared to other decks.</p>',
                'order_index' => 6,
            ],
            [
                'slug' => 'plot-vs-deck',
                'question' => 'What is the difference between plot and deck?',
                'answer' => '<p>"Deck" refers to areas at Pineus Tilu I, II, and III VIP, while "plot" refers to Pineus Tilu IV. The difference is only in the naming.</p>',
                'order_index' => 7,
            ],
            [
                'slug' => 'booking-down-payment',
                'question' => 'How much is the down payment (DP) for booking?',
                'answer' => '<p>For reservations, the payment system requires <strong>100% full payment</strong>. However, if you book a minimum of 9 tents, the down payment (DP) system is available. The initial payment in this case is <strong>25%</strong>.</p>',
                'order_index' => 8,
            ],
            [
                'slug' => 'guest-loyalty-program',
                'question' => 'GLP (Guest Loyalty Program)',
                'answer' => '<p><strong>Guest Loyalty Program</strong> is a special promo that can be claimed if you have camped here before. Simply show proof of your previous camping reservation to get the special promo price, available only on weekdays.</p>',
                'order_index' => 9,
            ],
            [
                'slug' => 'reservation-on-site',
                'question' => 'Can we make a reservation directly on-site?',
                'answer' => '<p>Yes, you can book a tent directly at the reception. However, tent availability will depend on the schedule shown at the following link:</p><p class="mt-2">Link: <a href="https://bit.ly/pineustilu-availabilities" target="_blank" rel="noopener" class="text-green-600 underline">bit.ly/pineustilu-availabilities</a></p>',
                'order_index' => 10,
            ],
            [
                'slug' => 'agent-price-per-tent',
                'question' => 'Are there agent prices per tent?',
                'answer' => '<p>For camping bookings of 9 tents or more, you are entitled to a <strong>5% cashback</strong> discount from the total tent/deck rental fee.</p>',
                'order_index' => 11,
            ],
            [
                'slug' => 'parking-distance',
                'question' => 'Is the distance from parking to tent far?',
                'answer' => '<p>The distance from the parking area to the camping area is approximately <strong>300 meters</strong>.</p>',
                'order_index' => 12,
            ],
            [
                'slug' => 'large-bus-access',
                'question' => 'Can large buses enter?',
                'answer' => '<p>Yes, small, medium, and large buses can enter. However, after passing the intersection to Pineus Tilu, there is usually escort assistance (both on arrival and departure) to avoid traffic jams when buses pass from opposite directions.</p><p class="mt-2">The escort fee is approximately <strong>IDR 200,000 per bus</strong>.</p>',
                'order_index' => 13,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['slug' => $faq['slug']],
                [
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'order_index' => $faq['order_index'],
                ]
            );
        }
    }
}
