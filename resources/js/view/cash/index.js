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

$(".rm").remove();

var cleaveOption = {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
};

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
	        {data: '_description', name: '_description'}
	    ]
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
		onsubmit : false
	},
	methods : {
		setData : function(id, name){
			this.id = id;
			this.cash_name = name;
			this.fund = '';
		},
		submit : function(){
			let e = this;
			e.onsubmit = true;
			axios.post(_URL.update , {
				id :  e.id,
				fund : e.fund,
				_method : 'PUT'
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					Table.ajax.reload();
					e.$refs.closeModal.click();
					loadCashList();
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
		onsubmit : false
	},
	methods : {
		submit : function(){
			let e = this;
			e.onsubmit = true;
			axios.post(_URL.store , {
				name :  e.name,
				balance : e.balance
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					e.name = '';
					e.balance = '';
					Table.ajax.reload();
					e.$refs.closeModal.click();
					loadCashList();
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

var create_mutation = new Vue({
	el : "#create-mutation",
	data : {
		from_cash_id : null,
		to_cash_id : null,
		cashes : [],
		fund : "",
		cleaveOption : cleaveOption,
		onsubmit : false
	},
	methods : {
		submit : function(){
			let e = this;
			e.onsubmit = true;
			axios.post(_URL.store_mutation , {
				from_cash_id :  e.from_cash_id == null ? null : e.from_cash_id.id,
				to_cash_id : e.to_cash_id == null ? null : e.to_cash_id.id,
				fund : e.fund
			}).then(function (response) {
				if(response.data.success){
					_leftAlert('Success', 'Data successfuly updated !', 'success');
					e.from_cash_id = null;
					e.to_cash_id = null;
					e.fund = '';
					Table.ajax.reload();
					e.$refs.closeModal.click();
					loadCashList();
				} else {
					for(let i in response.data.errors){
						_leftAlert('Warning !', response.data.errors[i], 'warning', false);
					}
				}
			})
			.catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
			.then(function(){ e.onsubmit = false; })
		},
		setData : function(data){
			this.cashes = data;
		}		
	}
});

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