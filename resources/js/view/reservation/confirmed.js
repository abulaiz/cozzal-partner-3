require('../../bootstrap');
window.Vue = require('vue');

var _URL = {};
_URL['index'] = $("#api-confirmed-index").text();

$(".rm").remove();

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
	    ]   
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

window._checkMessage("message.reservation.confirmed");