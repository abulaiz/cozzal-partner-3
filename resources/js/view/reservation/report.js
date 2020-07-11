require('../../bootstrap');
window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

// Date Picker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.component('date-picker', DatePicker);

// Radio and Checkbox
import PrettyCheck from 'pretty-checkbox-vue/check';
Vue.component('p-check', PrettyCheck);

var _URL = {};
_URL['booked'] = $("#api-booked").text();
_URL['confirmed'] = $("#api-confirmed").text();

$(".rm").remove();

var Table1, Table2;
var nav = new Vue({
	el : "#nav-ul",
	data : {
		has_initialize_table : [false, false],
		current_tab : 0
	},
	methods : {
		init_table1 : function(){
			try{ $('#datatables1').DataTable().destroy() } catch { }
			Table1 = $('#datatables1').DataTable({
				processing: true,
				serverSide: true,		
			    ajax: _URL.confirmed,
			    columns: [
			        {data: 'tenant', name: 'tenant'},
			        {data: 'unit', name: 'unit'},
			        {data: 'check_in', name: 'check_in'},
			        {data: 'check_out', name: 'check_out'},
			        {data: 'owner_rent_prices', name: 'owner_rent_prices'},
			        {data: 'status', name: 'status'}
			    ],
			    order: [[ 5, 'asc' ], [2, 'desc']],
			    createdRow: function( row, data, dataIndex ) {
			        $( row ).find('td:eq(2)').text( data.check_in.substr(0, 10) );       
			        $( row ).find('td:eq(3)').text( data.check_out.substr(0, 10) );       
			        $( row ).find('td:eq(4)').text( window._currencyFormat(JSON.parse(data.owner_rent_prices).TP) );       
			    }				
			});
		},
		init_table2 : function(){
			try{ $('#datatables2').DataTable().destroy() } catch { }
			Table2 = $('#datatables2').DataTable({	
				processing: true,
				serverSide: true,		
			    ajax: _URL.booked,
			    columns: [
			        {data: 'tenant', name: 'tenant'},
			        {data: 'unit', name: 'unit'},
			        {data: 'check_in', name: 'check_in'},
			        {data: 'check_out', name: 'check_out'},
			        {data: 'owner_rent_prices', name: 'owner_rent_prices'}
			    ],
			    order: [[ 2, 'desc' ]],
			    createdRow: function( row, data, dataIndex ) {
			        $( row ).find('td:eq(2)').text( data.check_in.substr(0, 10) );       
			        $( row ).find('td:eq(3)').text( data.check_out.substr(0, 10) );       
			        $( row ).find('td:eq(4)').text( window._currencyFormat(JSON.parse(data.owner_rent_prices).TP) );       
			    }			    						
			});
		},			
		show : function(index){
			this.current_tab = index;
			if( this.has_initialize_table[index] ) return;
			setTimeout( () => {
				if(index == 0) this.init_table1();
				if(index == 1) this.init_table2();
				this.has_initialize_table[index] = true;				
			}, 200 )
		}
	},
	mounted : function(){
		this.show(0);	
	}
})