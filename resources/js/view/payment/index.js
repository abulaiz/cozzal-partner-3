require('../../bootstrap');
window.Vue = require('vue');

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

var _URL = {};
_URL['index'] = $("#api-index").text();
_URL['owners'] = $("#api-owners").text();

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
			    columns: [
			        {data: 'receipt_number', name: 'receipt_number'},
			        {data: 'created_at', name: 'created_at'},
			        {data: 'nominal_paid', name: 'nominal_paid'},
			        {data: 'status', name: 'status'},
			        {data: '_action', name: '_action', orderable: false, searchable: false}
			    ]				
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


