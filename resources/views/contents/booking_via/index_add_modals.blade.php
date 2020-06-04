<div class="modal-dialog">
    <div class="modal-content" id="add-modal">
        <div class="modal-header bg-success">
            <h4 class="modal-title text-white" >Add Data</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="Name" v-model="name"/>
            </div>
        </div>
        <div class="modal-footer">
            <button v-show="!onsubmit" ref="closeModal" type="button" class="btn btn-outline-success" data-dismiss="modal">Cancel</button>
            <button v-show="!onsubmit" type="button" @click="submit()" class="btn btn-success">Save</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#37bc9b"></vue-loaders>
        </div>
    </div>
</div>