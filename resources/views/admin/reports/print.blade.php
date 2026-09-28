<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MarketLink Report — {{ $startDate }} to {{ $endDate }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; font-size: 13px; }
    .report-header { border-bottom: 3px solid #2d7a38; padding-bottom: 1rem; margin-bottom: 1.5rem; }
    @media print { .no-print { display: none !important; } }
  </style>
</head>
<body class="p-4">

  <div class="report-header d-flex align-items-center justify-content-between">
    <div>
      <h3 class="fw-bold mb-0" style="color:#2d7a38;">🌿 eGreen Basket MarketLink</h3>
      <div class="text-muted small">Platform Analytics Report</div>
    </div>
    <div class="text-end small text-muted">
      <div>Period: <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></div>
      <div>Generated: {{ now()->format('M j, Y H:i') }}</div>
      <button class="btn btn-sm btn-outline-secondary mt-2 no-print" onclick="window.print()">🖨 Print</button>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-4 text-center border rounded-3 p-3">
      <div class="text-muted small">Total Orders</div>
      <div class="h4 fw-bold">{{ $totalOrdersCount }}</div>
    </div>
    <div class="col-4 text-center border rounded-3 p-3">
      <div class="text-muted small">Completed</div>
      <div class="h4 fw-bold text-success">{{ $completedOrdersCount }}</div>
    </div>
    <div class="col-4 text-center border rounded-3 p-3">
      <div class="text-muted small">Revenue</div>
      <div class="h4 fw-bold" style="color:#2d7a38;">PKR {{ number_format($totalRevenue, 2) }}</div>
    </div>
  </div>

  <h5 class="fw-bold mb-2">Revenue by Market</h5>
  <table class="table table-bordered table-sm mb-4">
    <thead class="table-light">
      <tr><th>Market</th><th>Orders</th><th>Revenue</th></tr>
    </thead>
    <tbody>
      @foreach($revenueByMarket as $m)
      <tr>
        <td>{{ $m->name }}</td>
        <td>{{ $m->orders_count }}</td>
        <td>PKR {{ number_format($m->total_revenue ?? 0, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h5 class="fw-bold mb-2">Top Performing Farmers</h5>
  <table class="table table-bordered table-sm mb-4">
    <thead class="table-light">
      <tr><th>Stall Name</th><th>Contact</th><th>Orders</th><th>Sales</th></tr>
    </thead>
    <tbody>
      @foreach($topFarmers as $f)
      <tr>
        <td>{{ $f->stall_name }}</td>
        <td>{{ $f->contact_person }}</td>
        <td>{{ $f->orders_count }}</td>
        <td>PKR {{ number_format($f->total_sales ?? 0, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h5 class="fw-bold mb-2">Most Ordered Products</h5>
  <table class="table table-bordered table-sm">
    <thead class="table-light">
      <tr><th>#</th><th>Product</th><th>Category</th><th>Units Ordered</th></tr>
    </thead>
    <tbody>
      @foreach($topProducts as $i => $p)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->category->name ?? '—' }}</td>
        <td>{{ number_format($p->units_ordered) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="text-muted text-center mt-4" style="font-size:.78rem;">
    © {{ date('Y') }} MarketLink – eGreen Basket Platform · Confidential
  </div>

  <script>window.onload = function() { window.print(); }</script>
</body>
</html>
