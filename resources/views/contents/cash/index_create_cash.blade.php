<div class="modal-dialog">
    <div class="modal-content" id="create-cash">
        <div class="modal-header bg-success">
            <h4 class="modal-title text-white">Create new cash</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Cash Name</label>
                <input type="text" class="form-control" v-model="name"/>
            </div>
            <div class="form-group">
                <label>Initial Balance</label>
                <div>
                    <cleave class="form-control" v-model="balance" :options="cleaveOption"></cleave>    
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button v-show="!onsubmit" ref="closeModal" type="button" class="btn btn-outline-success" data-dismiss="modal">Cancel</button>
            <button v-show="!onsubmit" type="button" @click="submit()" class="btn btn-success">Save</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#37bc9b"></vue-loaders>
        </div>
    </div>
</div>