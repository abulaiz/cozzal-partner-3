require('../../bootstrap');
window.Vue = require('vue');

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

// Vue currency formater
import Cleave from 'vue-cleave-component';
Vue.component('cleave', Cleave);

var _URL = {};
_URL['index'] = $("#url-index").text();
_URL['invoice'] = $("#api-invoice").text();
_URL['cash'] = $("#api-cashes").text();
_URL['send'] = $("#api-send").text();
_URL['pay'] = $("#api-pay").text();
_URL['confirm'] = $("#api-confirm").text();
_URL['reject'] = $("#api-reject").text();

var has_submited = 0;

$(".rm").remove();

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'warning', false);
    return false;
}

function _str_empty(str){
	if(str == null) return true;
	return str.split(" ").join("") == "" 
}

var content = new Vue({
	el : "#content",
	data : {
		payments : [],
		option : {
			cashes : []
		},
		cleave : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand' 			
		}
	},
	methods : {
		setData(res){
			for(let i in res.owners){
				const expenditures = res.expenditures.filter( item => item.unit.owner_id == res.owners[i].id );
				const reservations = res.reservations.filter( item => item.unit.owner_id == res.owners[i].id );
				let expenditure_total = 0;
				let reservation_total = 0;
				for(let i in expenditures) expenditure_total += expenditures[i].price*expenditures[i].qty;
				for(let i in reservations) reservation_total += Number( JSON.parse(reservations[i].owner_rent_prices).TP );

				this.payments.push({
					owner : res.owners[i],
					expenditures : expenditures,
					reservations : reservations,
					description : res.description,
					has_arranged : res.has_arranged,
					paid_earning : res.paid_earning,
					receipt_number : res.receipt_number,
					role : res.role,
					date : res.date,
					expenditure_total : expenditure_total,
					reservation_total : reservation_total,
					earning : reservation_total - expenditure_total,
					additional_earning : res.paid_earning == null ? 0 : res.paid_earning - (reservation_total - expenditure_total),
					cash : null,
					input_earning : reservation_total - expenditure_total,
					input_description : null,
					is_paid : res.is_paid,
					is_accepted : res.has_arranged ? res.is_accepted : true,
					onsubmit : false
				})
			}
			if(this.payments.length <= 1) $("#global-action").remove();
		},
		getReservations(index){
			let res = [];
			for(let i in this.payments[index].reservations){
				res.push( this.payments[index].reservations[i].id );
			}	
			return res;
		},
		getExpenditures(index){
			let res = [];
			for(let i in this.payments[index].expenditures){
				res.push( this.payments[index].expenditures[i].id );
			}	
			return res;
		},		
		pay(index){
			if(this.payments[index].cash == null)
				_catch_with_toastr("Cash required");
			if(this.payments[index].paid_earning != null && _str_empty(this.payments[index].input_description))
				_catch_with_toastr("Description required");

		    axios.post(_URL.pay , {
		    	reservations : this.getReservations(index),
		    	expenditures : this.getExpenditures(index),
		    	total_earning : this.payments[index].earning,
		    	paid_earning : this.payments[index].input_earning,
		    	description : this.payments[index].input_description,
		    	owner_id : this.payments[index].owner.id,
		    	cash_id : this.payments[index].cash.id
		    }).then(function (response) {
		        let res = response.data;
		        if(res.success){

		        } else {

		        }
		    })
		    .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
		},
		send(index){
			let e = this;
			_leftAlert('Info', 'Processing ...', 'info');
			this.payments[index].onsubmit = true;
			if(this.payments[index].paid_earning != null && _str_empty(this.payments[index].input_description))
				_catch_with_toastr("Description required");
		    axios.post(_URL.send , {
		    	reservations : JSON.stringify(this.getReservations(index)),
		    	expenditures : JSON.stringify(this.getExpenditures(index)),
		    	total_earning : this.payments[index].earning,
		    	paid_earning : this.payments[index].input_earning,
		    	description : _str_empty(this.payments[index].input_description) ? '-' : this.payments[index].input_description,
		    	owner_id : this.payments[index].owner.id
		    }).then(function (response) {
		        let res = response.data;
		        if(res.success){
		        	has_submited++;
		        	if(has_submited == e.payments.length){
			            window._setMessage('message.payment.index', 'Payment purpose has been sended', 'success');
			            window.location = _URL.index; 
		        	} else {
		        		_leftAlert('Success !', 'Payment purpose has been sended', 'success');
		        	}
		        } else {
		      		_leftAlert('Error', 'Something wrong, try again', 'error');  	
		        }
		    })
		    .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
		},
		toIDR(number){
			return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},
		confirm(index){

		},
		reject(index){

		}
	},
	created : function(){
		let e = this;
		axios.get(_URL.invoice).then(function (response) { e.setData(response.data) })
		axios.get(_URL.cash).then( (response) => { this.option.cashes = response.data })
		$(".op-0").css('opacity', '1');
	}
})