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

// Date Picker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.component('date-picker', DatePicker);

var _URL = {};
_URL['cash'] = $("#api-url-cashes").text();
_URL['apartment'] = $("#api-url-apartments").text();
_URL['unit'] = $("#api-url-units").text();
_URL['store'] = $("#api-url-expenditures-store").text();

$(".rm").remove();

var submit_attempt = 0;

function _catch_with_toastr(message){
    _leftAlert('Sorry !', message, 'error', false);
    return false;
}

function step1_validation(){
    let data = step1.$data;
    if(data.type == null) 
        return _catch_with_toastr("Expenditure type is required");
    if(data.necessary == null) 
        return _catch_with_toastr("Necessary is required");

    step2.setData(data.type, data.necessary);

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
        price : step2.$data.price,
        qty : step2.$data.qty,
        description : step2.$data.description,
        cash_id : step2.$data.cash == null ? null : step2.$data.cash.id,
        due_at : step2.$data.due_at,
        unit_id : step2.$data.unit == null ? null : step2.$data.unit.id,
        expenditure_type : step2.$data.type.id.toString(),
        expenditure_necessary : step2.$data.necessary.id.toString()
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
    data : {
        option : {
            type : [
                {id : 1, name : 'Direct'},
                {id : 2, name : 'Approval (Billing)'},
                {id : 3, name : 'Approval (Non Billing)'},
            ],
            necessary : [
                {id : 1, name : 'General'},
                {id : 2, name : 'Unit'},
            ]
        },
        type : null,
        necessary : null
    }
});

var step2 = new Vue({
    el : "#_step2",
    data : {
        option : {
            units : [],
            apartments : [],
            cashes : []
        },
        price : null,
        due_at : null,
        description : null,
        qty : null,
        unit : null,
        apartment : null,
        cash : null,
        type : '',
        necessary : '',
        unit_loaded : false,        
        cleaveOption : {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'            
        }
    },
    methods : {
        notBeforeToday(date) {
          return date < new Date();
        },
        setData(type, necessary){
            this.type = type;
            this.necessary = necessary;
        }
    },
    watch : {
        apartment : function(data){
            if(data == null) return;
            let e = this;
            this.unit = null;
            this.unit_loaded = false;
            axios.get(_URL.unit+'?apartment_id='+data.id).then(function (response) { 
                e.option.units = response.data 
                e.unit_loaded = true;
            })
        }
    },
    created : function(){
        let e = this;
        axios.get(_URL.cash).then(function (response) { e.option.cashes = response.data })
        axios.get(_URL.apartment).then(function (response) { e.option.apartments = response.data.data })
    }
});

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