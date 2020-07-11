'use_strict'

class DayClassifiers {

	constructor () {
		this.check_in = null
		this.check_out = null
		this.unit_mod_prices = []
		this.days = {
			we : 0, wd : 0, wk : 0, mn : 0, mod : []
		}
		this.calculate_mod_days = true
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
    		let sdt = this.unit_mod_prices[i].started_at.substr(0, 10); 
    		let edt = this.unit_mod_prices[i].ended_at.substr(0, 10);
    		
    		if(this.toDate(sdt) < this.toDate(this.check_in)) 
    			sdt = this.check_in;
    		if(this.toDate(edt) > this.toDate(this.check_out)) 
    			edt = this.check_out;
    		
    		let selisih = this.diff_date(sdt,edt);
    		if( sdt != this.check_in ) selisih++;
    		
    		const weeks = this.getdetailweek(sdt, selisih);
    		this.days.mod.push({
    			days : weeks.weekday + weeks.weekend, 
    			wd : weeks.weekday, 
    			we : weeks.weekend,
    			owner_price : Number(this.unit_mod_prices[i].owner_price),
    			rent_price : Number(this.unit_mod_prices[i].price),
    			start : sdt, end : edt
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
				if(this.calculate_mod_days)
					this.setModDays();
				return 0;
			}
		}
	}

	describe_days_type (check_in, check_out, unit_mod_prices, days, calculate_mod_days = true) {
		// Setter Data
		this.check_in = check_in;
		this.check_out = check_out;
		this.unit_mod_prices = unit_mod_prices;
		if(days != null)
			this.days = days;
		this.calculate_mod_days = calculate_mod_days

		this.days.mod = []
		this.setDaily(this.check_in, this.diff_date(this.check_in, this.check_out));
		this.days.mn = this.monthly();
		this.days.wk = this.weekly(this.days.mn);
		return this.days;
	}
}

module.exports = DayClassifiers