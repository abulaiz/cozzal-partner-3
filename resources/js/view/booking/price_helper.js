'use_strict'

const DayClassifiers = require('../../libs/DayClassifiers');

class PriceHelper {

	constructor () {
		this.url = {};
		this.unit = {};
		this.check_in = null
		this.check_out = null
		this.events = {}
		this.unit_mod_prices = []
		this.guest_count
		this.allow_mod_price = false
		this.days = {
			we : 0, wd : 0, wk : 0, mn : 0, mod : []
		}
		this.prices = {}
		this.day_classifiers = new DayClassifiers();
	}

	// Utilites Method

	toDate(str){ return new Date(str) }

	StringOfDate(date, inc = 0){
		date.setDate(date.getDate()+inc);
		date.setHours(7); date.setMinutes(0); date.setSeconds(0); date.setMilliseconds(0);
		bln = date.getMonth()+1; thn = date.getFullYear(); hr = date.getDate();
		b2 = Number(bln); h2 = Number(hr);
		if(b2<10) bln = '0'+ bln; if(h2<10) hr = '0'+ hr;
		return thn+'-'+bln+'-'+hr;
	}	

	diff_date(start_time, end_time){
		const d1 = this.toDate(start_time).getTime();
		const d2 = this.toDate(end_time).getTime();
		return (d2-d1)/86400000;
	}

	// Setter Methods

	setUnitModPrices (onsuccess) {
		let e = this;
        axios.post(this.url.mod_prices, {
            unit_id : this.unit.id,
            check_in : this.check_in,
            check_out : this.check_out
        }).then(function (response) { 
        	e.unit_mod_prices = response.data;
        	onsuccess(response.data.length);
        })		
	}

	addEventListener (name, callback) { this.events[name] = callback } 

	setURL (url) { this.url = url }

	addModPrices() {
		this.prices['mod_prices'] = [];
		for(let i in this.days.mod){
			this.prices.mod_prices.push({
				rent_price : this.days.mod[i].rent_price,
				owner_price : this.days.mod[i].owner_price
			});
		}
	}

	setUnit (data, callback){
		let e = this;
		this.unit = data;
		this.setUnitModPrices((mod_price_count) => {
			this.day_classifiers.describe_days_type(
				this.check_in,
				this.check_out,
				this.unit_mod_prices,
				this.days
			);

			if(this.events.updateDays !== undefined)
				this.events.updateDays(this.days)

			this.addModPrices();

			this.generatePrice();
			callback(mod_price_count)
		});
	}

	setGuestCount (value, callback){ 
		this.guest_count = value 
		let ech = this.guest_count - 5;
		this.prices['charge'] = ech > 0 ? ech * this.unit.charge : 0;			
		this.generatePrice(false, this.prices)
		callback( this.guest_count > 5 )
	}

	setDate (check_in, check_out){
		this.check_in = check_in;
		this.check_out = check_out;
	}

	setVisibilityFields (deposite, charge) {
		let v_params = {
            owner_weekday_price : true, 
            owner_weekend_price : true, 
            owner_weekly_price : true, 
            owner_monthly_price : true, 
            rent_weekday_price : true, 
            rent_weekend_price : true, 
            rent_weekly_price : true, 
            rent_monthly : true, 
            charge : charge, 
            deposite : deposite
		}
		if(this.days.wd == 0){v_params.owner_weekday_price = false; v_params.rent_weekday_price = false;}
		if(this.days.we == 0){v_params.owner_weekend_price = false; v_params.rent_weekend_price = false;}
		if(this.days.wk == 0){v_params.owner_weekly_price = false; v_params.rent_weekly_price = false;}
		if(this.days.mn == 0){v_params.owner_monthly_price = false; v_params.rent_monthly_price = false;}
		if(this.events.showPricingField !== undefined)
			this.events.showPricingField(v_params)	
	}

	// Procedures Method

	generatePrice (modified_visibility = true, default_price = null, recalculate_total = false){ 
		if(Object.keys(this.unit).length == 0) return;

		if(modified_visibility){
			let show_charge, show_deposite;
			
			if(!(this.days.mn ==0 && this.days.wk==0)){ // if not daily price
				show_charge = false;
				this.prices['charge'] = 0;
			} else {
				let ech = this.guest_count - 5;
				this.prices['charge'] = ech > 0 ? ech * this.unit.charge : 0;		
				show_charge = true;			
			}

			if(this.days.mn==0){
				show_deposite = false; 
				this.prices['deposite'] = 0;
			} else {
				show_deposite = true; 
				this.prices['deposite'] = 200000;
			}

			this.setVisibilityFields(show_deposite, show_charge)	
		}

		let kind = ['weekday_price', 'weekend_price', 'weekly_price', 'monthly_price'];
		let k = ['wd', 'we', 'wk', 'mn'];
		let rent_price = JSON.parse(this.unit.rent_price)
		let owner_price = JSON.parse(this.unit.owner_rent_price)

		if(default_price == null){
			this.prices['owner_price_total'] = 0;
			this.prices['rent_price_total'] = 0;

			for(let i in kind){
				this.prices['rent_'+kind[i]] = Number(rent_price[k[i].toUpperCase()])
				this.prices['owner_'+kind[i]] = Number(owner_price[k[i].toUpperCase()])
				this.prices['rent_price_total'] += this.days[k[i]] * this.prices['rent_'+kind[i]];
				this.prices['owner_price_total'] += this.days[k[i]] * this.prices['owner_'+kind[i]];
			}
			for(let i in this.days.mod){
				this.prices['rent_price_total'] += this.days.mod[i].days * Number(this.prices.mod_prices[i].rent_price)
				this.prices['owner_price_total'] += this.days.mod[i].days * Number(this.prices.mod_prices[i].owner_price)
			}	
		} else {
			this.prices = default_price;
		}
		
		// Recalculate owner_total and rent_total
		if( recalculate_total ) {
			this.prices['owner_price_total'] = 0;
			this.prices['rent_price_total'] = 0;
			for(let i in kind){
				this.prices['rent_price_total'] += this.days[k[i]] * Number(this.prices['rent_'+kind[i]]);
				this.prices['owner_price_total'] += this.days[k[i]] * Number(this.prices['owner_'+kind[i]]);
			}
			for(let i in this.days.mod){
				this.prices['rent_price_total'] += this.days.mod[i].days * Number(this.prices.mod_prices[i].rent_price)
				this.prices['owner_price_total'] += this.days.mod[i].days * Number(this.prices.mod_prices[i].owner_price)
			}				
		}

		if(Object.keys(this.prices).length == 0) return;

		this.prices['amount_bill'] = Number(this.prices['charge']) + Number(this.prices['deposite']) + Number(this.prices['rent_price_total'])		
		
		if(default_price == null) 
			this.prices['normal_amount_bill'] = this.prices['amount_bill'];

		if(this.events.updatePricingField !== undefined)
			this.events.updatePricingField(this.prices)
	}

}

module.exports = PriceHelper