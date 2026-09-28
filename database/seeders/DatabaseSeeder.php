<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Farmer;
use App\Models\Customer;
use App\Models\Market;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\PickupSlot;
use App\Models\Announcement;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ──────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Platform Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '+1 555 000 0001',
                'address'  => '1 Admin Way, Springfield',
                'status'   => 'active',
            ]
        );

        // ── Categories ──────────────────────────────────────────────────
        $cats = [
            ['name' => 'Vegetables',   'icon' => 'bi-basket2-fill',  'description' => 'Fresh seasonal vegetables from local farms'],
            ['name' => 'Fruits',        'icon' => 'bi-apple',         'description' => 'Seasonal fruits, berries and citrus'],
            ['name' => 'Herbs',         'icon' => 'bi-flower1',       'description' => 'Fresh cut herbs and dried herb bundles'],
            ['name' => 'Eggs & Dairy',  'icon' => 'bi-egg-fried',     'description' => 'Farm-fresh eggs, milk, cheese and butter'],
            ['name' => 'Honey & Jams',  'icon' => 'bi-box-seam-fill', 'description' => 'Raw honey, jams, preserves and pickles'],
            ['name' => 'Grains',        'icon' => 'bi-sun-fill',      'description' => 'Whole grains, flour, oats and legumes'],
            ['name' => 'Flowers',       'icon' => 'bi-flower3',       'description' => 'Seasonal cut flowers and bouquets'],
            ['name' => 'Seedlings',     'icon' => 'bi-tree-fill',     'description' => 'Vegetable and herb seedlings for home gardens'],
        ];

        $categoryIds = [];
        foreach ($cats as $c) {
            $cat = Category::firstOrCreate(
                ['name' => $c['name']],
                [
                    'name'        => $c['name'],
                    'slug'        => Str::slug($c['name']),
                    'icon'        => $c['icon'],
                    'description' => $c['description'],
                    'status'      => 'active',
                ]
            );
            $categoryIds[$c['name']] = $cat->id;
        }

        // ── Markets ─────────────────────────────────────────────────────
        // ── Markets ─────────────────────────────────────────────────────
        $markets = [
            [
                'name'           => 'Lahore Model Town Farmers Market',
                'slug'           => 'lahore-model-town-farmers-market',
                'address'        => 'Model Town Park, Central Circular Road',
                'city'           => 'Lahore',
                'description'    => 'Lahore\'s premier organic weekend fair featuring over 40 fresh produce stalls, artisan dairy, pure Sidr honey, and farm-fresh herbs directly from Punjab growers.',
                'operating_days' => ['Saturday', 'Sunday'],
                'opening_time'   => '08:00',
                'closing_time'   => '14:00',
                'latitude'       => 31.4822,
                'longitude'      => 74.3216,
                'status'         => 'active',
                'image'          => 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'           => 'Islamabad F-7 Organic Fair',
                'slug'           => 'islamabad-f7-organic-fair',
                'address'        => 'F-7 Markaz Grounds, Bhittai Road',
                'city'           => 'Islamabad',
                'description'    => 'Exclusively certified organic growers and zero-spray produce harvested from the Margalla foothill farms. Convenient midweek and Saturday morning pickups.',
                'operating_days' => ['Wednesday', 'Saturday'],
                'opening_time'   => '07:00',
                'closing_time'   => '13:00',
                'latitude'       => 33.7215,
                'longitude'      => 73.0565,
                'status'         => 'active',
                'image'          => 'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name'           => 'Karachi Clifton Heritage Market',
                'slug'           => 'karachi-clifton-heritage-market',
                'address'        => 'Clifton Block 4 Promenade, Beach Avenue',
                'city'           => 'Karachi',
                'description'    => 'A vibrant coastal community market with early morning fresh harvests, heritage citrus fruits, organic dates, and coastal farm produce.',
                'operating_days' => ['Sunday'],
                'opening_time'   => '09:00',
                'closing_time'   => '15:00',
                'latitude'       => 24.8138,
                'longitude'      => 67.0302,
                'status'         => 'active',
                'image'          => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $marketModels = [];
        foreach ($markets as $m) {
            $market = Market::firstOrCreate(
                ['slug' => $m['slug']],
                $m
            );
            $marketModels[] = $market;
        }

        // ── Farmer Users ────────────────────────────────────────────────
        $farmersData = [
            [
                'user' => [
                    'name'     => 'John Green Valley',
                    'email'    => 'farmer@example.com',
                    'phone'    => '+92 300 555 1122',
                    'address'  => 'Plot 45, Multan Road Farm Belt, Lahore',
                    'password' => Hash::make('password'),
                    'role'     => 'farmer',
                    'status'   => 'active',
                ],
                'farmer' => [
                    'stall_name'         => 'Green Valley Organics',
                    'contact_person'     => 'John Miller',
                    'phone'              => '+92 300 555 1122',
                    'address'            => 'Plot 45, Multan Road Farm Belt, Lahore',
                    'bio'                => 'Third-generation family farm growing heirloom vegetables and heritage tomatoes using certified organic, soil-first practices in Punjab.',
                    'approval_status'    => 'approved',
                    'pickup_windows'     => '8:00 AM - 12:00 PM',
                    'operating_days'     => ['Saturday', 'Sunday'],
                    'order_cutoff_hours' => 24,
                    'profile_image'      => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                    'banner_image'       => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1200&q=80',
                ],
                'markets' => [0, 1],
            ],
            [
                'user' => [
                    'name'     => 'Sarah Sunshine Acres',
                    'email'    => 'farmer2@example.com',
                    'phone'    => '+92 321 444 3322',
                    'address'  => '5 Orchard Road, Pattoki Fruit Farms, Kasur',
                    'password' => Hash::make('password'),
                    'role'     => 'farmer',
                    'status'   => 'active',
                ],
                'farmer' => [
                    'stall_name'         => 'Sunshine Acres',
                    'contact_person'     => 'Sarah Bloom',
                    'phone'              => '+92 321 444 3322',
                    'address'            => '5 Orchard Road, Pattoki Fruit Farms, Kasur',
                    'bio'                => 'We grow over 30 varieties of heritage orchard fruits, sweet berries, and farm-harvested raw honey near Kasur.',
                    'approval_status'    => 'approved',
                    'pickup_windows'     => '9:00 AM - 1:00 PM',
                    'operating_days'     => ['Saturday', 'Wednesday'],
                    'order_cutoff_hours' => 18,
                    'profile_image'      => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                    'banner_image'       => 'https://images.unsplash.com/photo-1500651230702-0e2d8a49d4ad?auto=format&fit=crop&w=1200&q=80',
                ],
                'markets' => [0, 2],
            ],
            [
                'user' => [
                    'name'     => 'Mike Riverside Herbs',
                    'email'    => 'farmer3@example.com',
                    'phone'    => '+92 333 777 8899',
                    'address'  => '20 Organic Herb Way, Bari Imam Belt, Islamabad',
                    'password' => Hash::make('password'),
                    'role'     => 'farmer',
                    'status'   => 'active',
                ],
                'farmer' => [
                    'stall_name'         => 'Riverside Herb Co.',
                    'contact_person'     => 'Mike Waters',
                    'phone'              => '+92 333 777 8899',
                    'address'            => '20 Organic Herb Way, Bari Imam Belt, Islamabad',
                    'bio'                => 'Specialist herb and microgreen growers producing over 50 varieties of culinary and medicinal herbs in the Margalla foothills.',
                    'approval_status'    => 'approved',
                    'pickup_windows'     => '7:00 AM - 11:00 AM',
                    'operating_days'     => ['Wednesday', 'Saturday'],
                    'order_cutoff_hours' => 12,
                    'profile_image'      => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                    'banner_image'       => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=1200&q=80',
                ],
                'markets' => [1],
            ],
            [
                'user' => [
                    'name'     => 'Emma Pending Farmer',
                    'email'    => 'pending@example.com',
                    'phone'    => '+92 301 999 4455',
                    'address'  => 'Chak 12-RB, Canal Road Farm Estate, Faisalabad',
                    'password' => Hash::make('password'),
                    'role'     => 'farmer',
                    'status'   => 'active',
                ],
                'farmer' => [
                    'stall_name'         => 'New Horizon Microgreens',
                    'contact_person'     => 'Emma Newfield',
                    'phone'              => '+92 301 999 4455',
                    'address'            => 'Chak 12-RB, Canal Road Farm Estate, Faisalabad',
                    'bio'                => 'A new urban farm project focusing on hydroponic microgreens, edible flowers, and gourmet salad mixes.',
                    'approval_status'    => 'pending',
                    'pickup_windows'     => '9:00 AM - 1:00 PM',
                    'operating_days'     => ['Saturday'],
                    'order_cutoff_hours' => 24,
                ],
                'markets' => [],
            ],
        ];

        $farmerModels = [];
        foreach ($farmersData as $fd) {
            $user = User::firstOrCreate(['email' => $fd['user']['email']], $fd['user']);
            $farmer = Farmer::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($fd['farmer'], ['user_id' => $user->id])
            );
            foreach ($fd['markets'] as $mIdx) {
                if (isset($marketModels[$mIdx])) {
                    $farmer->markets()->syncWithoutDetaching([$marketModels[$mIdx]->id]);
                }
            }
            $farmerModels[] = $farmer;
        }

        // ── Customer Users ──────────────────────────────────────────────
        $customersData = [
            ['name' => 'Alice Customer', 'email' => 'customer@example.com', 'phone' => '+92 302 123 4567', 'address' => '42 Gulberg III, Main Boulevard, Lahore'],
            ['name' => 'Bob Shopper',    'email' => 'bob@example.com',      'phone' => '+92 312 987 6543', 'address' => '8 Street 14, Sector F-10/2, Islamabad'],
        ];

        $customerModels = [];
        foreach ($customersData as $cd) {
            $user = User::firstOrCreate(
                ['email' => $cd['email']],
                array_merge($cd, ['password' => Hash::make('password'), 'role' => 'customer', 'status' => 'active'])
            );
            $customer = Customer::firstOrCreate(['user_id' => $user->id], ['user_id' => $user->id]);
            $customerModels[] = $customer;
        }

        // -- Products ----------------------------------------------------------------
        $productsData = [
            // == Green Valley Organics (farmer index 0) - 6 products ==
            [
                'name'        => 'Heirloom Tomatoes',
                'cat'         => 'Vegetables',
                'price'       => 5.99,
                'unit'        => 'kg',
                'stock'       => 35,
                'farmer'      => 0,
                'featured'    => true,
                'description' => 'Vibrant assortment of yellow, striped, and deep-purple heirloom tomatoes. Hand-picked at peak ripeness for rich, old-fashioned sweetness.',
                'image'       => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Baby Spinach',
                'cat'         => 'Vegetables',
                'price'       => 4.50,
                'unit'        => 'bunch',
                'stock'       => 50,
                'farmer'      => 0,
                'featured'    => false,
                'description' => 'Crisp, tender organic baby spinach leaves. Triple-washed, cold-stored, and ready for vibrant salads and healthy cooking.',
                'image'       => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Zucchini',
                'cat'         => 'Vegetables',
                'price'       => 3.99,
                'unit'        => 'kg',
                'stock'       => 40,
                'farmer'      => 0,
                'featured'    => false,
                'description' => 'Young, slender green zucchini harvested before sunrise. Ideal for grilling, sauteing, or spiralizing.',
                'image'       => 'https://images.unsplash.com/photo-1590779033100-9f60a05a013d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Mixed Lettuce Head',
                'cat'         => 'Vegetables',
                'price'       => 3.50,
                'unit'        => 'piece',
                'stock'       => 35,
                'farmer'      => 0,
                'featured'    => false,
                'description' => 'Crispy butterhead and oakleaf lettuces with roots still attached for extended kitchen freshness.',
                'image'       => 'https://images.unsplash.com/photo-1556801712-76c8eb07bbc9?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Pasture-Raised Free Range Eggs',
                'cat'         => 'Eggs & Dairy',
                'price'       => 6.50,
                'unit'        => 'dozen',
                'stock'       => 60,
                'farmer'      => 0,
                'featured'    => true,
                'description' => 'Rich golden-yolk eggs from heritage hens roaming openly on certified organic green pastures.',
                'image'       => 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Punjab Desi Bhindi (Okra)',
                'cat'         => 'Vegetables',
                'price'       => 2.80,
                'unit'        => 'kg',
                'stock'       => 55,
                'farmer'      => 0,
                'featured'    => false,
                'description' => 'Tender young okra pods freshly plucked from Punjab fields. Non-GMO, grown with natural compost — perfect for daal and sabzi.',
                'image'       => 'https://images.unsplash.com/photo-1638960720571-e9f97fad58ac?auto=format&fit=crop&w=600&q=80',
            ],

            // == Sunshine Acres (farmer index 1) - 6 products ==
            [
                'name'        => 'Sweet Field Strawberries',
                'cat'         => 'Fruits',
                'price'       => 6.50,
                'unit'        => 'punnet',
                'stock'       => 45,
                'farmer'      => 1,
                'featured'    => true,
                'description' => 'Sun-warmed, crimson red strawberries overflowing with natural sweetness and aromatic fragrance.',
                'image'       => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Golden Orchard Peaches',
                'cat'         => 'Fruits',
                'price'       => 7.99,
                'unit'        => 'kg',
                'stock'       => 25,
                'farmer'      => 1,
                'featured'    => true,
                'description' => 'Velvety skin, melt-in-your-mouth juicy flesh with honeyed floral notes. Picked tree-ripe.',
                'image'       => 'https://images.unsplash.com/photo-1595124245030-41448b199d6d?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Raw Wildflower Honey',
                'cat'         => 'Honey & Jams',
                'price'       => 14.00,
                'unit'        => 'jar',
                'stock'       => 20,
                'farmer'      => 1,
                'featured'    => true,
                'description' => '100% pure raw wildflower honey extracted cold from hives positioned around apple and peach orchards.',
                'image'       => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Organic Blueberries',
                'cat'         => 'Fruits',
                'price'       => 8.99,
                'unit'        => 'punnet',
                'stock'       => 30,
                'farmer'      => 1,
                'featured'    => false,
                'description' => 'Plump, firm blueberries bursting with antioxidants, picked fresh from bushes in Fruitvale.',
                'image'       => 'https://images.unsplash.com/photo-1498557850523-fd3d118b962e?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Rainbow Cherry Tomatoes',
                'cat'         => 'Vegetables',
                'price'       => 4.99,
                'unit'        => 'punnet',
                'stock'       => 28,
                'farmer'      => 1,
                'featured'    => false,
                'description' => 'Bite-sized sweet candy bursts in ruby red, amber gold, and forest green.',
                'image'       => 'https://images.unsplash.com/photo-1546470427-0d4db154ceb7?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Kasur Kinnow Oranges',
                'cat'         => 'Fruits',
                'price'       => 3.50,
                'unit'        => 'kg',
                'stock'       => 80,
                'farmer'      => 1,
                'featured'    => false,
                'description' => 'Juicy, seedless Kinnow oranges from Kasur famous orchards. Rich in Vitamin C — a Pakistani winter staple.',
                'image'       => 'https://images.unsplash.com/photo-1548168376-8a69f000b8e2?auto=format&fit=crop&w=600&q=80',
            ],

            // == Riverside Herb Co. (farmer index 2) - 6 products ==
            [
                'name'        => 'Fresh Sweet Genovese Basil',
                'cat'         => 'Herbs',
                'price'       => 2.99,
                'unit'        => 'bunch',
                'stock'       => 60,
                'farmer'      => 2,
                'featured'    => true,
                'description' => 'Generous aromatic bundle of classic broad-leaf Genovese basil. Unmatched for homemade pesto and caprese.',
                'image'       => 'https://images.unsplash.com/photo-1608686207856-001b95cf60ca?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Woodland Rosemary Bundle',
                'cat'         => 'Herbs',
                'price'       => 2.50,
                'unit'        => 'bunch',
                'stock'       => 40,
                'farmer'      => 2,
                'featured'    => false,
                'description' => 'Pine-scented, woody culinary rosemary sprigs full of essential oils. Great for roasted potatoes and focaccia.',
                'image'       => 'https://images.unsplash.com/photo-1515586838455-8f8f940d6853?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => "Chef's Culinary Herb Bouquet",
                'cat'         => 'Herbs',
                'price'       => 4.50,
                'unit'        => 'bunch',
                'stock'       => 30,
                'farmer'      => 2,
                'featured'    => false,
                'description' => 'Hand-tied bouquet combining flat parsley, garden thyme, lemon thyme, winter savory, and french tarragon.',
                'image'       => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Handcrafted Chamomile & Lavender Tea',
                'cat'         => 'Herbs',
                'price'       => 9.99,
                'unit'        => 'jar',
                'stock'       => 15,
                'farmer'      => 2,
                'featured'    => false,
                'description' => 'Whole dried German chamomile blossoms blended with culinary French lavender buds and mint. Naturally caffeine-free.',
                'image'       => 'https://images.unsplash.com/photo-1597481499750-3e6b22637e12?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Organic Spearmint & Pudina Bundle',
                'cat'         => 'Herbs',
                'price'       => 1.99,
                'unit'        => 'bunch',
                'stock'       => 70,
                'farmer'      => 2,
                'featured'    => true,
                'description' => 'Fresh-cut green spearmint and pudina (peppermint) — a staple in Pakistani raita, chutneys, and chai. Harvested same morning.',
                'image'       => 'https://images.unsplash.com/photo-1628557010474-31795b6a4d56?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name'        => 'Margalla Green Coriander (Dhaniya)',
                'cat'         => 'Herbs',
                'price'       => 1.50,
                'unit'        => 'bunch',
                'stock'       => 90,
                'farmer'      => 2,
                'featured'    => false,
                'description' => 'Bushy, bright green fresh coriander grown in the clean Margalla foothill soil. Essential garnish for every Pakistani dish.',
                'image'       => 'https://images.unsplash.com/photo-1615485291234-9d694218aeb3?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $productModels = [];
        foreach ($productsData as $p) {
            $farmer = $farmerModels[$p['farmer']];
            $product = Product::firstOrCreate(
                ['slug' => Str::slug($p['name'] . '-' . $farmer->id)],
                [
                    'farmer_id'               => $farmer->id,
                    'category_id'             => $categoryIds[$p['cat']],
                    'market_id'               => $farmer->markets->first()?->id,
                    'name'                    => $p['name'],
                    'slug'                    => Str::slug($p['name'] . '-' . $farmer->id),
                    'description'             => $p['description'],
                    'price'                   => $p['price'],
                    'unit'                    => $p['unit'],
                    'quantity'                => $p['stock'],
                    'default_weekly_quantity' => $p['stock'],
                    'availability_status'     => 'available',
                    'is_featured'             => $p['featured'],
                ]
            );

            // Add primary image
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'image_path' => $p['image']],
                ['is_primary' => true]
            );

            $productModels[] = $product;
        }

        // ── Pickup Slots ────────────────────────────────────────────────
        // Create pickup slots for the next 2 upcoming market weekends
        foreach ($farmerModels as $farmer) {
            if ($farmer->approval_status !== 'approved') {
                continue;
            }

            foreach ($farmer->markets as $market) {
                // Generate next Saturday and Sunday slots
                for ($week = 1; $week <= 2; $week++) {
                    $satDate = Carbon::now()->startOfWeek()->addWeeks($week - 1)->addDays(5)->format('Y-m-d');
                    $sunDate = Carbon::now()->startOfWeek()->addWeeks($week - 1)->addDays(6)->format('Y-m-d');

                    foreach ([$satDate, $sunDate] as $pDate) {
                        PickupSlot::firstOrCreate(
                            [
                                'farmer_id'   => $farmer->id,
                                'market_id'   => $market->id,
                                'pickup_date' => $pDate,
                                'start_time'  => '08:30',
                            ],
                            [
                                'end_time'     => '11:00',
                                'capacity'     => 20,
                                'booked_count' => 2,
                                'cutoff_time'  => Carbon::parse($pDate . ' 08:30')->subHours($farmer->order_cutoff_hours ?? 24),
                                'is_active'    => true,
                            ]
                        );

                        PickupSlot::firstOrCreate(
                            [
                                'farmer_id'   => $farmer->id,
                                'market_id'   => $market->id,
                                'pickup_date' => $pDate,
                                'start_time'  => '11:00',
                            ],
                            [
                                'end_time'     => '13:30',
                                'capacity'     => 20,
                                'booked_count' => 1,
                                'cutoff_time'  => Carbon::parse($pDate . ' 11:00')->subHours($farmer->order_cutoff_hours ?? 24),
                                'is_active'    => true,
                            ]
                        );
                    }
                }
            }
        }

        // ── Demo Orders & Reviews ─────────────────────────────────────────
        if (!empty($customerModels) && !empty($productModels)) {
            $alice = $customerModels[0];
            $bob   = $customerModels[1];

            // Order 1: Alice completed order from Green Valley Farm
            $order1 = Order::firstOrCreate(
                ['order_number' => 'ML-2026-000001'],
                [
                    'customer_id'    => $alice->id,
                    'farmer_id'      => $farmerModels[0]->id,
                    'market_id'      => $marketModels[0]->id,
                    'pickup_date'    => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'status'         => 'COMPLETED',
                    'subtotal'       => 17.98,
                    'total'          => 17.98,
                    'payment_method' => 'Pay at Market Pickup (Cash)',
                    'notes'          => 'Please pack in eco-friendly paper bags if possible.',
                    'placed_at'      => Carbon::now()->subDays(4),
                    'completed_at'   => Carbon::now()->subDays(2),
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order1->id, 'product_id' => $productModels[0]->id],
                [
                    'product_name_snapshot' => $productModels[0]->name,
                    'unit_price_snapshot'   => $productModels[0]->price,
                    'unit_snapshot'         => $productModels[0]->unit,
                    'quantity'              => 2,
                    'subtotal'              => 11.98,
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order1->id, 'product_id' => $productModels[4]->id],
                [
                    'product_name_snapshot' => $productModels[4]->name,
                    'unit_price_snapshot'   => $productModels[4]->price,
                    'unit_snapshot'         => $productModels[4]->unit,
                    'quantity'              => 1,
                    'subtotal'              => 6.00,
                ]
            );

            // Order 2: Alice completed order from Sunshine Acres
            $order2 = Order::firstOrCreate(
                ['order_number' => 'ML-2026-000002'],
                [
                    'customer_id'    => $alice->id,
                    'farmer_id'      => $farmerModels[1]->id,
                    'market_id'      => $marketModels[0]->id,
                    'pickup_date'    => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'status'         => 'COMPLETED',
                    'subtotal'       => 20.50,
                    'total'          => 20.50,
                    'payment_method' => 'Pay at Market Pickup (Cash)',
                    'placed_at'      => Carbon::now()->subDays(5),
                    'completed_at'   => Carbon::now()->subDays(3),
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order2->id, 'product_id' => $productModels[5]->id],
                [
                    'product_name_snapshot' => $productModels[5]->name,
                    'unit_price_snapshot'   => $productModels[5]->price,
                    'unit_snapshot'         => $productModels[5]->unit,
                    'quantity'              => 1,
                    'subtotal'              => 6.50,
                ]
            );

            OrderItem::firstOrCreate(
                ['order_id' => $order2->id, 'product_id' => $productModels[7]->id],
                [
                    'product_name_snapshot' => $productModels[7]->name,
                    'unit_price_snapshot'   => $productModels[7]->price,
                    'unit_snapshot'         => $productModels[7]->unit,
                    'quantity'              => 1,
                    'subtotal'              => 14.00,
                ]
            );

            // Order 3: Bob pending order
            Order::firstOrCreate(
                ['order_number' => 'ML-2026-000003'],
                [
                    'customer_id'    => $bob->id,
                    'farmer_id'      => $farmerModels[0]->id,
                    'market_id'      => $marketModels[0]->id,
                    'pickup_date'    => Carbon::now()->addDays(2)->format('Y-m-d'),
                    'status'         => 'PLACED',
                    'subtotal'       => 10.49,
                    'total'          => 10.49,
                    'payment_method' => 'Pay at Market Pickup (Cash)',
                    'notes'          => 'Will arrive around 10:00 AM.',
                    'placed_at'      => Carbon::now(),
                ]
            );

            // Reviews associated with completed orders
            $sampleReviews = [
                [
                    'customer_id' => $alice->id,
                    'farmer_id'   => $farmerModels[0]->id,
                    'product_id'  => $productModels[0]->id,
                    'order_id'    => $order1->id,
                    'rating'      => 5,
                    'comment'     => 'The most flavorful heirloom tomatoes I have had in years! Beautiful colors and arrived neatly packed at the stall.',
                    'status'      => 'approved',
                ],
                [
                    'customer_id' => $alice->id,
                    'farmer_id'   => $farmerModels[1]->id,
                    'product_id'  => $productModels[5]->id,
                    'order_id'    => $order2->id,
                    'rating'      => 5,
                    'comment'     => 'Sweet and fragrant strawberries. My children devoured the entire punnet before we even drove home from the market!',
                    'status'      => 'approved',
                ],
            ];

            foreach ($sampleReviews as $sr) {
                Review::firstOrCreate(
                    [
                        'customer_id' => $sr['customer_id'],
                        'product_id'  => $sr['product_id'],
                    ],
                    $sr
                );
            }

            // Customer favorite relations
            $alice->favoriteProducts()->syncWithoutDetaching([$productModels[0]->id, $productModels[5]->id]);
            $alice->favoriteFarmers()->syncWithoutDetaching([$farmerModels[0]->id, $farmerModels[1]->id]);
        }

        // ── Announcements ───────────────────────────────────────────────
        $announcements = [
            [
                'title'        => '🌱 Welcome to MarketLink — eGreen Basket!',
                'content'      => 'We are thrilled to launch MarketLink, the eGreen Basket community pre-order platform! Browse fresh seasonal produce from verified local farmers, place pre-orders online, and pick up fresh at your local weekend market stall. Pay with cash on pickup — zero delivery fees and zero waste!',
                'target_role'  => 'all',
                'status'       => 'published',
                'published_at' => now(),
            ],
            [
                'title'        => '🌿 Spring Season Produce Harvest is Underway!',
                'content'      => 'Local growers have freshly listed spring specialties including heirloom greens, early strawberries, fresh chamomile, and tender baby zucchini. Remember to place orders at least 24 hours prior to market pickup.',
                'target_role'  => 'all',
                'status'       => 'published',
                'published_at' => now(),
            ],
            [
                'title'        => '📋 Farmer Stalls: Update Your Weekly Inventory',
                'content'      => 'A friendly reminder to all approved farmers to review and publish weekly stock quotas by Thursday 6:00 PM for the upcoming weekend markets.',
                'target_role'  => 'farmer',
                'status'       => 'published',
                'published_at' => now(),
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::firstOrCreate(['title' => $a['title']], $a);
        }

        $this->command->info('✅ MarketLink database seeded successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',    'admin@example.com',    'password'],
                ['Farmer 1', 'farmer@example.com',   'password'],
                ['Farmer 2', 'farmer2@example.com',  'password'],
                ['Farmer 3', 'farmer3@example.com',  'password'],
                ['Customer', 'customer@example.com', 'password'],
            ]
        );
    }
}
