@props(['category' => null, 'name' => null, 'size' => '0.9rem', 'class' => ''])

@php
  $catName = strtolower(is_object($category) ? ($category->name ?? '') : ($name ?? (is_string($category) ? $category : '')));
  
  $iconMap = [
    'vegetables' => ['icon' => 'bi-basket2-fill', 'color' => '#15803d', 'bg' => '#E8F5E9'],
    'fruits'     => ['icon' => 'bi-apple', 'color' => '#dc2626', 'bg' => '#FEE2E2'],
    'herbs'      => ['icon' => 'bi-flower1', 'color' => '#16a34a', 'bg' => '#DCFCE7'],
    'eggs'       => ['icon' => 'bi-egg-fried', 'color' => '#d97706', 'bg' => '#FEF3C7'],
    'dairy'      => ['icon' => 'bi-egg-fried', 'color' => '#d97706', 'bg' => '#FEF3C7'],
    'honey'      => ['icon' => 'bi-box-seam-fill', 'color' => '#b45309', 'bg' => '#FFEDD5'],
    'jams'       => ['icon' => 'bi-box-seam-fill', 'color' => '#b45309', 'bg' => '#FFEDD5'],
    'bakery'     => ['icon' => 'bi-cake2-fill', 'color' => '#9333ea', 'bg' => '#F3E8FF'],
    'grains'     => ['icon' => 'bi-sun-fill', 'color' => '#ca8a04', 'bg' => '#FEF9C3'],
    'flowers'    => ['icon' => 'bi-flower3', 'color' => '#db2777', 'bg' => '#FCE7F3'],
    'seedlings'  => ['icon' => 'bi-tree-fill', 'color' => '#047857', 'bg' => '#D1FAE5'],
    'organic'    => ['icon' => 'bi-patch-check-fill', 'color' => '#15803d', 'bg' => '#E8F5E9'],
  ];

  $match = null;
  foreach ($iconMap as $key => $val) {
    if (str_contains($catName, $key)) {
      $match = $val;
      break;
    }
  }

  if (!$match) {
    $match = ['icon' => 'bi-tag-fill', 'color' => '#15803d', 'bg' => '#E8F5E9'];
  }
@endphp

<span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0 {{ $class }}" style="width: 1.85em; height: 1.85em; background: {{ $match['bg'] }}; color: {{ $match['color'] }}; font-size: {{ $size }};" title="{{ is_object($category) ? $category->name : ($name ?? '') }}">
  <i class="bi {{ $match['icon'] }}"></i>
</span>
