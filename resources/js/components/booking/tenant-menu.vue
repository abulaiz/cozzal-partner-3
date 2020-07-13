<template>
    <div class="row">
        <div class="col-md-12" v-if="state == 1">
            <div class="row">
                <input class="form-control col-md-8 mb-1" v-model="filter" placeholder="Search tenant here" type="text">
                <ul class="list-group col-md-12" style="max-height: 300px; overflow: auto;">
                    <li class="list-group-item" v-if="!onload" v-for="item in tenants">
                        <div class="pull-right">
                            <button @click="setSelected(item)" type="button" class="btn btn-outline-info btn-sm">Select</button>
                        </div>                    
                        <h6 class="my-0 font-weight-bold">{{ item.name }} - {{ item.email }}</h6>    
                        <small>{{ item.gender }} :: {{ item.phone }} :: {{ item.address }}</small>                
                    </li>
                    <li class="list-group-item text-center" v-if="tenants.length == 0 && !onload">
                        Data is empty
                    </li>
                    <li class="list-group-item text-center" v-if="onload">
                        <vue-loaders name="line-scale-pulse-out-rapid" color="#967ADC"></vue-loaders>
                    </li>
                </ul>                                                                      
            </div> 
            <div class="row mt-1">
                <p>Can'nt find tenant ? <a @click="state = 2" href="javascript:void(0)">click</a> to create new one</p>
            </div>
        </div>
        <div class="col-md-12" v-if="state == 2">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" v-model="add.name" placeholder="Type name here ...">
                </div>
                <div class="col-md-6 form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" v-model="add.email" placeholder="Type email here ...">
                </div>
                <div class="col-md-6 form-group">
                    <label>Phone Number</label>
                    <vue-phone-number-input default-country-code="ID" v-model="add.phone"></vue-phone-number-input>
                </div>   
                <div class="col-md-6 form-group">
                    <label>Gender</label>
                    <select class="form-control" v-model="add.gender">
                        <option selected disabled value="">-- Select One --</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>  
                <div class="col-md-6 form-group">
                    <label>Address</label>
                    <textarea class="form-control" rows="5" v-model="add.address" placeholder="Short Address"></textarea>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button v-show="!onsubmit" @click="state = 1" type="button" class="btn btn-outline-success">Back to List</button>
                        <button v-show="!onsubmit" @click="submit" type="button" class="btn btn-success">Save Tenant</button>
                        <vue-loaders v-show="onsubmit" name="line-scale-pulse-out-rapid" color="#967ADC"></vue-loaders>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 p-3" v-if="state == 3">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>You have select 
                        <span class="font-weight-bold">{{ selected.name }}</span> from <span class="font-weight-bold">{{ selected.address }}</span></p>
                </div>
                <div class="col-md-12 text-center">
                    <button @click="reset" type="button" class="btn btn-warning">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                state : 1,
                tenants : [],
                datatable : {
                    // Standar Params
                    start : 0, length : 10,
                    search : {
                        value : ''
                    },
                    columns : [
                        {name : 'name', searchable : true},
                        {name : 'email', searchable : true},
                        {name : 'phone', searchable : true},
                        {name : 'gender', searchable : true},
                        {name : 'address', searchable : true},
                    ],
                    order : [],          
                },
                onload : false ,
                onsubmit : false,
                filter : '',
                add : {
                    name : '',
                    email : '',
                    phone : '',
                    gender : '',
                    address : ''
                },
                selected : {
                    name : '',
                    address : ''
                }              
            }
        },
        props : [
            'url'
        ],
        methods : {
            setSelected : function(data) {
                this.selected.name = data.name;
                this.selected.address = data.address;
                this.state = 3;
                this.$emit('select', data.id)
            },
            reset : function(){
                this.$emit('unselect')
                this.load_tenant_data()
                this.state = 1;
            },
            load_tenant_data : function(){
                let e = this;
                this.onload = true;
                axios.get(this.url.tenant_index + "?" + jQuery.param(this.datatable))
                .then(function (response) {
                    e.tenants = response.data.data;
                    e.onload = false;
                });                
            },
            submit : function(){
                let e = this;
                e.onsubmit = true;
                axios.post(this.url.tenant_store , this.add)
                .then(function (response) {
                    if(response.data.success){
                        _leftAlert('Success', 'Data successfuly added !', 'success');
                        e.add.name = '';
                        e.add.email = '';
                        e.add.phone = '';
                        e.add.gender = '';
                        e.add.address = '';
                        e.setSelected(response.data.data);
                    } else {
                        for(let i in response.data.errors){
                            _leftAlert('Warning !', response.data.errors[i], 'warning', false);
                        }
                    }
                })
                .catch(function(){ _leftAlert('Error', 'Something wrong, try again', 'error'); })
                .then(function(){ e.onsubmit = false; })
            }
        },
        watch : {
            filter : function(val){
                this.datatable.search.value = val;
                this.debounceTenantData();
            }
        },
        created : function(){
            this.debounceTenantData = _.debounce(this.load_tenant_data, 500);
            this.debounceTenantData();
        }
    }
</script>