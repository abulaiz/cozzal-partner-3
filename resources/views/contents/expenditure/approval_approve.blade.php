<div class="modal-dialog">
    <div class="modal-content" id="approve-modals">
        <div class="modal-header bg-info">
            <h4 class="modal-title" style="color:white">Approve Expenditure</h4>
            <button ref="close" type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">  
            <div class="form-group mb-2">
                <p-check class="p-switch p-fill" v-model="approve_as_billing" :value="true" color="primary">
                    <span style="margin-left: 10px; font-weight: bold;">Approve expenditure as Billing</span>
                </p-check>
            </div>              
            <div class="form-group" v-if="!approve_as_billing">
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
            <div class="form-group" v-if="approve_as_billing">
                <label>Due at </label>
                <div>
                    <date-picker format="DD-MM-YYYY" value-type="YYYY-MM-DD" v-model="due_date" :disabled-date="notBeforeToday"></date-picker>
                </div>
            </div>                                   
        </div>
        <div class="modal-footer insert-section">
            <button v-if="!onsubmit" type="button" class="btn btn-outline-info" data-dismiss="modal">Back</button>
            <button @click="submit" v-if="!onsubmit" type="button" class="btn btn-info">Approve</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#3BAFDA"></vue-loaders>

        </div>
    </div>
</div>