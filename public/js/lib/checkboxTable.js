    // this class require iCheck js and css file before 

    window.checkboxTable = function(className, tagName = null){
        $('.'+ className ).iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_flat-blue'
        });

        this.is_implement_for_table = true
        this.tag = tagName
        this.labelElement = null
        this.value = []
        this.onUpdate = function(){}

        this.getTrInstance = function(e){
            if(e.tagName == "TR")
                return e;
            else
                return this.getTrInstance(e.parentNode);
        }

        this.update = function (add, value){
            if(add)
                this.value.push(value);
            else {
                for(let i = 0; i< this.value.length; i++){
                    if(this.value[i] == value){
                        this.value.splice(i, 1);
                    }
                }
            }
            if(this.labelElement !== null){
                if(this.value.length > 0){
                    $(this.labelElement).text(this.value.length+" item selected");
                } else {
                    $(this.labelElement).text("");
                }
            }
            this.onUpdate();
        }

        this.clear = function(){
            this.value = [];
            this.onUpdate();   
        }

        this.setChildClass = function(className){
            var inst = this;
            this.childE = $("."+className);
            $(this.childE).on('ifChecked', function(event){
                if(inst.is_implement_for_table){
                    var a = inst.getTrInstance(this);
                    $(a).css({'background-color' : '#d0e6f5'});
                }
                inst.update(true, this.value);
            });
            $(this.childE).on('ifUnchecked', function(event){
                if(inst.is_implement_for_table){
                    var a = inst.getTrInstance(this);
                    $(a).css({'background-color' : 'white'});
                }
                inst.update(false, this.value);
            });              
        }

        this.appendChildClass = function(className){
            $('.'+ className ).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_flat-blue'
            });   
            this.setChildClass(className);   
            this.updateParentEvent();
        }

        this.updateParentEvent = function(){
            var chd = this.childE;

            $(this.parentE).on('ifChecked', function(event){
                var x = $(chd);
                for(let i=0; i < x.length; i++){
                    $(x[i]).iCheck('check');
                }
            });
            $(this.parentE).on('ifUnchecked', function(event){
                var x = $(chd);
                for(let i=0; i < x.length; i++){
                    $(x[i]).iCheck('uncheck');
                }
            });             
        }

        this.setParentElement = function(selector){
            this.parentE = $(selector);
            this.updateParentEvent();
        }     

        this.setParentStatus = function(status){
             $(this.parentE).iCheck(status);
        }                 
    }