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
_URL['billing'] = $("#api-billing").text();
_URL['non_billing'] = $("#api-non-billing").text();
_URL['destroy'] = $("#api-destroy").text();
_URL['approve'] = $("#api-approve").text();
_URL['pay'] = $("#api-pay").text();
_URL['cash'] = $("#api-cashes").text();

$(".rm").remove();

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'error', false);
    return false;
}

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
			    ajax: _URL.billing,
			    columns: [
			        {data: 'updated_at', name: 'updated_at'},
			        {data: 'description', name: 'description'},
			        {data: '_necessary', name: '_necessary'},
			        {data: 'price', name: 'price'},
			        {data: 'qty', name: 'qty'},
			        {data: '_total', name: '_total'},
			        {data: 'due_at', name: 'due_at'},
			        {data: '_action', name: '_action', orderable: false, searchable: false},
			    ],
			    order: [[ 0, 'desc' ]],
				fnRowCallback: function (nRow, data, iDisplayIndex) {
					let info = $(this).DataTable().page.info();
					$("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
					$("td:nth-child(4)", nRow).text(_currencyFormat(data.price));
					$("td:nth-child(6)", nRow).text(_currencyFormat(data._total));
					$("td:nth-child(7)", nRow).text(data.due_at.substr(0, 10));
				}					
			});
		},
		init_table2 : function(){
			try{ $('#datatables2').DataTable().destroy() } catch { }
			Table2 = $('#datatables2').DataTable({	
				processing: true,
				serverSide: true,		
			    ajax: _URL.non_billing,
			    columns: [
			        {data: 'updated_at', name: 'updated_at'},
			        {data: 'description', name: 'description'},
			        {data: '_necessary', name: '_necessary'},
			        {data: 'price', name: 'price'},
			        {data: 'qty', name: 'qty'},
			        {data: '_total', name: '_total'},
			        {data: '_action', name: '_action', orderable: false, searchable: false},
			    ],
			    order: [[ 0, 'desc' ]],
				fnRowCallback: function (nRow, data, iDisplayIndex) {
					let info = $(this).DataTable().page.info();
					$("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
					$("td:nth-child(4)", nRow).text(_currencyFormat(data.price));
					$("td:nth-child(6)", nRow).text(_currencyFormat(data._total));					
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
		if( sessionStorage.getItem('approval_type') !== undefined && sessionStorage.getItem('approval_type') != null ){
		    let m = sessionStorage.getItem('approval_type');
		    sessionStorage.removeItem('approval_type');
		    console.log({tab : `tab${Number(m) - 1}`, table : Number(m) - 2});
		    this.$refs[`tab${Number(m) - 1}`].click();
		    this.show( Number(m) - 2 );	
		} else {
			this.show(0);	
		}	
	}
})

var approve = new Vue({
	el : "#approve-modals",
	data : {
		option : {
			cashes : []
		},
		id : null,
		cash : null,
		approve_as_billing : false,
		due_date : null,
		onsubmit : false
	},
	methods : {
        notBeforeToday(date) {
          return date < new Date();
        },		
		setCashes(data){
			this.option.cashes = data;
		},
		setData(id){
			this.id = id;
			this.approve_as_billing = false;
			this.due_date = null;
			this.cash = null;
		},
		submit(){	
			let e = this;
			this.onsubmit = true;
			axios.post(_URL.approve , {
				id : this.id,
				type : this.approve_as_billing ? "2" : "1",
				cash_id : this.cash == null ? null : this.cash.id,
				due_at : this.due_date
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Expenditure has been payed', 'success');
					e.$refs.close.click();
					Table2.ajax.reload();
				} else {
					_leftAlert('Sorry', response.data.message, 'warning');
				}
			})
			.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then(function(){ e.onsubmit = false; })
		}
	}
})

var pay = new Vue({
	el : "#pay-modals",
	data : {
		option : {
			cashes : []
		},
		id : null,
		cash : null,
		onsubmit : false
	},
	methods : {
		setData(id){
			this.id = id;
		},
		submit(){
			if( this.cash == null )
				return _catch_with_toastr('Please select source fund');		
			let e = this;
			this.onsubmit = true;
			axios.post(_URL.pay , {
				id : this.id,
				cash_id : this.cash.id
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Expenditure has been payed', 'success');
					e.$refs.close.click();
					Table1.ajax.reload();
				} else {
					_leftAlert('Sorry', response.data.message, 'warning');
				}
			})
			.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then(function(){ e.onsubmit = false; })				
		}
	},
	mounted : async function(){
		await axios.get(_URL.cash).then( (response) => { this.option.cashes = response.data })
		approve.setCashes(this.option.cashes);
	}
})

window._delete = function(e){
	let Table = $(e).data("type") == 2 ? Table1 : Table2;
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

window._approve = function(e){
	let data = Table2.row($(e).parents('tr')).data();
	approve.setData(data.id);
	$("#approve").modal();
}

window._pay = function(e){
	let data = Table1.row($(e).parents('tr')).data();
	pay.setData(data.id);
	$("#pay").modal();
}

window._checkMessage("message.expenditure.approval");