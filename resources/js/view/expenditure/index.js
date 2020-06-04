require('../../bootstrap');

window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

var _URL = {};
_URL['index'] = $("#api-url-expenditures").text();

$(".rm").remove();

function tableOptions(){
	return {
		processing: true,
		serverSide: true,		
	    ajax: _URL.index,
	    columns: [
	        {data: 'id', name: 'id'},
	        {data: 'description', name: 'description'},
	        {data: '_necessary', name: '_necessary'},
	        {data: '_cash', name: '_cash'},
	        {data: 'price', name: 'price'},
	        {data: 'qty', name: 'qty'},
	        {data: '_total', name: '_total'},
	        {data: 'id', name: 'id', orderable: false, searchable: false},
	    ],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			var info = $(this).DataTable().page.info();
			$("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
			return nRow;
		}	    
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

window._checkMessage("message.expenditure.index");