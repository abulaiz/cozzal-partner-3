function url(){
    var metas = document.getElementsByTagName('meta');
    for (var i=0; i<metas.length; i++) { 
      if (metas[i].getAttribute("name") == "mainURL") { 
         return metas[i].getAttribute("content"); 
      } 
   }
}

function addFilters(){

    // var count = document.getElementById("countQuestions").value;
    var id = document.getElementById('inputCategories').value;
    // var link = ;

    // var selectHTML = id;
    // var newDiv = document.createElement('div');
    // newDiv.innerHTML = selectHTML;
    var _if = document.getElementById('sub-category');
    if(_if){
        document.getElementById('sub-category').remove();
    }
    if(id != "null"){
    $.ajax({
        url: this.url()+"/api/get/subcategory/"+id,
        success: function (data) {
            $("#displayFilter").append(data);
            }
        });
    }
}

function stockItem() {
    // document.getElementById('stock_qty').value = 0;
    if(this.checked){
        if(document.getElementById('stock_qty').hasAttribute('disabled')){
            document.getElementById('stock_qty').removeAttr('disabled');
        }
    }else{
        document.getElementById('stock_qty').getAttribute('disabled')
    }
}
