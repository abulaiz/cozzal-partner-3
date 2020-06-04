<div class="modal-dialog">
    <div class="modal-content" id="add-balance">
        <div class="modal-header bg-info">
            <h4 class="modal-title text-white">Add Balance - @{{ cash_name }}</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Fund</label>
                <div>
                    <cleave class="form-control" v-model="fund" :options="cleaveOption"></cleave>    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button v-show="!onsubmit" ref="closeModal" type="button" class="btn btn-outline-info" data-dismiss="modal">Cancel</button>
            <button v-show="!onsubmit" type="button" @click="submit()" class="btn btn-info">Save</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#3BAFDA"></vue-loaders>
        </div>
    </div>
</div>