'use_strict'

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

	setUnit (data, callback){
		let e = this;
		this.unit = data;
		this.setUnitModPrices((mod_price_count) => {
			this.describe_days_type();
			this.generatePrice();
			callback(mod_price_count)
		});
	}

	setGuestCount (value){ 
		this.guest_count = value 
		let ech = this.guest_count - 5;
		this.prices['charge'] = ech > 0 ? ech * this.unit.charge : 0;			
		this.generatePrice(false, this.prices)
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
				this.prices['rent_'+kind[i]] = this.days[k[i]] * Number(rent_price[k[i].toUpperCase()])
				this.prices['owner_'+kind[i]] = this.days[k[i]] * Number(owner_price[k[i].toUpperCase()])
				this.prices['rent_price_total'] += this.prices['rent_'+kind[i]];
				this.prices['owner_price_total'] += this.prices['owner_'+kind[i]];
			}
			for(let i in this.days.mod){
				this.prices['rent_price_total'] += this.days.mod[i].days * Number(this.days.mod[i].rent_price)
				this.prices['owner_price_total'] += this.days.mod[i].days * Number(this.days.mod[i].owner_price)
			}	
		} else {
			this.prices = default_price;
		}
		
		// Recalculate owner_total and rent_total
		if( recalculate_total ) {
			this.prices['owner_price_total'] = 0;
			this.prices['rent_price_total'] = 0;
			for(let i in kind){
				this.prices['rent_price_total'] += Number(this.prices['rent_'+kind[i]]);
				this.prices['owner_price_total'] += Number(this.prices['owner_'+kind[i]]);
			}
			for(let i in this.days.mod){
				this.prices['rent_price_total'] += this.days.mod[i].days * Number(this.days.mod[i].rent_price)
				this.prices['owner_price_total'] += this.days.mod[i].days * Number(this.days.mod[i].owner_price)
			}				
		}

		if(Object.keys(this.prices).length == 0) return;

		this.prices['amount_bill'] = Number(this.prices['charge']) + Number(this.prices['deposite']) + Number(this.prices['rent_price_total'])		
		
		if(default_price == null) 
			this.prices['normal_amount_bill'] = this.prices['amount_bill'];

		console.log(this.prices['normal_amount_bill'])
		if(this.events.updatePricingField !== null)
			this.events.updatePricingField(this.prices)
	}

	/*
	|--------------------------------------------------------------------------
	| Days Clasification section
	|--------------------------------------------------------------------------
	*/

	startinweekend(day, week, weekday_count, weekend_count){
		let we = 0; 
		let wd = day + 5;
		while(wd>5){
			we = 8-week; 
			day = wd-5;
			if(day==1) 
				we=1; 
			wd = day-we;
			weekend_count = weekend_count + we;
			if(wd>5) 
				weekday_count = weekday_count + 5; 
			else 
				weekday_count = weekday_count + wd;
		}
		return {'weekday' : weekday_count, 'weekend' : weekend_count};
	}

	getdetailweek(start_time, day){
		var wd;
		var week = this.toDate(start_time).getDay();
		console.log({start_time : start_time, hari : day})
		// week = posisi hari, dari minggu (1) - sabtu (7)
		week++;
		if(week>5){ //jika dimuai dari weekend
			return this.startinweekend(day, week, 0, 0);
		} else { //jika dimulai dri weekday
			if((week+day)<7) {
				return {'weekday' : day, 'weekend' : 0};
			} else {
				wd = 6 - week;
				return this.startinweekend(day-wd, 6, wd, 0);
			}
		}
	}

	setModDays(){
		for(let i in this.unit_mod_prices){
    		let sdt = this.unit_mod_prices[i].started_at; 
    		let edt = this.unit_mod_prices[i].ended_at;
    		
    		if(this.toDate(sdt) < this.toDate(this.check_in)) 
    			sdt = this.check_in;
    		if(this.toDate(edt) > this.toDate(this.check_out)) 
    			edt = this.check_out;
    		
    		let selisih = this.diff_date(sdt,edt);
    		if( sdt != this.check_in ) selisih++;
    		
    		const weeks = this.getdetailweek(sdt, selisih);
    		this.days.mod.push({
    			days : weeks.weekday + weeks.weekend, 
    			owner_price : Number(this.unit_mod_prices[i].owner_price),
    			rent_price : Number(this.unit_mod_prices[i].price)
    		})
			this.days.wd -= weeks.weekday;
			this.days.we -= weeks.weekend;
		}
	}

	setDaily(start, count_day){
		const weeks = this.getdetailweek(start, count_day);
		this.days.wd = weeks.weekday;
		this.days.we = weeks.weekend; 
	}

	monthly(){ //without mod days
		let days_total = this.days.wd + this.days.we;
		if(days_total>=28)
			return Math.floor(days_total/28); 
		else 
			return 0;
	}

	weekly(monthly){
		var d = (this.days.wd + this.days.we) - (monthly*28);
		if(monthly>0){
			this.days.wd = 0; this.days.we = 0; 
			if(d==7) return 1;  
			else if(d>7) { this.days.mn++; return 0; } 
			else { this.days.wd = d; this.days.we = 0; return 0;}
		} else {
			if(d>=7){
				var m = Math.floor(d/7);
				var sel = d - (m*7);
				// posisi check in dimajukan beberapa hari, dan lama pemesanan disesuaikan
				var last_pointer = this.StringOfDate(this.toDate(this.check_in), d-sel);
				this.setDaily(last_pointer, sel);
				return m;
			} else {
				this.setModDays();
				return 0;
			}
		}
	}

	describe_days_type () {
		this.days.mod = []
		this.setDaily(this.check_in, this.diff_date(this.check_in, this.check_out));
		this.days.mn = this.monthly();
		this.days.wk = this.weekly(this.days.mn);
		console.log(this.days);
	}
}

module.exports = PriceHelper