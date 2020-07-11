<script>
import { Line, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
  extends: Line,
  mixins: [reactiveProp],
  props: ['options'],
  mounted () {
    this.renderChart(this.chartData, {
    	responsive: true, 
    	maintainAspectRatio: false,
        legend: {
            display: true,
            position : "bottom",
            labels: {
                padding: 30
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

                    if (label) {
                        label += ': ';
                    }
                    label += window._currencyFormat(tooltipItem.yLabel) + " IDR";
                    return label;
                }
            }
        }            	
    })
  }
}	
</script>