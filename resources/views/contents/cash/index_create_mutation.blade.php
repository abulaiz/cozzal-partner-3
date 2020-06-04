<div class="modal-dialog">
    <div class="modal-content" id="create-mutation">
        <div class="modal-header bg-primary">
            <h4 class="modal-title text-white">Make balance mutation</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Initial Cash</label>
                <div>
                    <dynamic-select 
                        :options="cashes"
                        option-value="id"
                        option-text="name"
                        placeholder="type to search"
                        v-model="from_cash_id" />                    
                </div>
            </div>
            <div class="form-group">
                <label>Destination Cash</label>
                <div>
                    <dynamic-select 
                        :options="cashes"
                        option-value="id"
                        option-text="name"
                        placeholder="type to search"
                        v-model="to_cash_id" />                    
                </div>
            </div> 
            <div class="form-group">
                <label>Fund</label>
                <div>
                    <cleave class="form-control" v-model="fund" :options="cleaveOption"></cleave>
                </div>
            </div>           
        </div>
        <div class="modal-footer">
            <button v-show="!onsubmit" ref="closeModal" type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
            <button v-show="!onsubmit" type="button" @click="submit()" class="btn btn-primary">Save</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#967ADC"></vue-loaders>
        </div>
    </div>
</div>