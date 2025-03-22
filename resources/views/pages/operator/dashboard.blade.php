@extends('layouts.operator')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">{{ ucwords(Auth::user()->role) }} / {{ $title }}</h1>
    </div>
    <div class="grid grid-cols-1 gap-1 md:gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-gray-800 p-4 rounded-xl shadow-xl transition-transform transform hover:scale-105 stat-card">
            <div class="flex items-center gap-4">
                <div class="bg-merah rounded-lg w-16 h-16 flex items-center justify-center shadow-md">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-gray-300 text-lg font-semibold">Jumlah Anggota</h3>
                    <div class="text-3xl font-bold text-white">{{ $jumlahAnggota }}</div>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 p-4 rounded-xl shadow-xl transition-transform transform hover:scale-105 stat-card">
            <div class="flex items-center gap-4">
                <div class="bg-biru rounded-lg w-16 h-16 flex items-center justify-center shadow-md">
                    <i class="fas fa-building text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-gray-300 text-lg font-semibold">Jumlah Cabang</h3>
                    <div class="text-3xl font-bold text-white">{{ $jumlahCabang }}</div>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 p-4 rounded-xl shadow-xl transition-transform transform hover:scale-105 stat-card">
            <div class="flex items-center gap-4">
                <div class="bg-kuning rounded-lg w-16 h-16 flex items-center justify-center shadow-md">
                    <i class="fas fa-code-branch text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-gray-300 text-lg font-semibold">Jumlah Ranting</h3>
                    <div class="text-3xl font-bold text-white">{{ $jumlahRanting }}</div>
                </div>
            </div>
        </div>
        <div class="bg-gray-800 p-4 rounded-xl shadow-xl transition-transform transform hover:scale-105 stat-card">
            <div class="flex items-center gap-4">
                <div class="bg-green-600 rounded-lg w-16 h-16 flex items-center justify-center shadow-md">
                    <i class="fas fa-user-cog text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-gray-300 text-lg font-semibold">Jumlah Pengurus Daerah</h3>
                    <div class="text-3xl font-bold text-white">{{ $jumlahOperator }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4 mt-1 md:mt-4">
        <div class="bg-gray-800 p-6 rounded-xl shadow-xl lg:col-span-2">
            <div class="text-xl font-bold text-white mb-4">
                <h3>Pertumbuhan Anggota</h3>
            </div>
            <div class="mt-4">
                <div id="chartLine"></div>
            </div>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl shadow-xl">
            <div class="text-xl font-bold text-white mb-4">
                <h3>Demografi</h3>
            </div>
            <div class="flex flex-col items-center space-y-4 relative">
                <div id="chartDonut"></div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span>Laki-laki</span>
                    </div>
                    <span id="maleValue">0 (0%)</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span>Perempuan</span>
                    </div>
                    <span id="femaleValue">0 (0%)</span>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetch("{{ route('chartData') }}")
                .then(response => response.json())
                .then(data => {
                    if (!data.values || !data.categories) {
                        console.error("Data format tidak valid:", data);
                        return;
                    }
    
                    const totalAnggota = data.values.reduce((a, b) => a + b, 0);
                    //document.getElementById("jumlahAnggota").innerText = totalAnggota;
    
                    var lineOptions = {
                        series: [{
                            name: 'Jumlah Anggota',
                            data: data.values
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            toolbar: { show: false },
                            zoom: { enabled: false },
                        },
                        stroke: {
                            width: 5,
                            curve: 'smooth'
                        },
                        xaxis: {
                            categories: data.categories.map(date => {
                                let d = new Date(date);
                                return new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' }).format(d);
                            }),
                            tickAmount: 6,
                            labels: {
                                style: {
                                    colors: '#ffffff'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#ffffff'
                                }
                            }
                        },
                        grid: {
                            show: false
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'dark',
                                gradientToColors: ['#3B82F6'],
                                shadeIntensity: 1,
                                type: 'vertical',
                                opacityFrom: 1,
                                opacityTo: 0.4,
                                stops: [0, 100]
                            }
                        },
                        markers: {
                            size: 5,
                            colors: ['#3B82F6']
                        },
                        tooltip: {
                            theme: "dark"
                        }
                    };
    
                    var lineChart = new ApexCharts(document.querySelector("#chartLine"), lineOptions);
                    lineChart.render();
                })
                .catch(error => console.error("Error fetching line chart data:", error));
    
            // Pastikan ada elemen dengan ID yang sesuai di HTML
            const maleCount = {{ $jumlahLaki }};
            const femaleCount = {{ $jumlahPerempuan }};
            const total = maleCount + femaleCount;
    
            var donutOptions = {
                series: [maleCount, femaleCount],
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: ['Laki-laki', 'Perempuan'],
                colors: ['#3B82F6', '#FACC15'],
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.globals.series[opts.seriesIndex] + " (" + val.toFixed(1) + "%)";
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " orang";
                        }
                    }
                }
            };
    
            document.getElementById("maleValue").innerText = `${maleCount} (${((maleCount / total) * 100).toFixed(1)}%)`;
            document.getElementById("femaleValue").innerText = `${femaleCount} (${((femaleCount / total) * 100).toFixed(1)}%)`;
    
            var donutChart = new ApexCharts(document.querySelector("#chartDonut"), donutOptions);
            donutChart.render();
        });
    </script>
</div>
@endsection

@section('scripts')
@endsection