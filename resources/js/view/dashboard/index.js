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
_URL['transactions'] = $("#api-transactions").text();

$(".rm").remove();

var app = new Vue({
	el : "#content",
    components: {
      IncomeStatistic, TransactionStatistic
    },	
	data : {
		total_reservation : 0,
		total_booking : 0,
		total_cancellation : 0,
		total_income : 0,
		total_gross_profit : 0,
		income_collection: null,
		transaction_collection : null,
		transaction_year : null,
		income_year : null,
		months : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
	},
    created () {
    	const current_year = new Date().getFullYear();
    	this.income_year = current_year;
    	this.transaction_year = current_year;
    },
    methods : {
		loadIncomesStatistic(data){
			this.income_collection = {
			    labels: this.months,
			    datasets: [
			        {
			        	label: 'Income',
			        	fill: false,
			        	borderColor : '#9C27B0',
			        	backgroundColor: '#9C27B0',
			        	data: data.income
			        },
			        {
			        	label: 'Gross Profit',
			        	borderColor : '#E91E63',
			        	backgroundColor: '#E91E63',
			        	fill: false,
			        	data: data.gross_profit
			        },			        
			    ]
			}
		},
		loadTransactionsStatistic(data){
			this.transaction_collection = {
			    labels: this.months,
			    datasets: [
			        {
			        	label: 'Reservation',
			        	fill: false,
			        	borderColor : '#28D094',
			        	backgroundColor: '#28D094',
			        	data: data.reservation
			        },
			        {
			        	label: 'Booking',
			        	borderColor : '#1E9FF2',
			        	backgroundColor: '#1E9FF2',
			        	fill: false,
			        	data: data.booking
			        },	
			        {
			        	label: 'Canceled',
			        	borderColor : '#FF9149',
			        	backgroundColor: '#FF9149',
			        	fill: false,
			        	data: data.canceled
			        },				        		        
			    ]
			}
		},
		count_transactions(data){
			this.total_reservation = data.reservation.reduce((a, b) => a + b, 0)
			this.total_booking = data.booking.reduce((a, b) => a + b, 0)
			this.total_cancellation = data.canceled.reduce((a, b) => a + b, 0)
		},
		count_incomes(data){
			this.total_income = window._currencyFormat(data.income.reduce((a, b) => a + b, 0))
			this.total_gross_profit = window._currencyFormat(data.gross_profit.reduce((a, b) => a + b, 0))
		} 		   	
    },
    watch : {
    	income_year : function(newVal, oldVal){
    		if(newVal == null) return;
    		if(newVal == oldVal) return;
	    	let e = this;
			axios.get(_URL.incomes.replace('/0', '/' + newVal)).then(function (response) { 
				e.loadIncomesStatistic(response.data);
				e.count_incomes(response.data)
			})
    	},
    	transaction_year : function(newVal, oldVal){
    		if(newVal == null) return;
    		if(newVal == oldVal) return;
	    	let e = this;
			axios.get(_URL.transactions.replace('/0', '/' + newVal)).then(function (response) { 
				e.loadTransactionsStatistic(response.data);
				e.count_transactions(response.data)
			})
    	}    	
    }
})

window.onload = function(){
	$(".op-0").css('opacity', '1');
}