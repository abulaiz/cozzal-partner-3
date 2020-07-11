require('../../bootstrap');

window.Vue = require('vue');

// Date Picker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.component('date-picker', DatePicker);

// Vue Chart Components
import IncomeStatistic from '../../components/dashboard/IncomeStatistic'
import TransactionStatistic from '../../components/dashboard/TransactionStatistic'


var _URL = {};
_URL['incomes'] = $("#api-incomes").text();
_URL['outcomes'] = $("#api-outcomes").text();
_URL['reservations'] = $("#api-reservations").text();

$(".rm").remove();

var app = new Vue({
	el : "#content",
    components: {
      IncomeStatistic, TransactionStatistic
    },	
	data : {
		statistic_mode : 'income', // income, outcome, reservation
		total_reservation : 0,
		first_load : true,
		total_booking : 0,
		total_cancellation : 0,
		total_income : 0,
		total_gross_profit : 0,
		income_collection: null,
		transaction_collection : null,
		statistic_collection : {
			income : null, outcome : null, reservation : null
		},
		year : null,
		months : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		color: ['#5E66E5', '#FF4961', '#62FFAE', '#1A97F0', '#FF4081', '#9C27B0', '#009688', '#689F38', '#607D8B', '#E91E63'],
	},
    created () {
    	this.year = new Date().getFullYear();
    	setTimeout( () => {
    		this.first_load = false;
    	}, 2000)
    },
    methods : {
    	setStatisticMode(mode){
    		this.statistic_mode = mode;
    	},
    	loadStatistic(type, data){
			let datasets = [];
			let x = 0;
			for(let i in data){
				datasets.push({
		        	label: i,
		        	fill: false,
		        	borderColor : this.color[x],
		        	backgroundColor: this.color[x],
		        	data: data[i]					
				});
				x++;
			}    		
    		this.statistic_collection[type] = {
			    labels: this.months,
			    datasets: datasets
    		}
    	}	   	
    },
    watch : {
    	year : function(newVal, oldVal){
    		if(newVal == null) return;
    		if(newVal == oldVal) return;
	    	let e = this;
			axios.get(_URL.incomes.replace('/0', '/' + newVal)).then(function (response) { 
				e.loadStatistic('income', response.data);
			})
			axios.get(_URL.outcomes.replace('/0', '/' + newVal)).then(function (response) { 
				e.loadStatistic('outcome', response.data);
			})
			axios.get(_URL.reservations.replace('/0', '/' + newVal)).then(function (response) { 
				e.loadStatistic('reservation', response.data);
			})						
    	}  	
    }
})

window.onload = function(){
	$(".op-0").css('opacity', '1');
}