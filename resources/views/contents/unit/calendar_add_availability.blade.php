<div class="modal-dialog">
    <div class="modal-content" id="add-availability">
        <div class="modal-header bg-info">
            <h4 class="modal-title text-white" >Block Unit Availability</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Started Date</label>
                <div>
                    <date-picker format="YYYY-MM-DD" value-type="format" v-model="started_at"></date-picker>
                </div>   
            </div>
            <div class="form-group">
                <label>Ended Date</label>
                <div>
                    <date-picker format="YYYY-MM-DD" value-type="format" v-model="ended_at"></date-picker>
                </div>
            </div>
            <div class="form-group">
                <label>Note (Optional)</label>
                <textarea class="form-control" v-model="note"></textarea>
            </div>
            <div class="form-group mt-2">
                <p-check class="p-switch p-fill" v-model="maintenance" :value="true" color="primary">Due for maintenance</p-check>
            </div>
        </div>
        <div class="modal-footer">
            <button v-show="!onsubmit" ref="closeModal" type="button" class="btn btn-outline-info" data-dismiss="modal">Cancel</button>
            <button v-show="!onsubmit" type="button" @click="submit()" class="btn btn-info">Save</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#3bafda"></vue-loaders>
        </div>
    </div>
</div>