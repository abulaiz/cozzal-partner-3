require('../../bootstrap');

window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

var _URL = {};
_URL['index'] = $("#url-api-units").text();
_URL['destroy'] = $("#url-api-units-destroy").text();

$(".rm").remove();

function getPrices(str_arr){
	let arr = [];
	console.log(str_arr);
	const price = JSON.parse(str_arr);
	arr.push("Weekday : "+price['WD']);
	arr.push("Weekend : "+price['WE']);
	arr.push("Weekly : "+price['WK']);
	arr.push("Monthly : "+price['MN']);
	return arr.join("<br>");
}

function tableOptions(){
	return {
		processing: true,
		serverSide: true,		
	    ajax: _URL.index,
	    columns: [
	        {data: 'unit_number', name: 'unit_number'},
	        {data: '_apartment', name: '_apartment'},
	        {data: '_address', name: '_address'},
	        {data: '_status', name: '_status'},
	        {data: '_action', name: '_action', orderable: false, searchable: false},
	    ]	    
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

window._delete = function(e){
	let data = Table.row($(e).parents('tr')).data();
	_confirm("Are you sure ?", "Data will be deleted parmanently", function(){
			axios.post(_URL.destroy.replace('/0', '/'+data.id) , {
				_method : 'delete'
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