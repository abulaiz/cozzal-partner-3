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
const PriceHelper = require('./price_helper');

var _URL = {};
_URL['tenant_index'] = $("#api-tenants-index").text();
_URL['tenant_store'] = $("#api-tenants-store").text();
_URL['apartment'] = $("#api-apartments").text();
_URL['unit'] = $("#api-units").text();
_URL['mod_prices'] = $("#api-mod-prices").text();

$(".rm").remove();

var submit_attempt = 0;
const Helper = new PriceHelper();
Helper.setURL(_URL);

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'error', false);
    return false;
}

function step1_validation(){
    let data = step1.$data;
    if(data.tenant_id == null) 
        return _catch_with_toastr("Please select tenant first");

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
    axios.post(_URL.store , {

    }).then(function (response) {
        let res = response.data;
        if(res.success){
            window._setMessage(res.direct_path, res.message, 'success');
            window.location = res.direct_route; 
        } else {
            for(let i in res.errors){
                _leftAlert('Warning !', res.errors[i], 'warning', false);
            }
        }
    })
    .catch(function(){ form_onsubmit(false); _leftAlert('Error', 'Something wrong, try again', 'error'); })  
    .then(function(){ form_onsubmit(false); })
}

$(".number-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        if( currentIndex > newIndex ) return true;
        if(currentIndex == 0 ){
            return step1_validation();
        }
        return true;
    },       
    onFinishing: function (event, currentIndex)
    {
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
        check_in : '',
        check_out : '',
        skip_watch_days : false,
        skip_watch_check_out : false,
        days : null,
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
            if(this.days == 1) {this.incrementCheckOut(1); reset();}
            this.days = 1;
        },
        check_out : function(val){
            Helper.setDate(this.check_in, val)

            if(this.skip_watch_check_out) {this.skip_watch_check_out = false; return;};
            
            if(this.check_in == '') { this.check_out = ''; _leftAlert('Error !', 'Please set check in please', 'error', false);  return; }
            
            let e = this;
            this.skip_watch_days = true;
            this.days = Helper.diff_date(this.check_in, val)
        },
        days : function(val, oldVal){
            reset();
            if(this.skip_watch_days){
                this.skip_watch_days = false;
                return;
            }
            if(val <= 0) {  
                this.skip_watch_days = true; 
                this.days = oldVal; 
                _leftAlert('Error !', 'Date not valid', 'error', false); 
            }
            this.incrementCheckOut(this.days)
      }
    },
    created : function(){

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
        guest_count : 1,
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
                    if(count > 0)
                        _leftAlert('Info', `System detected ${count} mod price`, 'info', true); 
                })
        },
        guest_count : function(value){ Helper.setGuestCount(value) }
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
            amount_bill : 0        
        },
        cleave : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'       
        },
        prevent_cleave_changed : false
    },
    methods : {
        cleaveDebounce1 : function(){
            Helper.generatePrice(false, this.price, true)
        },
        cleaveDebounce2 : function(){
            Helper.generatePrice(false, this.price)
        },        
    },
    created : function(){
        this.debounceCleave1 = _.debounce(this.cleaveDebounce1, 200)
        this.debounceCleave2 = _.debounce(this.cleaveDebounce2, 200)
        Helper.addEventListener('showPricingField', (params) => { this.show = params })
        Helper.addEventListener('updatePricingField', (params) => { 
            this.prevent_cleave_changed = true;
            this.price = params; 
            setTimeout(() => {
                this.prevent_cleave_changed = false;
            }, 100)

            console.log(params) 
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