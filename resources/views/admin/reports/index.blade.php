@extends('layouts.admin')

@section('title', 'Marketplace Intelligence & Reports — MarketLink')
@section('page-title', 'Financial Analytics & Reports')

@section('content')
<div class="d-flex flex-column gap-4">

  {{-- Filter Toolbar --}}
  <div class="portal-card">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle);">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-sliders" style="color: var(--color-secondary); font-size: 1rem;"></i>
        <span style="font-size: 0.88rem; font-weight: 700; color: var(--color-dark);">Analytics Filter Range</span>
      </div>
      <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['print' => 1])) }}" target="_blank"
        class="btn btn-sm rounded-pill d-inline-flex align-items-center gap-2"
        style="border: 1.5px solid var(--color-border); background: var(--color-bg); color: var(--color-text-muted); font-size: 0.8rem; font-weight: 600; padding: 0.4rem 1rem;">
        <i class="bi bi-printer"></i> Print / Export PDF
      </a>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label-lux">Start Date</label>
        <input type="date" name="start_date" class="form-control form-control-lux" value="{{ $startDate }}">
      </div>
      <div class="col-md-3">
        <label class="form-label-lux">End Date</label>
        <input type="date" name="end_date" class="form-control form-control-lux" value="{{ $endDate }}">
      </div>
      <div class="col-md-3">
        <label class="form-label-lux">Market Location</label>
        <select name="market_id" class="form-select form-select-lux">
          <option value="">All Active Markets</option>
          @foreach($markets as $m)
            <option value="{{ $m->id }}" {{ $selectedMarketId == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn rounded-pill py-2 flex-grow-1" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.88rem; border: none;">
          <i class="bi bi-sliders me-1"></i>Apply Filter
        </button>
        <a href="{{ route('admin.reports.index') }}" class="btn rounded-pill px-3" style="border: 1.5px solid var(--color-border); background: var(--color-bg); color: var(--color-text-muted); font-size: 0.88rem;">Reset</a>
      </div>
    </form>
  </div>

  {{-- KPI Highlights --}}
  <div class="row g-4">
    <div class="col-md-4">
      <div class="portal-card h-100" style="border-left: 4px solid var(--color-info, #3B82F6) !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--color-text-muted);">Total Pre-Orders</span>
          <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(59,130,246,0.1); color: #2563EB; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-basket3-fill"></i>
          </div>
        </div>
        <div style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 800; color: var(--color-dark); letter-spacing: -0.02em; line-height: 1;">
          {{ number_format($totalOrdersCount) }}
        </div>
        <div style="font-size: 0.76rem; color: var(--color-text-muted); margin-top: 0.35rem;">All reservation records in period</div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="portal-card h-100" style="border-left: 4px solid var(--color-success) !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--color-text-muted);">Fulfillment Rate</span>
          <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-success-soft); color: var(--color-success); display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>
        <div style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 800; color: var(--color-success); letter-spacing: -0.02em; line-height: 1;">
          {{ number_format($completedOrdersCount) }}
        </div>
        <div style="font-size: 0.76rem; color: var(--color-text-muted); margin-top: 0.35rem;">Completed pickups fulfilled at stalls</div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="portal-card h-100" style="background: linear-gradient(135deg, var(--color-sage-soft) 0%, rgba(237,243,240,0.5) 100%); border: 1px solid rgba(22,132,91,0.15) !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--color-secondary);">Gross Volume (GMV)</span>
          <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-success-soft); color: var(--color-success); display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-cash-stack"></i>
          </div>
        </div>
        <div style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 800; color: var(--color-primary); letter-spacing: -0.02em; line-height: 1;">
          PKR {{ number_format($totalRevenue, 2) }}
        </div>
        <div style="font-size: 0.76rem; color: var(--color-secondary); margin-top: 0.35rem;">Circulated to local producers</div>
      </div>
    </div>
  </div>

  {{-- Breakdowns Grid --}}
  <div class="row g-4">
    {{-- Market Revenue --}}
    <div class="col-lg-6">
      <div class="portal-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle);">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-geo-alt-fill" style="color: var(--color-secondary);"></i>
            <span style="font-family: var(--font-serif); font-size: 1rem; font-weight: 700; color: var(--color-dark);">Revenue by Market Venue</span>
          </div>
          <span style="font-size: 0.72rem; font-weight: 600; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.65rem; border-radius: 100px;">Venues</span>
        </div>

        @if($revenueByMarket->isEmpty())
          <div class="text-center py-5" style="color: var(--color-text-muted); font-size: 0.88rem;">No transactions found for the selected filter parameters.</div>
        @else
          <div class="table-responsive">
            <table class="table-lux table mb-0">
              <thead>
                <tr>
                  <th style="padding-left: 1rem;">Market</th>
                  <th class="text-center">Pre-Orders</th>
                  <th class="text-end" style="padding-right: 1rem;">Volume</th>
                </tr>
              </thead>
              <tbody>
                @foreach($revenueByMarket as $m)
                  <tr>
                    <td style="padding-left: 1rem; font-weight: 600; color: var(--color-dark); font-size: 0.9rem;">{{ $m->name }}</td>
                    <td class="text-center">
                      <span style="font-size: 0.78rem; font-weight: 700; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.6rem; border-radius: 100px;">{{ $m->orders_count }}</span>
                    </td>
                    <td class="text-end" style="padding-right: 1rem; font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 0.95rem;">
                      PKR {{ number_format($m->total_revenue ?? 0, 2) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    {{-- Top Farmers --}}
    <div class="col-lg-6">
      <div class="portal-card h-100">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle);">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-award-fill" style="color: #D97706;"></i>
            <span style="font-family: var(--font-serif); font-size: 1rem; font-weight: 700; color: var(--color-dark);">Top Performing Growers</span>
          </div>
          <span style="font-size: 0.72rem; font-weight: 600; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.65rem; border-radius: 100px;">Producers</span>
        </div>

        @if($topFarmers->isEmpty())
          <div class="text-center py-5" style="color: var(--color-text-muted); font-size: 0.88rem;">No producer sales recorded in this interval.</div>
        @else
          <div class="table-responsive">
            <table class="table-lux table mb-0">
              <thead>
                <tr>
                  <th style="padding-left: 1rem;">Grower / Stall</th>
                  <th class="text-center">Orders</th>
                  <th class="text-end" style="padding-right: 1rem;">Gross Sales</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topFarmers as $f)
                  <tr>
                    <td style="padding-left: 1rem;">
                      <div style="font-weight: 600; color: var(--color-dark); font-size: 0.9rem;">{{ $f->stall_name }}</div>
                      <div style="font-size: 0.74rem; color: var(--color-text-muted);">{{ $f->contact_person }}</div>
                    </td>
                    <td class="text-center">
                      <span style="font-size: 0.78rem; font-weight: 700; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.6rem; border-radius: 100px;">{{ $f->orders_count }}</span>
                    </td>
                    <td class="text-end" style="padding-right: 1rem; font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 0.95rem;">
                      PKR {{ number_format($f->total_sales ?? 0, 2) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    {{-- Top Products Table --}}
    <div class="col-12">
      <div class="portal-card overflow-hidden">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3" style="border-bottom: 1px solid var(--color-border-subtle);">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-fire" style="color: var(--color-danger);"></i>
            <span style="font-family: var(--font-serif); font-size: 1rem; font-weight: 700; color: var(--color-dark);">High-Demand Harvest Varieties</span>
          </div>
          <span style="font-size: 0.8rem; color: var(--color-text-muted);">Ranked by aggregate reservation units</span>
        </div>

        @if($topProducts->isEmpty())
          <div class="text-center py-5" style="color: var(--color-text-muted); font-size: 0.88rem;">No individual produce records found for this period.</div>
        @else
          <div class="table-responsive">
            <table class="table-lux table mb-0">
              <thead>
                <tr>
                  <th style="padding-left: 1.5rem;">Rank & Variety</th>
                  <th>Category</th>
                  <th class="text-end" style="padding-right: 1.5rem;">Total Units Reserved</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topProducts as $idx => $p)
                  <tr>
                    <td style="padding-left: 1.5rem;">
                      <div class="d-flex align-items-center gap-3">
                        <span style="width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.78rem; flex-shrink: 0; background: {{ $idx === 0 ? 'var(--color-warning-soft)' : 'var(--color-bg)' }}; color: {{ $idx === 0 ? '#925D07' : 'var(--color-text-muted)' }}; border: 1px solid {{ $idx === 0 ? 'rgba(216,155,61,0.3)' : 'var(--color-border)' }};">
                          {{ $idx + 1 }}
                        </span>
                        <div>
                          <span style="font-weight: 700; color: var(--color-dark); font-size: 0.9rem;">{{ $p->name }}</span>
                          @if($p->is_organic ?? false)
                            <span style="font-size: 0.68rem; font-weight: 700; background: var(--color-success-soft); color: var(--color-success); border: 1px solid rgba(61,139,98,0.25); border-radius: 100px; padding: 0.12rem 0.5rem; margin-left: 0.35rem;">Organic</span>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>
                      <span style="font-size: 0.76rem; font-weight: 600; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.65rem; border-radius: 100px;">
                        {{ $p->category->name ?? 'Standard' }}
                      </span>
                    </td>
                    <td class="text-end" style="padding-right: 1.5rem; font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">
                      {{ number_format($p->units_ordered) }} <span style="font-weight: 400; font-size: 0.8rem; color: var(--color-text-muted); font-family: var(--font-sans);">units</span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection
