require('../../bootstrap');

var _URL = {};
_URL['index'] = $("#api-url-expenditures").text();
_URL['destroy'] = $("#api-destroy").text();

$(".rm").remove();

function tableOptions(){
	return {
		processing: true,
		serverSide: true,		
	    ajax: _URL.index,
	    columns: [
	        {data: 'updated_at', name: 'updated_at'},
	        {data: 'description', name: 'description'},
	        {data: '_unit', name: '_unit'},
	        {data: '_total', name: '_total'},
	        {data: '_status', name: '_status'},
	    ],
	    order: [[ 0, 'desc' ]],
		fnRowCallback: function (nRow, data, iDisplayIndex) {
			$("td:nth-child(4)", nRow).text(_currencyFormat(data._total));
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
				_leftAlert('Success', 'Expenditure successfuly canceled !', 'info');
				Table.ajax.reload();
			} else {
				_leftAlert('Error', 'Something wrong, try again', 'error');
			}
		})
		.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
	});
}
