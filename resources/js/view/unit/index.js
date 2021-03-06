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
	const price = JSON.parse(str_arr);
	arr.push("Weekday : "+window._currencyFormat(price['WD']));
	arr.push("Weekend : "+window._currencyFormat(price['WE']));
	arr.push("Weekly : "+window._currencyFormat(price['WK']));
	arr.push("Monthly : "+window._currencyFormat(price['MN']));
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
	        {data: '_owner', name: '_owner'},
	        {data: 'see_more', name: 'see_more'},
	        {data: 'see_more', name: 'see_more'},
	        {data: 'charge', name: 'charge'},
	        {data: '_action', name: '_action', orderable: false, searchable: false},
	    ],
	    "fnDrawCallback": function( oSettings ) {
		    $(".tooltip-track-mouse" ).tooltip({
		      track: true,
		      content:function(){
		        return this.getAttribute("title");
		      }		      
		    });     
	    },
	    createdRow: function( row, data, dataIndex ) {
	        $( row ).find('td:eq(3)').attr('class', 'tooltip-track-mouse');
	        $( row ).find('td:eq(3)').attr('title', getPrices(data.rent_price));

	        $( row ).find('td:eq(4)').attr('class', 'tooltip-track-mouse');
	        $( row ).find('td:eq(4)').attr('title', getPrices(data.owner_rent_price));	     

	        $( row ).find('td:eq(5)').text( window._currencyFormat(data.charge) );   
	    } 	    
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