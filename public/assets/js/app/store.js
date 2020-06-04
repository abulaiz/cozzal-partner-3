function url(){
    var metas = document.getElementsByTagName('meta');
    for (var i=0; i<metas.length; i++) { 
      if (metas[i].getAttribute("name") == "mainURL") { 
         return metas[i].getAttribute("content"); 
      } 
   }
}

function checkCountry(){

    var id = document.getElementById('location').value;
    // alert(id);
    var _if = document.getElementById('fee');
    var t = document.getElementById('inputCartTotal').value;
    var total = Number(t.replace(/[^0-9\.-]+/g,""));
    // alert(total);
    if(_if){
        document.getElementById('fee').remove();
    }
    if(id == ""){
        document.getElementById('cartTotal').innerHTML = t;
    }else{
    $.ajax({
        url: this.url()+"/get/postage/country/"+id,
        success: function (data) {
            $("#postalfee").append(data);
            var num = +total + +document.getElementById('input_postal_fee').value;
            var n = num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            document.getElementById('cartTotal').innerHTML = n+'.00';
            }
        });
    }
}

function viewComment(id) {
    var _if = document.getElementById('discussion_'+id);
    if(_if){
        document.getElementById('discussion_'+id).remove();
    }
    $.ajax({
        url: this.url()+"/api/get/comment/parent/"+id,
        success: function (data) {
            $("#"+id).append(data);
            }
        });
}

function viewTopic() {
    var id = document.getElementById('master_help').value;
    var _if = document.getElementById('sub-topic');
    if(_if){
        document.getElementById('sub-topic').remove();
    }
    if(id != "null"){
    $.ajax({
        url: this.url()+"/api/get/subtopic/"+id,
        success: function (data) {
            $("#detailTopic").append(data);
            }
        });
    }
}
