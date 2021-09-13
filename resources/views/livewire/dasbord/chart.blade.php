<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Chart Transaksi</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="myAreaChart" style="display: block; width: 1037px; height: 320px;" width="1037"
                        height="320" class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Chart Nasabah</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="nasabah" width="486" height="245" style="display: block; width: 486px; height: 245px;"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('livewire:load', function () {

        let transaksi = [];


        @this.transaksi()
            .then((data) => {

                var labaChart = document.getElementById('myAreaChart');

                var myChartLaba = new Chart(labaChart, {
                    type: 'bar',
                    data: {
                        labels: data['labels'],
                        datasets: [

                            {
                                type: 'line',
                                label: 'Setor',
                                id: "y-axis-0",
                                data: data['data_setor'],
                                borderColor: data['warna_setor'],
                                tension: 0.1
                            },
                            {
                                type: 'line',
                                label: 'Tarik',
                                id: "y-axis-0",
                                data: data['data_tarik'],
                                borderColor: data['warna_tarik'],
                                tension: 0.1
                            }


                        ]
                    },
                    options: {

                        tooltips: {
                            mode: 'label'
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true
                            }],
                            yAxes: [{

                                ticks: {
                                    callback: function (value, index, values) {
                                        return value.toLocaleString("id-ID", { style: "currency", currency: "IDR" });
                                    }
                                },

                                stacked: true,
                                position: "left",
                                id: "y-axis-0",
                            },



                            ]
                        }
                    }



                });



            })








        @this.nasabah()
            .then((data) => {

                var nasabahChart = document.getElementById('nasabah');

                nasabah = new Chart(nasabahChart, {
                    type: 'bar',
                    data: {
                        labels: data['labels'],
                        datasets: [{
                            label: 'Saldo Nasabah Terbanyak',
                            data: data['data'],
                            borderWidth: 1,
                            backgroundColor: data['backgroundColor'],
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    callback: function (value, index, values) {
                                        return value.toLocaleString("id-ID", { style: "currency", currency: "IDR" });
                                    }
                                }
                            }]
                        }
                    }


                });



            });









    })
</script>