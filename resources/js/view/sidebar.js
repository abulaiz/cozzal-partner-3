require('../bootstrap');
window.Vue = require('vue');

const notification_api = $("#api-notification").text();
$(".sidebar-parser-data").remove();

window._sidebar = new Vue({
	el : "#sidebar",
	data : {
		need_interuption : false,
		expenditure : {
			have_notification : false,
			billing_count : 0,
			non_billing_count : 0
		}
	},
	methods : {
		dismiss(){
			this.need_interuption = false;
			setTimeout(()=>{
				$(".discovery-wrapper").hide();
			}, 200)
		},
		updateBadge(data){
			this.expenditure.billing_count = Number(data.expired_expenditure);
			this.expenditure.non_billing_count = Number(data.need_to_approve);
			this.expenditure.have_notification = this.expenditure.billing_count+this.expenditure.non_billing_count > 0;
			if(document.getElementById('b-tab') !== null){
				document.getElementById('b-tab').innerHTML = this.expenditure.billing_count;
				if(this.expenditure.billing_count > 0)
					$("#b-tab").show();
				else
					$("#b-tab").hide();
			}
			if(document.getElementById('nb-tab') !== null){
				document.getElementById('nb-tab').innerHTML = this.expenditure.non_billing_count;
				if(this.expenditure.non_billing_count > 0)
					$("#nb-tab").show();
				else
					$("#nb-tab").hide();
			}			
		},
		showInterupt(expired_expenditure){
			if(expired_expenditure > 0 && !this.$refs.approval_exp.classList.value.includes("active")){
				$(".discovery-wrapper").show();
				this.need_interuption = true;	
			}			
		},
		loadInfo(){
			let e = this;
			axios.get(notification_api)
			.then(function (response) {
				if(response.data.notified){
					e.showInterupt(response.data.expired_expenditure);
					e.updateBadge(response.data);
				}	
			})			
		}
	},
	mounted : function(){
		this.loadInfo()
	},
	created : function(){
		$(".op-0").css('opacity', '1');
	}
})	