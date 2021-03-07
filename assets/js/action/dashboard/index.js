$( document ).ready(function() {
  $('#menu-dashboard').addClass('mm-active');
  getdash('kalibrasi');
});

function getdash(param){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'getDash',
        data : {
                param      : param,
         },
        success: function(result){
          // console.log(result);
          let categories = [];
          let trackgauge = [];
          let back_to_back = [];
          let vernier_calipper = [];

          let diterima = [];
          let ditolak = [];

          let total = [];

          for (var i = 0; i < result.length; i++) {
            categories.push(result[i].nama_pemilik_alat_ukur);
            trackgauge.push(result[i].trackgauge);
            back_to_back.push(result[i].back_to_back);
            vernier_calipper.push(result[i].vernier_calipper);
            diterima.push(parseInt(result[i].diterima));
            ditolak.push(parseInt(result[i].ditolak));
            total.push(parseInt(result[i].total));
          }

          let total_diterima = diterima.reduce((a, b) => a + b, 0);
          let total_ditolak = ditolak.reduce((a, b) => a + b, 0);


          let persen_diterima = (total_diterima/(total_diterima+total_ditolak))*100;
          let persen_ditolak = (total_ditolak/(total_diterima+total_ditolak))*100;

          var options_bar = {
            series: [{
            name: 'Track Gauge',
            data: trackgauge
          }, {
            name: 'Back to back',
            data: back_to_back
          }, {
            name: 'Vernier calipper',
            data: vernier_calipper
          }],
            chart: {
            type: 'bar',
            height: 600
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '80%',
              endingShape: 'flat',
              colors: {
                  ranges: [{
                      from: 0,
                      to: 0,
                      color: undefined
                  }],
                  backgroundBarColors: [],
                  backgroundBarOpacity: 1,
                  backgroundBarRadius: 0,
              },
            },
          },
          colors: ['#5b9bd5', '#ed7d31', '#a5a5a5'],
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: false,
            width: 2,
            colors: ['transparent']
          },
          xaxis: {
            categories: categories,
            labels: {
              maxHeight: 300,
              rotate: -90,
            }
          },
          yaxis: {
            title: {
              text: ''
            }
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (val) {
                return val
              }
            }
          }
          };

          var chart_bar = new ApexCharts(document.querySelector("#chart-apex-columbus"), options_bar);
          chart_bar.render();

          var options_pie = {
          series: [Math.ceil(persen_diterima.toFixed(2)), Math.ceil(persen_ditolak.toFixed(2))],
          chart: {
            width: 400,
            type: 'pie',
          },

          labels: ['Diterima - '+persen_diterima.toFixed(2)+'%', 'Ditolak - '+persen_ditolak.toFixed(2)+'%'],
            responsive: [{
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
                legend: {
                  position: 'bottom'
                }
              }
            }],
            colors: ['#5b9bd5', '#ed7d31']
          };

        var chart_pie = new ApexCharts(document.querySelector("#chart-apex-columbus-pie"), options_pie);
        chart_pie.render();

        var options = {
          series: [{
          name: 'Total',
          data: total
        }],
          chart: {
          height: 600,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: true,
          formatter: function (val) {
            return val + "";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },

        xaxis: {
          categories: categories,
          position: 'bottom',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          },
          labels: {
            maxHeight: 300,
            rotate: -90,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val + "";
            }
          }

        },
        title: {
          text: '',
          floating: true,
          offsetY: 330,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart-apex-columbus-tab"), options);
        chart.render();

        }
      })
    }
