require('../../bootstrap');
window._ = require('lodash');
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

// Date Picker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.component('date-picker', DatePicker);

// Phone number formatter
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
Vue.component('vue-phone-number-input', VuePhoneNumberInput);

Vue.component('tenant-menu', require('../../components/booking/tenant-menu.vue').default);
Vue.component('upload-image', require('../../components/UploadImage.vue').default);
const PriceHelper = require('./price_helper');

var _URL = {};
_URL['tenant_index'] = $("#api-tenants-index").text();
_URL['tenant_store'] = $("#api-tenants-store").text();
_URL['apartment'] = $("#api-apartments").text();
_URL['booking_via'] = $("#api-booking_vias").text();
_URL['cash'] = $("#api-cashes").text();
_URL['unit'] = $("#api-units").text();
_URL['mod_prices'] = $("#api-mod-prices").text();
_URL['store'] = $("#api-booking-store").text();

$(".rm").remove();

var submit_attempt = 0;
const Helper = new PriceHelper();
Helper.setURL(_URL);
var unvalidate = {};

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'warning', false);
    return false;
}

function step1_validation(data = step1.$data){
    if(data.tenant_id == null)  return _catch_with_toastr("Please select tenant first");
    return true;
}

function step2_validation(data = step2.$data){
   if(data.check_in == null)  return _catch_with_toastr("Check in required");
   if(data.check_out == null)  return _catch_with_toastr("Check out required");
   return true;
}

function step3_validation(data = step3.$data){
    if(data.guest_count <= 0) return _catch_with_toastr("Please insert valid guest count");
    if(data.unit == null || data.unit == {}) return _catch_with_toastr("Unit required");
    return true;
}

function step5_validation(data = step5.$data){ 
    if(data.payment_type.id != 3 && data.attachment == null)
        return _catch_with_toastr("Please add payment slip image")
    if(data.booking_via == null || data.booking_via == {}) 
        return _catch_with_toastr("Booking Via required");
    if( (data.cash == null || data.cash == {}) && data.payment_type.id != 3 )
        return _catch_with_toastr("DP Via required");
    if(Number(step4.$data.price.deposite) > 0 && Number(data.dp) < Number(step4.$data.price.deposite))
        return _catch_with_toastr("DP can'nt least from deposite");  
    if(Number(data.dp) > Number(step4.$data.price.amount_bill))
        return _catch_with_toastr("System doesn't have refund system"); 
    return true;
}

function form_onsubmit(state = true){
    submit_attempt++;
    $(".number-tab-steps").each(function(){
        if(submit_attempt == 1){
            let _loader = document.getElementById("loader");
            $(this).append(_loader);                        
        }        
        $(this).find(".actions").each(function(){
            if(state){
                $(this).hide();
                loader.show();
            }
            else{
                $(this).show();
                loader.hide();
            }
        });
    });
}

function submit(){
    form_onsubmit();
    let form_datas = {
        amount_bill : step4.$data.price.amount_bill,
        normal_amount_bill : step4.$data.price.normal_amount_bill,
        dp : step5.$data.dp,
        deposite : step4.$data.price.deposite,
        tenant_id : step1.$data.tenant_id,
        unit_id : step3.$data.unit.id,
        booking_via_id : step5.$data.booking_via.id,
        check_in : step2.$data.check_in,
        check_out : step2.$data.check_out,
        guest : step3.$data.guest_count,
        note : step5.$data.note,
        owner_weekday_price : step4.$data.price.owner_weekday_price,
        owner_weekend_price : step4.$data.price.owner_weekend_price,
        owner_weeky_price : step4.$data.price.owner_weeky_price,
        owner_monthly_price : step4.$data.price.owner_monthly_price,
        owner_price_total : step4.$data.price.owner_price_total,
        rent_weekday_price : step4.$data.price.rent_weekday_price,
        rent_weekend_price : step4.$data.price.rent_weekend_price,
        rent_weekly_price : step4.$data.price.rent_weekly_price,
        rent_monthly_price : step4.$data.price.rent_monthly_price,
        rent_price_total : step4.$data.price.rent_price_total,
        mod_prices : JSON.stringify(step4.$data.mod_prices),
        days : JSON.stringify(step2.$data.days),
        charge : step4.$data.price.charge,
        cash_id : step5.$data.cash == null ? null : step5.$data.cash.id,
        attachment : step5.$data.attachment
    };
    let data = new FormData();
    for(let i in form_datas)
        data.append(i, form_datas[i])
    let config = {header : { 'Content-Type' : 'multipart/form-data' }}
    axios.post(_URL.store, data, config ).then(function (response) {
        let res = response.data;
        if(res.success){
            window._setMessage(res.direct_path, res.message, 'success');
            window.location = res.direct_route; 
        } else {
            for(let i in res.errors){
                _leftAlert('Warning !', res.errors[i], 'warning', false);
            }
            form_onsubmit(false);
        }
    })
    .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
}

$(".number-tab-steps").steps({
    headerTag: "h6", bodyTag: "fieldset",
    transitionEffect: "fade", titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        if( currentIndex < newIndex ) {
            if(unvalidate[currentIndex]) unvalidate[currentIndex] = false;
            if(currentIndex == 0 ) return step1_validation();
            if(currentIndex == 1 ) return step2_validation();
            if(currentIndex == 2 ) return step3_validation();
        } else {
            unvalidate[currentIndex] = true;
        }
        return true;
    },       
    onFinishing: function (event, currentIndex)
    {
        let idx = Object.keys(unvalidate);
        for(let i in idx){
            if(!unvalidate[idx[i]]) continue;
            let ret = true;
            if(idx[i] == 0 ) ret = ret && step1_validation();
            if(idx[i] == 1 ) ret = ret && step2_validation();
            if(idx[i] == 2 ) ret = ret && step3_validation();
            if(!ret){
                $("#steps-uid-0-t-"+idx[i]).click();
                return false;
            }           
        }
        if(step5_validation())
            submit();
        return true;
    }
});

var step1 = new Vue({
    el : "#_step1",
    data : { url : _URL, tenant_id : null },
    methods : {
        setTenant : function(tenant_id){
            this.tenant_id = tenant_id;
        },
        resetTenant : function(){
            this.tenant_id = null;
        }
    }
});

var step2 = new Vue({
    el : "#_step2",
    data : {
        check_in : null,
        check_out : null,
        skip_watch_count_day : false,
        skip_watch_check_out : false,
        count_day : null,
        days : {},
        lang : {
          formatLocale: {
            firstDayOfWeek: 1,
            weekdaysMin: ['Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'St'],
          }            
        }
    },
    methods : {
        pad(num){
            return num.toString().length < 2 ? ("0" + num) : num;
        },
        notBeforeCheckIn(date) {
          return date < new Date(this.check_in);
        },
        strDate(date){
            return date.getFullYear() + '-'
                 + this.pad( date.getMonth()+1 ) + '-'
                 + this.pad( date.getDate() )
        },
        incrementCheckOut(inc){
            const date = new Date(  (new Date(this.check_in).getTime()) + (86400000*inc)  )
            this.skip_watch_check_out = true;
            this.check_out = this.strDate(date)              
        }
    },
    watch : {
        check_in : function(val){
            if(this.count_day == 1) {this.incrementCheckOut(1); reset();}
            this.count_day = 1;
        },
        check_out : function(val){
            if(val == null) return;

            Helper.setDate(this.check_in, val)

            if(this.skip_watch_check_out) {this.skip_watch_check_out = false; return;};
            
            if(this.check_in == null) { this.check_out = null; _leftAlert('Error !', 'Please set check in first', 'error', false);  return; }
            
            let e = this;
            this.skip_watch_count_day = true;
            this.count_day = Helper.diff_date(this.check_in, val)
        },
        count_day : function(val, oldVal){
            reset();
            if(this.skip_watch_count_day){
                this.skip_watch_count_day = false;
                return;
            }
            if(val <= 0) {  
                this.skip_watch_count_day = true; 
                this.count_day = oldVal; 
                _leftAlert('Error !', 'Date not valid', 'error', false); 
            }
            this.incrementCheckOut(this.count_day)
      }
    },
    created : function(){
        Helper.addEventListener('updateDays', (params) => { 
            this.days = params; 
            step4.setModPrices(this.days.mod)
            step4.setDateInfo(params)
        })
    }
});

var step3 = new Vue({
    el : "#_step3",
    data : {
        option : {
            units : [],
            apartments : []
        },        
        unit : null,
        apartment : null,
        guest_count : 0,
        unit_loaded : false
    },
    methods : {
        resetApartment(){
            this.apartment = null;
            this.unit = null;
            this.option.units = [];
        }
    },
    watch : {
        apartment : function(data){
            if(data == null) return;
            let e = this;
            this.unit = null;
            this.unit_loaded = false;
            axios.post(_URL.unit, {
                apartment_id : data.id,
                check_in : step2.$data.check_in,
                check_out : step2.$data.check_out
            }).then(function (response) { 
                e.option.units = response.data 
                e.unit_loaded = true;
            })
        },
        unit : function(data){
            if(data != null)
                Helper.setUnit(data, (count) => {
                    // if has mod prices
                })
        },
        guest_count : function(value){ 
            Helper.setGuestCount(value, (showCharge)=>{
                step4.setChargeVisibility(showCharge)
            }) 
        }
    },    
    created : function(){
        let e = this;
        axios.get(_URL.apartment).then(function (response) { e.option.apartments = response.data.data })
    }
})

var step4 = new Vue({
    el : "#_step4",
    data : {
        show : {
            owner_weekday_price : false,
            owner_weekend_price : false,
            owner_weekly_price : false,
            owner_monthly_price : false,
            rent_weekday_price : false,
            rent_weekend_price : false,
            rent_weekly_price : false,
            rent_monthly : false,
            charge : false,
            deposite : false
        }, 
        price : {
            mod_prices : [],
            owner_weekday_price : 0,
            owner_weekend_price : 0,
            owner_weekly_price : 0,
            owner_monthly_price : 0,
            rent_weekday_price : 0,
            rent_weekend_price : 0,
            rent_weekly_price : 0,
            rent_monthly : 0,
            charge : 0,
            deposite : 0,
            rent_price_total : 0,
            owner_price_total : 0,
            amount_bill : 0,
            normal_amount_bill : 0  // legacy amount bill (not edited by user)     
        },
        mod_prices : [],
        cleave : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'       
        },
        prevent_cleave_changed : false,
        date_info : null
    },
    methods : {
        cleaveDebounce1: function(){
            Helper.generatePrice(false, this.price, true)
        },
        cleaveDebounce2: function(){
            Helper.generatePrice(false, this.price)
        }, 
        setModPrices: function(data){ this.mod_prices = data },
        modPriceKeyUp: function(){ this.debounceCleave1() },
        setChargeVisibility: function(showCharge){ this.show.charge = showCharge } ,
        setDateInfo: function(days){ 
            let description = "";
            let day_type = { wd: 'Weekday', we: 'Weekend', wk: 'Weekly', mn: 'Monthly' }
            for(let i in day_type){
                if( days[i] != 0 ) 
                    description += `, ${days[i]} ${day_type[i]}`
            }
            let mod_days_count = 0;
            for(let i in days.mod)
                mod_days_count += days.mod[i].days;

            if(mod_days_count > 0)
                description += `, ${mod_days_count} Overrided Day`
            
            this.date_info = description.substr(1)
        }    
    },
    mounted: function(){
        this.debounceCleave1 = _.debounce(this.cleaveDebounce1, 200)
        this.debounceCleave2 = _.debounce(this.cleaveDebounce2, 200)
        Helper.addEventListener('showPricingField', (params) => { this.show = params })
        Helper.addEventListener('updatePricingField', (params) => { 
            this.prevent_cleave_changed = true;
            this.price = params; 
            setTimeout(() => {
                this.prevent_cleave_changed = false;
            }, 100)
        })

        this.$watch(e => [
            e.price.rent_weekday_price, e.price.rent_weekend_price,
            e.price.rent_weekly_price, e.price.rent_monthly,
            e.price.owner_weekday_price, e.price.owner_weekend_price,
            e.price.owner_weekly_price, e.price.owner_monthly            
        ], () => {
            if(this.prevent_cleave_changed) return;
            this.cleaveDebounce1()
        }); 

        this.$watch(e => [
            e.price.deposite, e.price.charge,           
            e.price.rent_price_total           
        ], () => {
            if(this.prevent_cleave_changed) return;
            this.cleaveDebounce2()
        });

        $(".tooltipp" ).tooltip({
            content:function(){
                return this.getAttribute("title");
            }           
        });                         
    }
})

var step5 = new Vue({
    el : "#_step5",
    data : {
        option : {
            booking_vias : [],
            cashes : [],
            payment_types : [
                {id: 1, name: 'Full Payment'},
                {id: 2, name: 'Custom Payment'},
                {id: 3, name: 'Without Payment'}
            ]
        },
        booking_via : null,
        cash : null,
        payment_type : null,
        dp : 0,
        note : '',
        attachment : null,
        cleave : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'       
        }        
    },
    created : function(){
        axios.get(_URL.cash).then( (response) => { this.option.cashes = response.data })
        axios.get(_URL.booking_via).then( (response) => { this.option.booking_vias = response.data.data })
        this.payment_type = {id: 2, name: 'Custom Payment'}
    },
    watch : {
        payment_type : function(val){
            if(val == null)
                this.payment_type = {id: 2, name: 'Custom Payment'}

            if(val.id == 1) 
                this.dp = step4.$data.price.amount_bill;
            else if(val.id == 3)
                this.dp = 0
        }
    }
})

function reset(){
    step3.resetApartment();
}

var loader = new Vue({ 
    el : "#loader", 
    data : {onsubmit : false},
    methods : {
        show : function(){
            this.onsubmit = true;
        },
        hide : function(){
            this.onsubmit = false;
        }
    }
})

$("#content").css('opacity', '1');