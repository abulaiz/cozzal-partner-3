require('../../bootstrap');
window.Vue = require('vue');

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

var _URL = {};
_URL['index'] = $("#api-index").text();
_URL['destroy'] = $("#api-destroy").text();
_URL['owners'] = $("#api-owners").text();
_URL['invoice'] = $("#url-invoice").text();

$(".rm").remove();

var Table1, Table2, Table3;
var ck = { clear : function (){} };
var current_owner = null;
var first_load_unpaid = true;

var app = new Vue({
	el : "#app",
	data : {
		option : {
			owners : []
		},
		owner : null
	},
	watch : {
		owner : function(newVal){
			console.log(newVal)
			if(newVal == null) return;
			nav.update();			
		}
	},
	created : async function(){
		let e = this;
		const all_owners = {id : 0, name : 'All Owner'}
		await axios.get(_URL.owners).then(function (response) { 
			const owners = response.data.data;
			e.option.owners = [all_owners].concat(owners);
		})
		this.owner = all_owners;
	}
});

var nav = new Vue({
	el : "#nav-ul",
	data : {
		has_initialize_table : [false, false, false],
		current_tab : 0
	},
	methods : {
		init_table1 : function(){
			try{ $('#datatables1').DataTable().destroy() } catch { }
			Table1 = $('#datatables1').DataTable({
				processing: true, serverSide: true,		
			    ajax: `${_URL.index}?type=history&owner_id=${app.$data.owner.id}`,
			    order: [ [3, 'desc'], [0, 'desc'] ],
			    columns: [
			        {data: 'receipt_number', name: 'receipt_number'},
			        {data: 'created_at', name: 'created_at'},
			        {data: 'nominal_paid', name: 'nominal_paid'},
			        {data: 'status', name: 'status'},
			        {data: '_action', name: '_action', orderable: false, searchable: false}
			    ],
				fnRowCallback: function (nRow, data, iDisplayIndex) {
					$("td:nth-child(3)", nRow).text( _currencyFormat(data.nominal_paid) );
				}			    				
			});
		},
		init_table2 : function(){
			try{ $('#datatables2').DataTable().destroy() } catch { }
			Table2 = $('#datatables2').DataTable({	
			    ajax: `${_URL.index}?type=paid&owner_id=${app.$data.owner.id}`,
			    columns: [
			        {data: 'type', name: 'type'},
			        {data: 'receipt_number', name: 'receipt_number'},
			        {data: 'owner', name: 'owner'},
			        {data: 'unit', name: 'unit'},
			        {data: 'date', name: 'date'}
			    ]			    						
			});
		},
		init_table3 : function(){
			try{ $('#datatables3').DataTable().destroy() } catch { }
			Table3 = $('#datatables3').DataTable({
			    ajax: `${_URL.index}?type=unpaid&owner_id=${app.$data.owner.id}`,
			    columns: [
			    	{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
			        {data: 'type', name: 'type'},
			        {data: 'receipt_number', name: 'receipt_number'},
			        {data: 'owner', name: 'owner'},
			        {data: 'unit', name: 'unit'},
			        {data: 'date', name: 'date'}
			    ],
			    fnDrawCallback: function( oSettings ) {
			    	if(current_owner == app.$data.owner.id || first_load_unpaid) {
			    		first_load_unpaid = false; 
			    		return;
			    	}
					ck = new window.checkboxTable('skin-flat');
					ck.setChildClass('ckck');
					ck.setParentElement('#gck');
					ck.labelElement = $("#selected-rec-caption");
					ck.onUpdate = function(){
						if(this.value.length > 0){
							$("#propose-payment").removeAttr('disabled');
						} else {
							$("#propose-payment").attr({'disabled' : 'disabled'});
						}
					};
					current_owner = app.$data.owner.id					    
			    }		    				
			});
		},				
		show : function(index){
			this.current_tab = index;
			if( this.has_initialize_table[index] ) return;
			setTimeout( () => {
				if(index == 0) this.init_table1();
				if(index == 1) this.init_table2();
				if(index == 2) this.init_table3();
				this.has_initialize_table[index] = true;				
			}, 200 )
		},
		update : function(){			
			this.has_initialize_table = [false, false, false];
			first_load_unpaid = true;
			ck.clear();
			this.show(this.current_tab);
		}
	},
	created : function(){
		this.show(0);
	}
})

window.propose = function(){
	let x = SimpleEnc.encrypt(ck.value);
	window.location = _URL.invoice.replace('/0', '/' + x)
}

window.invoice = function(e){
	let data = Table1.row($(e).parents('tr')).data();
	let x = SimpleEnc.encrypt(data.id);
	window.location = _URL.invoice.replace('/0', '/' + x)
}

window.__remove = function(e){
	let data = Table1.row($(e).parents('tr')).data();
	_confirm("Are you sure ?", "Data will be deleted parmanently", function(){
		axios.post(_URL.destroy , {
			id : data.id
		}).then(function (response) {
			if(response.data.success){
				_leftAlert('Success', 'Data successfuly deleted !', 'success');
				nav.update();	
			} else {
				_leftAlert('Error', 'Something wrong, try again', 'error');
			}
		})
		.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
	});	
}

window._checkMessage("message.payment.index");