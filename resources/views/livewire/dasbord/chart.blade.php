<div class="mt-10">

    <x-card title="Grafik Transaksi Nasabah" shadow separator>
        <div class="flex justify-center h-96 mt-5" wire:ignore>

            <div id="loading" class="flex justify-center h-96 ">
                <x-loading class="loading-infinity loading-xl" />
            </div>
            <div id="chart" class="w-full hidden"></div>
        </div>
    </x-card>




    <!-- Pie Chart -->

</div>


@script
    <script>
        document.addEventListener('livewire:initialized', () => {

            $wire.transaksi();

            $wire.on('chart-transaksi', (data) => {

                const options = {
                    chart: {
                        type: 'bar',
                        height: 375,
                        stacked: true,

                    },
                    series: data.chart.series,
                    xaxis: {
                        categories: data.chart.labels
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 300
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    },

                    tooltip: {
                        shared: true,
                        intersect: false,
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Inter, sans-serif',
                        },
                        custom: function({
                            series,
                            dataPointIndex,
                            w
                        }) {
                            const date = w.globals.categoryLabels[dataPointIndex];
                            const seriesNames = w.globals.seriesNames;

                            // Ambil nilai Setor dan Tarik dari index masing-masing series
                            const setor = series[0][dataPointIndex] ?? 0;
                            const tarik = series[1][dataPointIndex] ?? 0;

                            return `
                        <div class="
                          p-3 
                            text-gray-800 text-sm w-56
                            bg-base-100
                            dark:bg-base-300
                       
                        ">
                            <div class="flex justify-between">
                                <span class=" font-semibold text-success">${seriesNames[0]} Tunai</span>
                                <span class=" font-semibold text-success">Rp${setor.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class=" font-semibold text-error">${seriesNames[1]} Tunai</span>
                                <span class=" font-semibold text-error">Rp${tarik.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
            `;
                        }
                    },
                    stroke: {
                        curve: 'smooth'
                    },

                };
                let loading = document.getElementById('loading');
                loading.classList.add('hidden');
                let chart = document.getElementById('chart');
                chart.classList.remove('hidden');

                var chartApex = new ApexCharts(chart, options);
                chartApex.render();
            });

        })
    </script>
@endscript
