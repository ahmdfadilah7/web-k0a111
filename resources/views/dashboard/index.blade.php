@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pemohon SIM</h4>
                    </div>
                    <div class="card-body">
                        {{ $pemohon_sim }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-warning bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pemohon SIM Belum Diverifikasi</h4>
                    </div>
                    <div class="card-body">
                        {{ $pemohon_sim_blm_diverif }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-success bg-success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pemohon SIM Sudah Diverifikasi</h4>
                    </div>
                    <div class="card-body">
                        {{ $pemohon_sim_sdh_diverif }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Statistics</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart2" height="180"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-home"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Polres</h4>
                    </div>
                    <div class="card-body">
                        {{ $polres_count }}
                    </div>
                </div>
            </div>
            <div class="card card-statistic-2">
                <div class="card-icon shadow-warning bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Petugas</h4>
                    </div>
                    <div class="card-body">
                        {{ $petugas_count }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var ctx = document.getElementById("myChart2").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($polres as $row)
                        "{{ $row->nama_polres }}",
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Jumlah',
                        data: [
                            @foreach ($polres as $row)
                                @php
                                    $blm_diverif = \App\Models\Profile::join('users', 'profiles.user_id', 'users.id')->where('polres_id', $row->id)->where('users.role', 'Member')->where('status', 0)->count();
                                @endphp
                                {{ $blm_diverif }},
                            @endforeach
                        ],
                        borderWidth: 2,
                        backgroundColor: 'rgba(254,86,83,.7)',
                        borderColor: 'rgba(254,86,83,.7)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#ffffff',
                        pointRadius: 4
                    }, 
                    {
                        label: 'Jumlah',
                        data: [
                            @foreach ($polres as $row)
                                @php
                                    $sdh_diverif = \App\Models\Profile::join('users', 'profiles.user_id', 'users.id')->where('polres_id', $row->id)->where('users.role', 'Member')->where('status', 1)->count();
                                @endphp
                                {{ $sdh_diverif }},
                            @endforeach
                        ],
                        borderWidth: 2,
                        backgroundColor: 'rgba(63,82,227,.8)',
                        borderColor: 'transparent',
                        borderWidth: 0,
                        pointBackgroundColor: '#999',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: {{ \App\Models\Profile::join('users', 'profiles.user_id', 'users.id')->where('users.role', 'Member')->count() }}
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
            }
        });
    </script>
@endsection
