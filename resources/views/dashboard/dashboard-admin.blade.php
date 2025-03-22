@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">DataTables.Net</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tables</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Datatables</a>
                </li>
            </ul>
        </div>
<<<<<<< HEAD
=======

        <!-- Tambahan Grafik -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Grafik Penjualan</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
>>>>>>> e5d46b5 (Menambahkan project baru)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                        <td>System Architect</td>
                                        <td>Edinburgh</td>
                                        <td>61</td>
                                        <td>2011/04/25</td>
                                        <td>$320,800</td>
                                    </tr>

                                    <tr>
                                        <td>Shad Decker</td>
                                        <td>Regional Director</td>
                                        <td>Edinburgh</td>
                                        <td>51</td>
                                        <td>2008/11/13</td>
                                        <td>$183,000</td>
                                    </tr>
                                    <tr>
                                        <td>Michael Bruce</td>
                                        <td>Javascript Developer</td>
                                        <td>Singapore</td>
                                        <td>29</td>
                                        <td>2011/06/27</td>
                                        <td>$183,000</td>
                                    </tr>
                                    <tr>
                                        <td>Donna Snider</td>
                                        <td>Customer Support</td>
                                        <td>New York</td>
                                        <td>27</td>
                                        <td>2011/01/25</td>
                                        <td>$112,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<<<<<<< HEAD

        </div>
    </div>
</div>
@endsection
=======
        </div>
    </div>
</div>

<!-- Tambahkan Chart.js dan AJAX -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let ctx = document.getElementById('transactionChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Jumlah Transaksi',
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.2)',
                    data: [],
                    fill: true
                },
                {
                    label: 'Total Pendapatan',
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 255, 0, 0.2)',
                    data: [],
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { title: { display: true, text: 'Tanggal' } },
                y: { title: { display: true, text: 'Jumlah' } }
            }
        }
    });

    function fetchData() {
        $.ajax({
            url: '/api/penjualan',
            method: 'GET',
            success: function (data) {
                chart.data.labels = data.map(t => t.date);
                chart.data.datasets[0].data = data.map(t => t.count);
                chart.data.datasets[1].data = data.map(t => t.revenue);
                chart.update();
            }
        });
    }

    // Ambil data pertama kali
    fetchData();

    // Update data setiap 5 detik
    setInterval(fetchData, 5000);
</script>

<script>
    let ctx = document.getElementById('transactionChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Jumlah Penjualan',
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.2)',
                    data: [],
                    fill: true
                },
                {
                    label: 'Total Pendapatan',
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 255, 0, 0.2)',
                    data: [],
                    fill: true
                },
                {
                    label: 'Jumlah Pembelian',
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    data: [],
                    fill: true
                },
                {
                    label: 'Total Pengeluaran',
                    borderColor: 'orange',
                    backgroundColor: 'rgba(255, 165, 0, 0.2)',
                    data: [],
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { title: { display: true, text: 'Tanggal' } },
                y: { title: { display: true, text: 'Jumlah' } }
            }
        }
    });

    function fetchData() {
        $.ajax({
            url: '/api/penjualan',
            method: 'GET',
            success: function (data) {
                let labels = data.penjualan.map(t => t.date);
                let salesCount = data.penjualan.map(t => t.count);
                let revenue = data.penjualan.map(t => t.revenue);
                let purchaseCount = data.pembelian.map(t => t.count);
                let cost = data.pembelian.map(t => t.cost);

                chart.data.labels = labels;
                chart.data.datasets[0].data = salesCount;
                chart.data.datasets[1].data = revenue;
                chart.data.datasets[2].data = purchaseCount;
                chart.data.datasets[3].data = cost;
                chart.update();
            }
        });
    }

    // Ambil data pertama kali
    fetchData();

    // Update data setiap 5 detik
    setInterval(fetchData, 5000);
</script>

@endsection
>>>>>>> e5d46b5 (Menambahkan project baru)
