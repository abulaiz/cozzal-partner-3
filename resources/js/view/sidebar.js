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
		},
		payment_report : {
			waiting_payment : 0
		},
		owner_payment : {
			accepted_payment : 0
		}
	},
	methods : {
		dismiss(){
			this.need_interuption = false;
			setTimeout(()=>{
				$(".discovery-wrapper").hide();
			}, 200)
		},
		showInterupt(expired_expenditure){
			if(expired_expenditure > 0 && !this.$refs.approval_exp.classList.value.includes("active")){
				$(".discovery-wrapper").show();
				this.need_interuption = true;	
			}			
		},		
		updateExpenditureBadge(data){
			if(!data.notified) return;
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
		updatePaymentReportBadge(data){
			if(!data.notified) return;
			this.payment_report.waiting_payment = data.waiting_payment
		},
		updateOwnerPaymentBadge(data){
			if(!data.notified) return;
			this.owner_payment.accepted_payment = data.accepted_payment
		},
		loadInfo(){
			let e = this;
			axios.get(notification_api)
			.then(function (response) {
				e.showInterupt(response.data.expenditure.expired_expenditure);
				e.updateExpenditureBadge(response.data.expenditure);
				e.updatePaymentReportBadge(response.data.payment_report);
				e.updateOwnerPaymentBadge(response.data.owner_payment);
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