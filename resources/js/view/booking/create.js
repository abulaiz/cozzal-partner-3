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

var _URL = {};
_URL['tenant_index'] = $("#api-tenants-index").text();
_URL['tenant_store'] = $("#api-tenants-store").text();

$(".rm").remove();

var submit_attempt = 0;

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

// var step2 = new Vue({
//     el : "#_step2",
//     data : {
//     },
//     methods : {

//     },
//     watch : {

//     },
//     created : function(){
//     }
// });

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