require('../../bootstrap');

window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

// Vue currency formater
import Cleave from 'vue-cleave-component';
Vue.component('cleave', Cleave);

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

Vue.component('upload-image', require('../../components/UploadImage.vue').default);

// Vue Charts
import ECharts from 'vue-echarts'
import 'echarts/lib/chart/pie'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/title'
import 'echarts/lib/component/legend'
Vue.component('v-chart', ECharts)

var _URL = {};
_URL['index'] = $("#url-api-cashes").text();
_URL['store'] = $("#url-api-cashes-store").text();
_URL['update'] = $("#url-api-cashes-update").text();
_URL['destroy'] = $("#url-api-cashes-destroy").text();
_URL['mutation'] = $("#url-api-cash-mutations").text();
_URL['store_mutation'] = $("#url-api-cash-mutation-store").text();
_URL['payment_slip'] = $("#url-payment-slip").text();

$(".rm").remove();

const cleaveOption = {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
};

const axios_config = {header : { 'Content-Type' : 'multipart/form-data' }}

function tableOptions(){
	return {
		processing: true,
		serverSide: true,		
	    ajax: _URL.mutation,
	    columns: [
	        {data: '_date', name: '_date'},
	        {data: '_cash', name: '_cash'},
	        {data: 'fund', name: 'fund'},
	        {data: '_type', name: '_type'},
	        {data: '_description', name: '_description'},
	        {data: '_action', name: '_action', orderable: false, searchable: false}
	    ],
	    order: [[ 0, 'desc' ]],
		fnRowCallback: function (nRow, data, iDisplayIndex) {
			$("td:nth-child(3)", nRow).text(_currencyFormat(data.fund));
		}
	};
}

var Table = $('#datatables').DataTable( tableOptions() );

function tooltip_formatter(data){
	return data.name + "<br>" +data.value.toLocaleString('tr-TR', {style: 'currency', currency: 'IDR'}) + " ("+data.percent+"%)";
}

var chart = new Vue({
	el : "#chart",
	data : {
		polar : {
            // Add title
            title: {
                text: 'Cash Persentation',
                subtext: 'Total of balance',
                x: 'center',
                textStyle: {
                    color: '#FFF'
                },
                subtextStyle: {
                    color: '#FFF'
                }
            },
            // Add tooltip
            tooltip: {
                trigger: 'item',
                    formatter: function (params) {
                        return tooltip_formatter(params);
                    }
            },
            // Add legend
            legend: {
                orient: 'vertical',
                x: 'left',
                textStyle: {
                    color: '#FFF'
                },                    
                data: []
            },
            // Add custom colors
            color: ['#ffd775', '#ff847c', '#e84a5f', '#2a363b', '#7fd5c3', '#61a781', '#f0c75e', '#df8c7d', '#e8ed8a', '#55bcbb', '#e974b9', '#2f9395'],
            // Add series
            series: [
	            {
	                name: 'Cash Persentation',
	                type: 'pie',
	                radius: '70%',
	                center: ['50%', '57.5%'],
	                itemStyle: {
	                    normal: {
	                        label: {
	                            show: true,
	                            textStyle: {
	                                color: '#FFF'
	                            }
	                        },
	                        labelLine: {
	                            show: true,
	                            lineStyle: {
	                                color: '#FFF'
	                            }
	                        }
	                    }
	                },                    
	                data: []
	            }
            ]			
		}
	},
	methods : {
		setData : function(legendData, series){
			this.polar.legend.data = legendData;
			this.polar.series[0].data = series;
		}
	}
});

var add_fund = new Vue({
	el : "#add-balance",
	data : {
		id : '',
		fund : '',
		cleaveOption : cleaveOption,
		cash_name : '',
		attachment : null,
		onsubmit : false
	},
	methods : {
		setData : function(id, name){
			this.id = id;
			this.cash_name = name;
			this.fund = '';
			this.attachment = null;
		},
		submit : function(){
			let data = new FormData();
			data.append('id', this.id)
			data.append('fund', this.fund)
			data.append('attachment', this.attachment == null ? '' : this.attachment)
			data.append('_method', 'PUT')
			this.onsubmit = true;
			axios.post(_URL.update , data, axios_config).then( (response) => {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					Table.ajax.reload();
					this.$refs.closeModal.click();
					loadCashList();
				} else {
					for(let i in response.data.errors){
						_leftAlert('Warning !', response.data.errors[i], 'warning', false);
					}
				}
			})
			.catch( () => { _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then( () => { this.onsubmit = false; })			
		}
	}
});

var cash_list = new Vue({
	el : "#cash_list",
	data : {
		cashes : []
	},
	methods : {
		setData : function(data){
			this.cashes = data;
		},
		addBalance : function(index){
			add_fund.setData( this.cashes[index].id, this.cashes[index].name );
			$("#modal3").modal();
		},
		removeCash : function(index){
			let data = this.cashes[index];
			let e = this;
			_confirm("Are you sure ?", "Data will be deleted parmanently", function(){
				axios.post(_URL.destroy.replace('/0', '/'+data.id) , {
					_method : 'delete'
				}).then(function (response) {
					if(response.data.success){
						_leftAlert('Success', 'Data successfuly deleted !', 'success');
						Table.ajax.reload();
						loadCashList();
					} else {
						for(let i in response.data.errors){
							_leftAlert('Warning !', response.data.errors[i], 'warning', false);
						}
					}
				})
				.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
			});				
		}
	},
	computed : {
		total_balance : function(){
			let e = 0;
			for(let i in this.cashes){
				e += this.cashes[i].balance;
			}
			return e;
		}
	}
});

var create_cash = new Vue({
	el : "#create-cash",
	data : {
		cleaveOption : cleaveOption,
		name : '',
		balance : '',
		attachment : null,
		onsubmit : false
	},
	methods : {
		submit : function(){
			let data = new FormData();
			data.append('name', this.name)
			data.append('balance', this.balance)
			data.append('attachment', this.attachment == null ? '' : this.attachment)
			this.onsubmit = true;
			
			axios.post(_URL.store , data, axios_config).then( (response) => {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					this.name = '';
					this.balance = '';
					this.attachment = null;
					Table.ajax.reload();
					this.$refs.closeModal.click();
					loadCashList();
				} else {
					for(let i in response.data.errors){
						_leftAlert('Warning !', response.data.errors[i], 'warning', false);
					}
				}
			})
			.catch( () => { _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then( () => { this.onsubmit = false; })
		}
	}
});

var create_mutation = new Vue({
	el : "#create-mutation",
	data : {
		from_cash_id : null,
		to_cash_id : null,
		cashes : [],
		fund : "",
		cleaveOption : cleaveOption,
		attachment : null,
		onsubmit : false
	},
	methods : {
		submit : function(){
			let data = new FormData();
			data.append('from_cash_id', this.from_cash_id == null ? null : this.from_cash_id.id)
			data.append('to_cash_id', this.to_cash_id == null ? null : this.to_cash_id.id)
			data.append('fund', this.fund)
			data.append('attachment', this.attachment == null ? '' : this.attachment)
			this.onsubmit = true;
			axios.post(_URL.store_mutation , data, axios_config).then( (response) => {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					this.from_cash_id = null;
					this.to_cash_id = null;
					this.fund = '';
					Table.ajax.reload();
					this.$refs.closeModal.click();
					this.attachment = null;
					loadCashList();
				} else {
					for(let i in response.data.errors){
						_leftAlert('Warning !', response.data.errors[i], 'warning', false);
					}
				}
			})
			.catch( () => { _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then( () => { this.onsubmit = false; })
		},
		setData : function(data){
			this.cashes = data;
		}		
	}
});

var detail_mutation = new Vue({
	el : "#detail-mutation",
	data : {
		data : null
	},
	methods : {
		setData(data){
			this.data = data;
		}
	}
})

function setChart(data){
	let series_data = [];
	let legend_data = [];
	for(let i in data){
		series_data.push({ value : data[i].balance, name : data[i].name });
		legend_data.push( data[i].name );
	}
	chart.setData(legend_data, series_data);
}

function loadCashList(){
	axios.get(_URL.index)
	.then(function (response) {
		cash_list.setData(response.data);
		create_mutation.setData(response.data);
		setChart(response.data);		
	})
	.catch(function (error) {
	}); 	
}

loadCashList();

window.onload = function(){
	$(".op-0").css('opacity', '1');
}

window.detail = function(e){
	let data = Table.row($(e).parents('tr')).data();
	console.log(data)
	detail_mutation.setData({
		mutation_date : data.updated_at,
		cash : data._cash,
		fund : _currencyFormat(data.fund),
		type : data._type,
		description : data._description,
		executor : data._executor,
		attachment : _URL.payment_slip.replace("/0", "/"+data.id)
	})
	$("#modal4").modal();
}