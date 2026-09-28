<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;

class AiAssistantService
{
    public function ask(string $prompt): array
    {
        $query = strtolower(trim($prompt));
        $response = '';
        $suggestions = [];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $foundDay = null;
        foreach ($days as $day) {
            if (str_contains($query, $day)) {
                $foundDay = ucfirst($day);
                break;
            }
        }

        $matchedCategory = null;
        $categories = Category::all();
        foreach ($categories as $cat) {
            if (str_contains($query, strtolower($cat->name)) || (str_contains($query, 'veg') && str_contains(strtolower($cat->name), 'veg'))) {
                $matchedCategory = $cat;
                break;
            }
        }

        if (str_contains($query, 'market') || str_contains($query, 'where') || str_contains($query, 'location') || $foundDay) {
            $marketsQuery = Market::where('status', 'active');
            if ($foundDay) {
                $marketsQuery->whereJsonContains('operating_days', $foundDay);
            }
            $markets = $marketsQuery->take(4)->get();

            if ($markets->isNotEmpty()) {
                $response .= $foundDay
                    ? "Here are the farmers markets operating on **{$foundDay}**:\n\n"
                    : "Here are some of our community farmers markets:\n\n";

                foreach ($markets as $m) {
                    $daysList = is_array($m->operating_days) ? implode(', ', $m->operating_days) : $m->operating_days;
                    $response .= "🌿 **{$m->name}**\n";
                    $response .= "📍 {$m->address}\n";
                    $response .= "⏰ Operating: {$daysList} ({$m->opening_time} - {$m->closing_time})\n";
                    $response .= "👉 [Explore Market](" . route('markets.show', $m->id) . ")\n\n";
                }
                $suggestions[] = 'Show all markets';
            }
        }

        if ($matchedCategory || str_contains($query, 'product') || str_contains($query, 'organic') || str_contains($query, 'fresh') || str_contains($query, 'buy') || str_contains($query, 'price')) {
            $productsQuery = Product::with(['farmer', 'category'])
                ->where('availability_status', '!=', 'sold_out');

            if ($matchedCategory) {
                $productsQuery->where('category_id', $matchedCategory->id);
            } else {
                $keywords = ['apple', 'tomato', 'potato', 'egg', 'honey', 'milk', 'cheese', 'bread', 'carrot', 'spinach', 'berry', 'lettuce'];
                foreach ($keywords as $kw) {
                    if (str_contains($query, $kw)) {
                        $productsQuery->where('name', 'like', "%{$kw}%");
                        break;
                    }
                }
            }

            $products = $productsQuery->take(4)->get();

            if ($products->isNotEmpty()) {
                $response .= ($matchedCategory ? "Fresh **{$matchedCategory->name}** available for pre-order:\n\n" : "Here are top available farm-fresh items:\n\n");
                foreach ($products as $p) {
                    $response .= "🧺 **{$p->name}** — \${$p->price} / {$p->unit}\n";
                    $response .= "👨‍🌾 Sold by **{$p->farmer->stall_name}** | In stock: {$p->quantity} {$p->unit}\n";
                    $response .= "👉 [View Product & Reserve](" . route('products.show', $p->id) . ")\n\n";
                }
                $suggestions[] = 'Browse all fresh produce';
            }
        }

        if (str_contains($query, 'farmer') || str_contains($query, 'grower') || str_contains($query, 'stall') || str_contains($query, 'who')) {
            $farmers = Farmer::where('approval_status', 'approved')->with('markets')->take(3)->get();
            if ($farmers->isNotEmpty()) {
                $response .= "👨‍🌾 **Featured Local Farmers & Stalls**:\n\n";
                foreach ($farmers as $f) {
                    $response .= "⭐ **{$f->stall_name}** (Rating: {$f->average_rating} / 5)\n";
                    $response .= "Contact: {$f->contact_person}\n";
                    $response .= "Pickup Windows: {$f->pickup_windows}\n";
                    $response .= "👉 [View Farmer Profile](" . route('farmers.show', $f->id) . ")\n\n";
                }
                $suggestions[] = 'Browse all local farmers';
            }
        }

        if (str_contains($query, 'pickup') || str_contains($query, 'order') || str_contains($query, 'how') || str_contains($query, 'pay') || str_contains($query, 'payment')) {
            $response .= "📋 **How Pre-Orders & Pickup Work on MarketLink**:\n\n";
            $response .= "1. **Browse & Reserve:** Choose fresh items from your local farmers and select your preferred pickup time slot.\n";
            $response .= "2. **In-Person Payment:** There are NO online cards or gateway charges! You pay cash or card in person directly at the farmer stall upon pickup.\n";
            $response .= "3. **Order Cutoff:** Farmers set cutoff windows (usually 12-24 hours prior) so they can harvest fresh items for you.\n";
            $response .= "4. **Pickup Location:** At the designated market stall. Bring your order confirmation number!\n\n";
            $suggestions[] = 'How to place a pre-order?';
        }

        if (empty(trim($response))) {
            $response = "Hello! I am your **eGreen Basket AI Assistant** 🌿\n\n" .
                "I can assist you with:\n" .
                "• **Markets & Schedules:** Ask _'Which markets are open on Saturday?'_\n" .
                "• **Produce Search:** Ask _'Where can I find fresh vegetables?'_\n" .
                "• **Local Farmers:** Ask _'Tell me about local honey or dairy farmers'_\n" .
                "• **Pickup & Pre-Orders:** Ask _'How does market pickup work?'_\n\n" .
                "What can I help you find today?";
            $suggestions = [
                'Where can I find fresh vegetables on Saturday?',
                'Which farmers markets are nearby?',
                'How does market pickup work?',
            ];
        }

        return [
            'reply' => $response,
            'suggestions' => array_slice(array_unique($suggestions), 0, 3),
        ];
    }
}
