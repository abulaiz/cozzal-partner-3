<div class="modal-dialog">
    <div class="modal-content" id="add-price">
        <div class="modal-header bg-info">
            <h4 class="modal-title text-white" >Price Override</h4>
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
                <label>Owner Price (IDR)</label>
                <div>
                    <cleave class="form-control" v-model="owner_price" :options="cleaveOption"></cleave>
                </div>
            </div>
            <div class="form-group">
                <label>Rent Price (IDR)</label>
                <div>
                    <cleave class="form-control" v-model="rent_price" :options="cleaveOption"></cleave>
                </div>
            </div>            
            <div class="form-group">
                <label>Note (Optional)</label>
                <textarea class="form-control" v-model="note"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button v-show="!onsubmit" ref="closeModal" type="button" class="btn btn-outline-info" data-dismiss="modal">Cancel</button>
            <button v-show="!onsubmit" type="button" @click="submit()" class="btn btn-info">Save</button>
            <vue-loaders  v-show="onsubmit" class="mr-1" name="line-scale-pulse-out-rapid" color="#3bafda"></vue-loaders>
        </div>
    </div>
</div>