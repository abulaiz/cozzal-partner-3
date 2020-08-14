<div class="modal-dialog">
    <div class="modal-content" id="payment-modals">
        <div class="modal-header bg-success">
            <h4 class="modal-title" style="color:white">Transaction Payment</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <transition name="slide">
        <div v-if="!onload" class="modal-body">
            <div class="form-group row hd border-bottom-blue-grey border-bottom-lighten-4">
                <label class="col-sm-5 control-label">Total Amount </label>
                <div class="col-sm-5">
                <p class="form-control-static"><strong>@{{ to_IDR(total_amount) }}</strong></p>
                </div>
            </div>
            <div class="form-group row hd border-bottom-blue-grey border-bottom-lighten-4">
                <label class="col-sm-5 control-label">DP </label>
                <div class="col-sm-5">
                <p class="form-control-static"><strong>@{{ to_IDR(dp) }}</strong></p>
                </div>
            </div>
            <div class="form-group row hd border-bottom-blue-grey border-bottom-lighten-4">
                <label class="col-sm-5 control-label">Deposit </label>
                <div class="col-sm-5">
                <p class="form-control-static"><strong>@{{ to_IDR(deposit) }}</strong></p>
                </div>
            </div>
            <div class="form-group row hd border-bottom-blue-grey border-bottom-lighten-4">
                <label class="col-sm-5 control-label">Payed + Deposit </label>
                <div class="col-sm-5">
                <p class="form-control-static"><strong>@{{ to_IDR( has_pay ) }}</strong></p>
                </div>
            </div>                        
            <div class="form-group row hd border-bottom-blue-grey border-bottom-lighten-4">
                <label class="col-sm-5 control-label">Remaining Payment </label>
                <div class="col-sm-5">
                <p class="form-control-static"><strong>@{{ to_IDR(remaining_payment) }}</strong></p>
                </div>
            </div>
            <div class="form-group row hd border-bottom-blue-grey border-bottom-lighten-4">
                <label class="col-sm-5 control-label">Payment Status</label>
                <div class="col-sm-5">
                <p :class="'form-control-static text-'+(status == 'Unsettled' ? 'danger' : 'success')"><strong>@{{ status }}</strong></p>
                </div>
            </div>   
            <div class="form-group row border-bottom-blue-grey border-bottom-lighten-4" v-if="show_deposit_status">
                <label class="col-sm-5 control-label">Deposite Status</label>
                <div class="col-sm-5">
                <p :class="'form-control-static text-'+(deposit_status == 'Unsettled' ? 'danger' : 'success')"><strong>@{{ deposit_status }}</strong></p>
                </div>
            </div>                                                                         
            <div class="form-group" v-if="can_settlement_deposite || can_make_payment">
                <label>Source Fund</label>
                <div>
                    <dynamic-select 
                    :options="option.cashes"
                    option-value="id"
                    option-text="name"
                    placeholder="Type to search"
                    v-model="input.cash" />                    
                </div>
            </div>                                           
            <div class="form-group" v-if="can_make_payment">
                <label for="name">Payment (IDR)</label>
                <cleave class="form-control" v-model="input.fund" 
                  :options="cleave"></cleave>
                <p v-if="can_settlement_deposite" class="text-info">This field for Make Payment Only</p>
            </div>
            <div class="form-group" v-if="can_make_payment">
                <label>Payment Slip</label>
                <upload-image v-model="input.attachment"></upload-image>
            </div>               
        </div>
        </transition>
        
        <transition name="slide">
        <div v-if="!onload" class="modal-footer insert-section">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
            <button @click="settlement" v-if="can_settlement_deposite" type="button" class="btn btn-primary">Settlement Deposit</button>
            <button @click="pay" v-if="can_make_payment" type="button" class="btn btn-success">Make Payment</button>
        </div>
        </transition>

        <transition name="slide">
        <div v-if="onload" class="modal-body text-center p-5">
            <vue-loaders name="ball-spin-fade-loader" scale="1" color="#37BC9B"></vue-loaders>
        </div>
        </transition>
    </div>
</div>