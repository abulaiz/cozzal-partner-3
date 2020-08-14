<div class="modal-dialog">
    <div class="modal-content" id="pay-modals">
        <div class="modal-header bg-success">
            <h4 class="modal-title" style="color:white">Pay Billing Expenditure</h4>
            <button ref="close" type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">        
            <div class="form-group">
                <label>Source Fund</label>
                <div>
                    <dynamic-select 
                    :options="option.cashes"
                    option-value="id"
                    option-text="name"
                    placeholder="Type to search"
                    v-model="cash" />                    
                </div>
            </div>  
            <div class="form-group">
                <label>Payment Slip</label>
                <upload-image v-model="attachment"></upload-image>
            </div>                                       
        </div>
        <div class="modal-footer insert-section">
            <button v-if="!onsubmit" type="button" class="btn btn-outline-success" data-dismiss="modal">Back</button>
            <button @click="submit" v-if="!onsubmit" type="button" class="btn btn-success">Submit</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#37BC9B"></vue-loaders>

        </div>
    </div>
</div>