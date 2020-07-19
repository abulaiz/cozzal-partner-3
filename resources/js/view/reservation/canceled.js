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

var _URL = {};
_URL['index'] = $("#api-canceled-index").text();
_URL['cash'] = $("#api-cashes").text();
_URL['destroy'] = $("#api-destroy").text();
_URL['settlement'] = $("#api-settlement").text();

$(".rm").remove();

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'error', false);
    return false;
}

function toIDR(x){
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
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
	        {data: 'dp', name: 'dp', searchable: false},
	        {data: 'settlement', name: 'settlement', searchable: false},
	        {data: '_action', name: '_action', orderable: false, searchable: false},
	    ],
	    createdRow: function( row, data, dataIndex ) {
	        $( row ).find('td:eq(2)').text( data.check_in.substr(0, 10) );       
	        $( row ).find('td:eq(3)').text( data.check_out.substr(0, 10) );       
	        $( row ).find('td:eq(5)').text( toIDR(data.dp) );       
			$( row ).find('td:eq(6)').text( Number(data.settlement) == 0 ? '-' : toIDR(data.settlement) ); 	        

	        if(Number(data.settlement) == 0 && Number(data.dp) > 0){
	        	$( row ).find('.delete').remove();
	        } else {
	        	$( row ).find('.settlement').remove();
	        }  
	    }	       
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

window._delete = function(e){
	let data = Table.row($(e).parents('tr')).data();
	_confirm("Are you sure ?", "Data will be deleted parmanently", function(){
		axios.post(_URL.destroy , {
			reservation_id : data.id
		}).then(function (response) {
			if(response.data.success){
				_leftAlert('Success', 'Data successfuly deleted !', 'success');
				Table.ajax.reload();
			} else {
				_leftAlert('Error', 'Something wrong, try again', 'error');
			}
		})
		.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
	});	
}

window._settlement = function(e){
	let data = Table.row($(e).parents('tr')).data();
	payment.setId(data.id);
}

var payment = new Vue({
	el : "#settlement-modals",
	data : {
		id : null,
		onsubmit : false,
		option : { cashes : [] },
        cleave : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'       
        },		
		input : {
			cash : null, fund : null
		}
	},
	methods : {
		submit(){
			if( this.input.cash == null )
				return _catch_with_toastr('Please select source fund');
			if( this.input.fund == null )
				return _catch_with_toastr('Fund is required');
			if( this.input.fund < 0 )
				return _catch_with_toastr('Fund not meet requirement of payment');			

			let e = this;
			axios.post(_URL.settlement , {
				reservation_id : this.id,
				cash_id : this.input.cash.id,
				fund : this.input.fund
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'DP has been settled !', 'info');
					e.$refs.close.click();
					Table.ajax.reload();
				} else {
					_leftAlert('Sorry', response.data.message, 'warning');
				}
			})
			.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })			
		},
		setId(id){
			this.id = id;
		}
	},
	created : function(){
		axios.get(_URL.cash).then( (response) => { this.option.cashes = response.data })
	}
})


window._checkMessage("message.reservation.canceled");