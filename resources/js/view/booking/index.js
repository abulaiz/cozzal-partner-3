require('../../bootstrap');
window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

// Vue currency formater
import Cleave from 'vue-cleave-component';
Vue.component('cleave', Cleave);

Vue.component('upload-image', require('../../components/UploadImage.vue').default);

var _URL = {};
_URL['index'] = $("#api-booking-index").text();
_URL['payment_info'] = $("#api-payment-info").text();
_URL['payment_store'] = $("#api-payment-store").text();
_URL['cash'] = $("#api-cashes").text();
_URL['payment_settlement'] = $("#api-payment-settlement").text();
_URL['cancel'] = $("#api-booking-destroy").text();
_URL['confirm'] = $("#api-booking-confirm").text();

const axios_config = {header : { 'Content-Type' : 'multipart/form-data' }}

$(".rm").remove();

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'error', false);
    return false;
}

function tableOptions(){
	return {
		processing: true,
		serverSide: true,		
	    ajax: _URL.index,
	    columns: [
	        {data: 'receipt_id', name: 'receipt_id'},
	        {data: 'tenant', name: 'tenant'},
	        {data: 'check_in', name: 'check_in'},
	        {data: 'check_out', name: 'check_out'},
	        {data: 'unit', name: 'unit'},
	        {data: '_action', name: '_action', orderable: false, searchable: false}
	    ],
	    order: [[0, 'desc']],
	    createdRow: function( row, data, dataIndex ) {
	        $( row ).find('td:eq(2)').text( data.check_in.substr(0, 10) );       
	        $( row ).find('td:eq(3)').text( data.check_out.substr(0, 10) );       
	    },
	    fnDrawCallback: function( oSettings ) {
			$('.table-responsive').on('show.bs.dropdown', function () {
			     $('.table-responsive').css( "overflow", "inherit" );
			});

			$('.table-responsive').on('hide.bs.dropdown', function () {
			     $('.table-responsive').css( "overflow", "auto" );
			})      
	    },	    	       
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

window.payment = function(e){
	let data = Table.row($(e).parents('tr')).data();
	payment.load_info(data.id)
}

window.confirm = function (e){
	let data = Table.row($(e).parents('tr')).data();
	_leftAlert('Info', 'Checking payment requirement ...', 'info');
	axios.post(_URL.confirm , {
		reservation_id : data.id
	}).then(function (response) {
		if(response.data.success){
			_leftAlert('Success', 'Please wait, you will be redirected ...', 'info');
			setTimeout( () => {
	            window._setMessage(response.data.direct_path, response.data.message, 'success');
	            window.location = response.data.direct_route; 
			}, 200 )
		} else {
			_leftAlert('Failed', 'Please complete payment first', 'warning');
		}
	})
	.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })	
}

window.cancel = function(e){
	_confirm("Are you sure ?", "Canceled booking cann't restored", function(){
		let data = Table.row($(e).parents('tr')).data();
		_leftAlert('Info', 'Checking payment requirement ...', 'info');
		axios.post(_URL.cancel.replace('/0', '/'+data.id) , {
			_method : 'delete'
		}).then(function (response) {
			if(response.data.success){
				_leftAlert('Success', 'Please wait, you will be redirected ...', 'info');
				setTimeout( () => {
		            window._setMessage(response.data.direct_path, response.data.message, 'info');
		            window.location = response.data.direct_route; 
				}, 200 )
			} else {
				_leftAlert('Failed', 'Make sure deposit has settled', 'warning');
			}
		})
		.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
	});		
}

var payment = new Vue({
	el : "#payment-modals",
	data : {
		id : null,
		onload : true,
		total_amount : null,
		has_pay : null,
		status : null,
		deposit : null,
		dp : null,
		remaining_payment : null,
		deposit_status : null,
		show_deposit_status : true,
		can_settlement_deposite : false,
		can_make_payment : false,
		option : { cashes : [] },
        cleave : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'       
        },		
		input : {
			cash : null, fund : null, attachment : null
		}
	},
	methods : {
		pay(){
			if( this.input.cash == null )
				return _catch_with_toastr('Please select source fund');
			if( this.input.fund == null )
				return _catch_with_toastr('Fund is required');
			if( this.input.fund <= 0 )
				return _catch_with_toastr('Fund not meet requirement of payment');			
			if( this.input.fund > this.remaining_payment )
				return _catch_with_toastr("System doesn't have refund system");			
			if( this.input.attachment == null )
				return _catch_with_toastr("Payment Slip is required")

			let data = new FormData();
			data.append('reservation_id', this.id);
			data.append('cash_id', this.input.cash.id);
			data.append('fund', this.input.fund);
			data.append('attachment', this.input.attachment == null ? '' : this.input.attachment)
			this.onload = true;
		    axios.post(_URL.payment_store , data, axios_config).then( (response) => {
		    	this.input.fund = null;
		    	this.input.cash = null;
		    	this.input.attachment = null;
		    	this.load_info(this.id);
		    	_leftAlert('Success', 'Successfuly make a booking payment', 'success');
		    })
		    .catch( () => { _leftAlert('Error', 'Something wrong, try again', 'error'); })
		},
		settlement(){
			if( this.input.cash == null )
				return _catch_with_toastr('Please select source fund');
			if( this.input.attachment == null )
				return _catch_with_toastr("Payment Slip is required")		

			let data = new FormData();
			data.append('reservation_id', this.id)
			data.append('cash_id', this.input.cash_id.id)
			data.append('attachment', this.input.attachment == null ? '' : this.input.attachment)
			this.onload = true;
		    axios.post(_URL.payment_settlement , data, axios_config).then( (response) => {
		    	if(response.data.success){
			    	this.input.fund = null;
			    	this.input.cash = null;
			    	this.input.attachment = null;
			    	this.load_info(this.id);
			    	_leftAlert('Success', 'Deposit has been settled', 'success');
		    	} else {
			    	this.load_info(this.id);
			    	_leftAlert('Sorry', 'Balance of source fund not enought', 'warning');		    		
		    	}

		    })
		    .catch( () => { _leftAlert('Error', 'Something wrong, try again', 'error'); })
		},
		to_IDR(x){
			if(x == null) x = 0; 
			return x.toLocaleString('tr-TR', {style: 'currency', currency: 'IDR'})
		},
		load_info(id){
			let e = this;
			this.id = id;
			this.onload = true;
        	axios.get(_URL.payment_info.replace("/0", "/"+this.id))
        	.then(function (response) { 
        		e.total_amount = response.data.total_amount
        		e.has_pay = response.data.has_pay
        		e.status = response.data.status
        		e.deposit = response.data.deposit
        		e.dp = response.data.dp
        		e.remaining_payment = response.data.remaining_payment
        		e.deposit_status = response.data.deposit_status

        		e.show_deposit_status = e.deposit != 0;
        		e.can_settlement_deposite = (e.deposit != 0) && (e.deposit_status == "Unsettled")
        		e.can_make_payment = e.status == "Unsettled"
        		setTimeout(function(){
        			e.onload = false;
        		}, 100)
        	})
		}
	},
	created : function(){
		axios.get(_URL.cash).then( (response) => { this.option.cashes = response.data })
	}
})

window._checkMessage("message.booking.index");