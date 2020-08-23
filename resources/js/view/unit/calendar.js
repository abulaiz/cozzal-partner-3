require('../../bootstrap');

window.Vue = require('vue');

// Css Loader
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.component('vue-loaders', VueLoaders.component);

// Date Picker
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
Vue.component('date-picker', DatePicker);

// Radio and Checkbox
import PrettyCheck from 'pretty-checkbox-vue/check';
Vue.component('p-check', PrettyCheck);

// Vue currency formater
import Cleave from 'vue-cleave-component';
Vue.component('cleave', Cleave);

// Vue Select
import DynamicSelect from 'vue-dynamic-select'
Vue.component('dynamic-select', DynamicSelect);

const unit_id = $("#parse-unit-id").text();

var _URL = {};

_URL['store_av'] = $("#url-api-availability-store").text();
_URL['update_av'] = $("#url-api-availability-update").text();
_URL['delete_av'] = $("#url-api-availability-delete").text();

_URL['store_pr'] = $("#url-api-price-store").text();
_URL['update_pr'] = $("#url-api-price-update").text();
_URL['delete_pr'] = $("#url-api-price-delete").text();

_URL['calendar'] = $("#url-api-calendar").text();
_URL['units'] = $("#api-units").text();
_URL['site'] = $("#url-calendar").text();

var cleaveOption = {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand'
};

$(".rm").remove();

// Add Event Modal
var availability_add = new Vue({
   el : "#add-availability",
   data : {
      started_at : '',
      ended_at : '',
      note : '',
      maintenance : false,
      onsubmit : false
   },
   methods : {
      submit : function(){
         let e = this;
         e.onsubmit = true;
         axios.post(_URL.store_av , {
            started_at :  e.started_at,
            ended_at :  e.ended_at,
            note : e.note,
            maintenance : e.maintenance,
            unit_id : unit_id
         }).then(function (response) {
            if(response.data.success){
               _leftAlert('Success', 'Data successfuly added !', 'success');
               loadCalendar();
               e.started_at = '';
               e.ended_at = '';
               e.note = '';
               e.maintenance = false;
               e.$refs.closeModal.click();
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
})

// Edit Event Modal
var availability_edit = new Vue({
   el : "#edit-availability",
   data : {
      started_at : '',
      id : '',
      ended_at : '',
      note : '',
      maintenance : false,
      onsubmit : false
   },
   methods : {
      pad : function(value){
         if(value < 10)
            return "0"+value;
         else
            return value.toString();
      },
      getFormatedDate : function(time){
         let t = new Date(time);
         return t.getFullYear()+'-'+ this.pad( t.getMonth()+1 ) +'-'+this.pad( t.getDate() );
      },
      setData : function(id, start, end, note, type){
         this.started_at = this.getFormatedDate(start);
         this.ended_at = this.getFormatedDate(end);
         this.note = note;
         this.maintenance = type == '1' ;
         this.id = id;
      },
      submit : function(){
         let e = this;
         e.onsubmit = true;
         axios.post(_URL.update_av , {
            started_at :  e.started_at,
            ended_at :  e.ended_at,
            note : e.note,
            maintenance : e.maintenance,
            id : e.id
         }).then(function (response) {
            if(response.data.success){
               _leftAlert('Success', 'Data successfuly updated !', 'success');
               loadCalendar();
               e.$refs.closeModal.click();
            } else {
               for(let i in response.data.errors){
                  _leftAlert('Warning !', response.data.errors[i], 'warning', false);
               }
            }
         })
         .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
         .then(function(){ e.onsubmit = false; })         
      },
      remove : function(){
         let e = this;
         e.onsubmit = true;
         axios.post(_URL.delete_av , {
            id : e.id
         }).then(function (response) {
            if(response.data.success){
               _leftAlert('Success', 'Data successfuly deleted !', 'success');
               loadCalendar();
               e.$refs.closeModal.click();
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
})

// Add Mod Price
var price_add = new Vue({
   el : "#add-price",
   data : {
      started_at : '',
      ended_at : '',
      note : '',
      owner_price : '',
      rent_price : '',
      cleaveOption : cleaveOption,
      onsubmit : false
   },
   methods : {
      submit : function(){
         let e = this;
         e.onsubmit = true;
         axios.post(_URL.store_pr , {
            started_at :  e.started_at,
            ended_at :  e.ended_at,
            note : e.note,
            rent_price : e.rent_price,
            owner_price : e.owner_price,
            unit_id : unit_id
         }).then(function (response) {
            if(response.data.success){
               _leftAlert('Success', 'Data successfuly added !', 'success');
               loadCalendar();
               e.started_at = '';
               e.ended_at = '';
               e.note = '';
               e.rent_price = '';
               e.owner_price = '';
               e.$refs.closeModal.click();
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
})

// Edit Mod Price
var price_edit = new Vue({
   el : "#edit-price",
   data : {
      started_at : '',
      ended_at : '',
      note : '',
      owner_price : '',
      rent_price : '',
      id : '',
      cleaveOption : cleaveOption,
      onsubmit : false
   },
   methods : {
      pad : function(value){
         if(value < 10)
            return "0"+value;
         else
            return value.toString();
      },
      getFormatedDate : function(time){
         let t = new Date(time);
         return t.getFullYear()+'-'+ this.pad( t.getMonth()+1 ) +'-'+this.pad( t.getDate() );
      },
      setData : function(id, start, end, note, price, owner_price){
         this.started_at = this.getFormatedDate(start);
         this.ended_at = this.getFormatedDate(end);
         this.note = note;
         this.owner_price = owner_price;
         this.rent_price = price;
         this.id = id;
      }, 
      remove : function(){
         let e = this;
         e.onsubmit = true;
         axios.post(_URL.delete_pr , {
            id : e.id
         }).then(function (response) {
            if(response.data.success){
               _leftAlert('Success', 'Data successfuly deleted !', 'success');
               loadCalendar();
               e.$refs.closeModal.click();
            } else {
               for(let i in response.data.errors){
                  _leftAlert('Warning !', response.data.errors[i], 'warning', false);
               }
            }
         })
         .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
         .then(function(){ e.onsubmit = false; })     
      },           
      submit : function(){
         let e = this;
         e.onsubmit = true;
         axios.post(_URL.update_pr , {
            started_at :  e.started_at,
            ended_at :  e.ended_at,
            note : e.note,
            rent_price : e.rent_price,
            owner_price : e.owner_price,
            id : e.id
         }).then(function (response) {
            if(response.data.success){
               _leftAlert('Success', 'Data successfuly updated !', 'success');
               loadCalendar();
               e.$refs.closeModal.click();
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
})

function loadCalendar(){
   axios.get(_URL.calendar)
   .then(function (response) {
      $("#unit-calendar").css('opacity', '0');
      $("#waiting-calendar").show();  

      $("#unit-calendar").show();
      $('#unit-calendar').fullCalendar('destroy');
      $('#unit-calendar').fullCalendar({
         schedulerLicenseKey: '0291244311-fcs-1544872991',
         header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month, agendaWeek, list'
         },
         height: 600,
         editable: false,
         eventLimit: true,
         eventSources: [
            {
               // Maintenance
               events: response.data.mn,
               color: 'orange',    
               textColor: 'brown'
            },
            {
               // Blocked By Admin
               events: response.data.bba,
               color: '#134dfb',    
               textColor: 'white'
            },
            {
               // Blocked By Owner
               events: response.data.bbo,
               color: '#fb13ab',    
               textColor: 'white'
            },
            {
               // Reservation
               events: response.data.resv,
               color: '#e1415f',    
               textColor: 'white'
            },            
            {
               // Mod Price
               events: response.data.mp,
               color: '#d041e1',    
               textColor: 'white'
            },                                    
         ],
         eventRender: function(eventObj, $el) {
          let content = '';
          if(eventObj.type == '5')
            content = eventObj.description == '' || eventObj.description == null ? '-' : (function(){
               return "Owner Price : "+eventObj.owner_price+", Rent Price : "+eventObj.price;
            })();
          else
            content = eventObj.description == '' || eventObj.description == null ? '-' : eventObj.description;
          
          $el.popover({
            title: eventObj.title,
            content: content,
            trigger: 'hover',
            placement: 'top',
            container: 'body'
          });
         },  
         eventClick: function(event) {
            if(!event.editable)
               return;
            if(Number(event.type) > 3){
               if(event.type == '4') return;
               price_edit.setData(
                  event.id, event.start, event.end, 
                  event.description, event.price, event.owner_price
               );
               $("#modal4").modal();               
            } else {
               availability_edit.setData(
                  event.id, event.start, event.end, 
                  event.description, event.type
               );
               $("#modal2").modal();
            }
            
         }
      });

      $("#unit-calendar").css('opacity', '1');
      $("#waiting-calendar").hide();       
   })
   .catch(function (error) {
   });  
}

var app = new Vue({
   el : "#app",
   data : {
      units : [],
      unit : null
   },
   created : function(){
      axios.get(_URL.units)
      .then((response) => {
         this.units = response.data.data;
         for(let i in this.units){
            if(this.units[i].id == unit_id){
               this.unit = this.units[i];
            }
         }
      })
   },
   watch : {
      unit : function(newVal, oldVal){
         if(oldVal == null || newVal == null) return ;
         window.location = _URL.site.replace('/0', '/' + newVal.id)
      }
   }
});

loadCalendar();