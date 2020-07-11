require('../../bootstrap');
window.Vue = require('vue');

import VueHtml2pdf from 'vue-html2pdf'

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

var _URL = {};
_URL['invoice'] = $("#api-invoice").text();

$(".rm").remove();

var content = new Vue({
	el : "#content",
	data : {
		invoice : null,
		day_type : {
			we : 'Weekend',
			wd : 'Weekday',
			wk : 'Weekly',
			mn : 'Monthly'			
		},
		ondownload : false
	},
	methods : {
		setDays(days){
			let day = {wd : days.wd, we : days.we, mn : days.mn, wk : days.wk};
			let total_days = 0;
			let non_zero_day = 0;
			let referenced_single_day = '';
			let description = "";
			for(let x in days.mod){
				day.wd += days.mod[x].wd;
				day.we += days.mod[x].we;
			}
			for(let i in day){
				total_days += day[i];
				if(day[i] != 0){
					non_zero_day++;
					referenced_single_day = i;
					description += `, ${day[i]} ${this.day_type[i]}`
				}
			}
			
			this.invoice['days'] = {
				total : total_days,
				detail : day,
				is_single_day : non_zero_day < 2,
				referenced_single_day : referenced_single_day.toUpperCase(),
				description : description.substr(2)
			};
			console.log(this.invoice['days'])
		},
		setPrices(prices, mod_prices){
			this.invoice['prices'] = JSON.parse(prices);
			mod_prices = JSON.parse(mod_prices);
			// If price overrided by date
			if(mod_prices.length > 0){
				let mod_price_days = {we : {day : 0, price : 0}, wd : {day : 0, price : 0} }
				for(let i in mod_prices){
					mod_price_days.we.day += mod_prices[i].we;
					mod_price_days.wd.day += mod_prices[i].wd;
					mod_price_days.we.price += mod_prices[i].rent_price * mod_prices[i].we;
					mod_price_days.wd.price += mod_prices[i].rent_price * mod_prices[i].wd;					
				}

				// Recalculate (intinya harga sewa = (total_mod_price + harga_sewa_normal*selilih_total_hari-jumlah_modifikasi_hari) / lama sewa )
				this.invoice.prices["WD"] =  ( mod_price_days.wd.price + (this.invoice.prices["WD"]*(this.invoice.days.detail.wd - mod_price_days.wd.day)) ) / this.invoice.days.detail.wd;
				this.invoice.prices["WE"] =  ( mod_price_days.we.price + (this.invoice.prices["WE"]*(this.invoice.days.detail.we - mod_price_days.we.day)) ) / this.invoice.days.detail.we;
			}
		},
		setData(data){
			this.invoice = data;
			this.invoice['receipt_number'] = "COZ-"+data.id.toString(16).toUpperCase();	
			this.setDays(JSON.parse(data.days));
			this.setPrices( data.rent_prices, data.mod_prices);	
			preview.setData(this.invoice)		
		},
		generateReport(){ this.ondownload = true; preview.generateReport(); },
		hasGenerated(){ this.ondownload = false; }
	},	
	created : function(){
		axios.get(_URL.invoice).then( (response) => { this.setData(response.data) })
		$(".op-0").css('opacity', '1')
	}
})

var preview = new Vue({
	el : "#preview",
	data : {
		invoice : null
	},
	methods : {
        generateReport () {
            this.$refs.html2Pdf.generatePdf()
        },
        setData (invoice){
        	this.invoice = invoice;
        },
        onProgress(e){
        	if(e == 100)
        		content.hasGenerated()
        },
		isMobile() {
			if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
				return true
			} else {
				return false
			}
		}        		
	},
    components: {
        VueHtml2pdf
    },		
})

window._download = function(){
	content.generateReport();
}