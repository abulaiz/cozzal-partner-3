<div class="modal-dialog">
    <div class="modal-content" id="settlement-modals">
        <div class="modal-header bg-success">
            <h4 class="modal-title" style="color:white">Settlemet DP</h4>
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
                    v-model="input.cash" />                    
                </div>
            </div>                        
            <div class="form-group">
                <label for="name">Payment (IDR)</label>
                <cleave class="form-control" v-model="input.fund" 
                  :options="cleave"></cleave>
            </div>
        </div>
        <div class="modal-footer insert-section">
            <button v-if="!onsubmit" type="button" class="btn btn-outline-success" data-dismiss="modal">Back</button>
            <button @click="submit" v-if="!onsubmit" type="button" class="btn btn-success">Submit</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#37BC9B"></vue-loaders>

        </div>
    </div>
</div>