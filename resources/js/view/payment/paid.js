var _URL = {};
_URL['index'] = $("#api-index").text();
_URL['invoice'] = $("#url-invoice").text();

var Table = $('#datatables').DataTable({
	processing: true, serverSide: true,		
    ajax: _URL.index,
    columns: [
        {data: 'title', name: 'title'},
        {data: 'transaction_count', name: 'transaction_count'},
        {data: 'nominal_paid', name: 'nominal_paid'},
        {data: '_action', name: '_action', orderable: false, searchable: false}
    ],
    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:eq(2)').text( Number(data.nominal_paid).toLocaleString('tr-TR', {style: 'currency', currency: 'IDR'}));       
    }    				
});

window.invoice = function(e){
    let data = Table.row($(e).parents('tr')).data();
    let x = SimpleEnc.encrypt(data.id);
    window.location = _URL.invoice.replace('/0', '/' + x)
}