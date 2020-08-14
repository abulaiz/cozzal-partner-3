<div class="modal-dialog">
    <div class="modal-content" id="detail-mutation">
        <div class="modal-header bg-success">
            <h4 class="modal-title text-white">Cash Mutation Detail</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row" v-if="data != null">
                <div class="col-md-12">
                    <table>
                        <tr>
                            <td>Mutation date</td>
                            <td>:</td>
                            <td>@{{ data.mutation_date }}</td>                            
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td>:</td>
                            <td>@{{ data.cash }}</td>                            
                        </tr>
                        <tr>
                            <td>Mutation Fund</td>
                            <td>:</td>
                            <td>@{{ data.fund }}</td>                            
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>:</td>
                            <td v-html="data.type"></td>                            
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>:</td>
                            <td>@{{ data.description }}</td>                            
                        </tr>    
                        <tr>
                            <td>Executor</td>
                            <td>:</td>
                            <td>@{{ data.executor }}</td>                            
                        </tr>                                
                    </table>
                </div>
                <div class="col-md-12">
                    <img :src="data.attachment" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<style type="text/css">
    #detail-mutation td{
        padding: 10px;
    }
</style>