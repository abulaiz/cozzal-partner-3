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

// var Table = $('#datatables').DataTable( tableOptions() );

window._checkMessage("message.reservation.canceled");