<template>
    <div class="row">
        <div class="col-md-12">
            <a v-if="selected_image != null" href="javascript:void(0)" class="text-white close-button" @click="resetSelected">
                &times
            </a>
            <img :src="selected_image" v-if="selected_image != null" class="image-preview">
            <div v-show="selected_image == null" class="drop-area text-center" @click="triggerUploadFile">
                <p class="drop-message">Drop file here or click to add image</p>
            </div>
        </div>

        <!-- Belakang Layar -->
        <input ref="file" type="file" style="display: none;" accept="image/*" @change="previewFile" name="image" >  
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                selected_image: null,
                drop_area : null
            }
        },
        props: [
            'value'
        ],
        methods: {
            dataURLtoBlob : function(dataURL) {
                var byteString = atob(dataURL.split(',')[1]);
                var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0]
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) {
                  ia[i] = byteString.charCodeAt(i);
                }
                var blob = new Blob([ab], {type: mimeString});
                return blob;
            },
            previewFile(files = null) {
                let e = this;
                let file    = this.$refs.file.files[0];
                let reader  = new FileReader();
                reader.onloadend = function () {
                    e.selected_image = reader.result;
                    e.updateFileBlob();
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    e.selected_image = null;
                    e.updateFileBlob();
                }
            },
            triggerUploadFile(){
                this.$refs.file.click();
            },
            resetSelected(){
                this.selected_image = null;
                this.updateFileBlob()
            },
            updateFileBlob(){
                if( this.selected_image == null )
                    this.$emit("input", null)
                else
                    this.$emit("input", this.dataURLtoBlob(this.selected_image))

            }           
        },
        mounted: function(){
            let $form = $(".drop-area")
            let v = this;

            $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
            })
            .on('dragover dragenter', function() {
                $form.addClass('is-dragover');
            })
            .on('dragleave dragend drop', function() {
                $form.removeClass('is-dragover');
            })
            .on('drop', function(e) {
                let file    = e.originalEvent.dataTransfer.files[0];
                let reader  = new FileReader();
                reader.onloadend = function () {
                    v.selected_image = reader.result;
                    v.updateFileBlob();
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    v.selected_image = null;
                    v.updateFileBlob();
                }                
            });
        },
        watch: {
            value: function(val){
                if(val == null){
                    this.resetSelected()
                }
            }
        }
    }
</script>

<style scoped>
    .is-dragover{
        background-color: #e5e8ec !important;
    }
    .drop-area{
        background-color: white;
        color: #6d6f72;
        width: 100%;
        height: 150px;
        border-radius: 8px;
        border: 1px solid #adb5bd;
        cursor: pointer;
    }
    .drop-message{
        font-size: 1rem;
        padding-top: 61px;
    }
    .image-preview{
        width: 100%;
        height: auto;
    }
    .close-button{
        position: absolute;
        top: 6px;
        right: 23px;
        text-align: center;
        background-color: rgb(66, 61, 61);
        height: 30px;
        width: 30px;
        border-radius: 50%;
        padding: 5px;
        font-weight: bold;
    }
    .close-button:hover{
        border: 2px solid white;
        padding: 3px;
    }    
</style>