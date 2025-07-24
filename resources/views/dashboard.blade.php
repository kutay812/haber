@extends('layouts.admin.app')
@section('title', 'Admin Paneli')

@section('content')
    <!-- Bildirim veya sistem mesajı -->
    @if(isset($notification))
        <div class="alert alert-info">
            {{ $notification }}
        </div>
    @endif

    <div class="row">
        @foreach ($stats as $box)
            <div class="col-lg-3 col-12">
                <div class="small-box bg-{{ $box['color'] }}">
                    <div class="inner">
                        <h3>{{ $box['count'] }}</h3>
                        <p>{{ $box['label'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $box['icon'] }}"></i>
                    </div>
                    <a href="{{ $box['link'] }}" class="small-box-footer">
                        Detaylar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Basit örnek grafik (isteğe bağlı, AdminLTE Sparkline veya ChartJS ile) -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Örnek Grafik</h3>
        </div>
        <div class="card-body">
            <canvas id="myChart" height="80"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Sparkline (isteğe bağlı) -->
    <script src="/storage/adminlte/plugins/sparklines/sparkline.js"></script>
    <!-- Chart.js CDN ile -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Basit örnek grafik
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($stats as $box) "{{ $box['label'] }}", @endforeach
                ],
                datasets: [{
                    label: 'Adet',
                    data: [
                        @foreach($stats as $box) {{ $box['count'] }}, @endforeach
                    ],
                    backgroundColor: [
                        '#17a2b8', // info
                        '#ffc107', // warning
                        '#28a745', // success
                        '#dc3545', // danger
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    <!-- AdminLTE dashboard demo (isteğe bağlı) -->
    <script src="/storage/adminlte/dist/js/pages/dashboard.js"></script>
    <pre>{{ print_r(Auth::user()->getRoleNames(), true) }}</pre>

@endpush
