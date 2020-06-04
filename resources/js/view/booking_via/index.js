require('../../bootstrap');

window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

var _URL = {};
_URL['index'] = $("#url-api-booking-vias").text();
_URL['store'] = $("#url-api-booking-vias-store").text();
_URL['update'] = $("#url-api-booking-vias-update").text();
_URL['destroy'] = $("#url-api-booking-vias-destroy").text();

$(".rm").remove();

function tableOptions(){
	return {
		processing: true,
		serverSide: true,		
	    ajax: _URL.index,
	    columns: [
	        {data: 'name', name: 'name'},
	        {data: '_action', name: '_action', orderable: false, searchable: false},
	    ]
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

var modal_add = new Vue({
	el : "#add-modal",
	data : {
		name : '',
		onsubmit : false
	},
	methods : {
		submit : function(){
			let e = this;
			e.onsubmit = true;
			axios.post(_URL.store , {
				name :  e.name
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly added !', 'success');
					e.name = '';
					Table.ajax.reload();
					e.$refs.closeModal.click();
				} else {
					for(let i in response.data.errors){
						_leftAlert('Warning !', response.data.errors[i], 'warning', false);
					}
				}
			})
			.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then(function(){ e.onsubmit = false; })
		}
	}
});

var modal_edit = new Vue({
	el : "#edit-modal",
	data : {
		id : '',
		name : '',
		onsubmit : false
	},
	methods : {
		setData : function(id, name){
			this.id = id;
			this.name = name;
		},
		submit : function(){
			let e = this;
			e.onsubmit = true;
			axios.post(_URL.update.replace('/0', '/' + e.id) , {
				name :  e.name,
				_method : 'PUT'
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					e.name = '';
					Table.ajax.reload();
					e.$refs.closeModal.click();
				} else {
					for(let i in response.data.errors){
						_leftAlert('Warning !', response.data.errors[i], 'warning', false);
					}
				}
			})
			.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then(function(){ e.onsubmit = false; })
		}
	}
});

window._edit = function(e){
	let data = Table.row($(e).parents('tr')).data();

	modal_edit.setData( data.id, data.name );

	$("#modal2").modal({
		show : true, backdrop: 'static', keyboard: false
	});	
}

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