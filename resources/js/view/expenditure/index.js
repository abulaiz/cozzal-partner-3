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
	        {data: '_necessary', name: '_necessary'},
	        {data: '_cash', name: '_cash'},
	        {data: 'price', name: 'price'},
	        {data: 'qty', name: 'qty'},
	        {data: '_total', name: '_total'},
	        {data: '_action', name: '_action', orderable: false, searchable: false},
	    ],
	    order: [[ 0, 'desc' ]],
		fnRowCallback: function (nRow, data, iDisplayIndex) {
			let info = $(this).DataTable().page.info();
			$("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
			$("td:nth-child(5)", nRow).text(_currencyFormat(data.price));
			$("td:nth-child(7)", nRow).text(_currencyFormat(data._total));
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

window._checkMessage("message.expenditure.index");