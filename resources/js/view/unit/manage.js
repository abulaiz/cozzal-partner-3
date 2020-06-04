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

var _URL = {};
_URL['edit'] = $("#api-edit").text();
_URL['update'] = $("#api-update").text();
_URL['owners'] = $("#api-owners").text();
_URL['apartments'] = $("#api-apartments").text();

$(".rm").remove();

var app = new Vue({
	el : "#content",
	data : {
		edit : {
			owner : null,
			apartment : null,
			unit_number : '',
			owner_price_weekday : null,
			owner_price_weekend : null,
			owner_price_weekly : null,
			owner_price_monthly : null,
			rent_price_weekday : null,
			rent_price_weekend : null,
			rent_price_weekly : null,
			rent_price_monthly : null,
			charge : null			
		},
		option : {
			owners : [],
			apartments : []
		},
        cleaveOption : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'            
        },
        onsubmit : false		
	},
	methods : {
		setSelect : function(referenced_data, id){
			for(let i in referenced_data){
				if(id == referenced_data[i].id)
					return referenced_data[i];
			}
		},
		load_unit_data : function(){
			let e = this;
			axios.get(_URL.edit).then(function (response) { 
				const owner_price = JSON.parse(response.data.owner_rent_price);
				const rent_price = JSON.parse(response.data.rent_price);
				e.edit.unit_number = response.data.unit_number;
				e.edit.owner_price_weekday = Number(owner_price.WD);
				e.edit.owner_price_weekend = Number(owner_price.WE);
				e.edit.owner_price_weekly = Number(owner_price.WK);
				e.edit.owner_price_monthly = Number(owner_price.MN);
				e.edit.rent_price_weekday = Number(rent_price.WD);
				e.edit.rent_price_weekend = Number(rent_price.WE);
				e.edit.rent_price_weekly = Number(rent_price.WK);
				e.edit.rent_price_monthly = Number(rent_price.MN);
				e.edit.charge = response.data.charge;
				e.edit.owner = e.setSelect(e.option.owners, response.data.owner_id);
				e.edit.apartment = e.setSelect(e.option.apartments, response.data.apartment_id);
			})			
		},
		load_owners : async function(){
			let e = this;
			await axios.get(_URL.owners).then(function (response) { 
				e.option.owners = response.data.data;
			})
		},
		load_apartments : async function(){
			let e = this;
			await axios.get(_URL.apartments).then(function (response) { 
				e.option.apartments = response.data.data;
			})
		},
		update : function(){
			let e = this;
			e.onsubmit = true;
		    axios.post(_URL.update , {
				owner_id : e.edit.owner.id,
				apartment_id : e.edit.apartment.id,
				unit_number : e.edit.unit_number,
				owner_price_weekday : e.edit.owner_price_weekday,
				owner_price_weekend : e.edit.owner_price_weekend,
				owner_price_weekly : e.edit.owner_price_weekly,
				owner_price_monthly : e.edit.owner_price_monthly,
				rent_price_weekday : e.edit.rent_price_weekday,
				rent_price_weekend : e.edit.rent_price_weekend,
				rent_price_weekly : e.edit.rent_price_weekly,
				rent_price_monthly : e.edit.rent_price_monthly,
				charge : e.edit.charge,
				_method : 'PUT'
		    }).then(function (response) {
		        if(response.data.success){
		        	_leftAlert('Success', 'Data successfully updated', 'success');
		        } else {
		            for(let i in response.data.errors){
		                _leftAlert('Warning !', response.data.errors[i], 'warning', false);
		            }
		        }
		    })
		    .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })  
		    .then(function(){ e.onsubmit = false; })			
		}
	},
	created : async function(){
		await this.load_owners();
		await this.load_apartments();
		this.load_unit_data();
		$(".op-0").css('opacity', '1');
	}
});