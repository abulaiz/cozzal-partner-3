var timer;
var keyCode;
var selItems={};

function del_sel(){
	var id = this.id.replace("ccl","sel");
	var a = id.split("-");
	$("#"+id).remove();
	selItems[a[1]] = selItems[a[1]].replace("&"+a[2],"");

	var f = document.getElementById(a[1]+"-name");
	var tmp = JSON.parse(f.value);
	var count = Object.keys(tmp).length;

	if(count==1){
		document.getElementById(a[1]+"-sel").style.padding = "0px";
		f.value = "";
	} else {
		delete tmp[a[2]];
		f.value = JSON.stringify(tmp);
	}

	$("#"+a[1]+"-search").focus();
}

function add_sel(){
	var konten = this.innerHTML;
	var identifier = this.id;
	identifier = identifier.split("-");
	var id = identifier[1];
	var val = identifier[2];

	$("#"+id+"-sel").append('<div id="sel-'+id+'-'+val+'" class="ss-selitems">'+konten.substr(0,8)+'...<a href="#" id="ccl-'+id+'-'+val+'" class="ss-selCancle">×</a></div>');
	document.getElementById("ccl-"+id+"-"+val).addEventListener("click",del_sel);

	document.getElementById(id+"-option").style.visibility = "hidden";

	selItems[id] += "&"+val;

	// add value to hidden element
	var f = document.getElementById(id+"-name");
	document.getElementById(id+"-sel").style.padding = "5px";
	if(f.value==""){
		f.value = '{"'+val+'" : "'+val+'"}';
	} else {
		var tmp = JSON.parse(f.value);
		tmp[val] = val;
		f.value = JSON.stringify(tmp);
	}

	$("#"+id+"-search").val("");
	$("#"+id+"-search").focus();
}

function add_sel2(id,val,konten){

	$("#"+id+"-sel").append('<div id="sel-'+id+'-'+val+'" class="ss-selitems">'+konten.substr(0,8)+'...<a href="#" id="ccl-'+id+'-'+val+'" class="ss-selCancle">×</a></div>');
	document.getElementById("ccl-"+id+"-"+val).addEventListener("click",del_sel);

	document.getElementById(id+"-option").style.visibility = "hidden";

	selItems[id] += "&"+val;

	// add value to hidden element
	var f = document.getElementById(id+"-name");
	document.getElementById(id+"-sel").style.padding = "5px";
	if(f.value==""){
		f.value = '{"'+val+'" : "'+val+'"}';
	} else {
		var tmp = JSON.parse(f.value);
		tmp[val] = val;
		f.value = JSON.stringify(tmp);
	}

	$("#"+id+"-search").val("");
	$("#"+id+"-search").focus();
}

function queryReq(res,id,Url){
	// $(".loading").show();
	$.ajax({
    url: Url+$("#"+id+"-search").val(),
    success: function (data) {
	   		// remove current element inside dropdown
		    var list_base = document.getElementById(id+"-option");
		    while(list_base.firstChild){
		      list_base.removeChild(list_base.firstChild);
		    } 	
			var response = JSON.parse(data);
        	var count = Object.keys(response).length;
        	if(count > 0 ){
			    // add new element
			    var counted = 0;
			    for(var i=0; i<count; i++){
			    	// initiate values
			    	var val = response[i][res.value];
			    	var text = response[i][res.text];
			    	// append element
			    	var pos = selItems[id].indexOf("&"+val);
			    	if(pos<0){
			    		counted++;
				    	$("#"+id+"-option").append('<div id="opt-'+id+'-'+val+'" class="ss-items">'+text+'</div>');
				  		document.getElementById("opt-"+id+"-"+val).addEventListener("click",add_sel);
			    	}
			    }
			    if (counted===0) {
			    	$("#"+id+"-option").append('<p style="padding:3px;">Item has selected</p>');
			    }
        	} else {
        		$("#"+id+"-option").append('<p style="padding:3px;">No Macth Result</p>');
        	}
        	document.getElementById(id+"-option").style.visibility = "visible";
        	// $(".loading").hide();        	
        }
    });
}

function stop_filter(id){
	clearTimeout(timer);
	document.getElementById(id+"-option").style.visibility = "hidden";
}

function filter(x){
	// reset timer
    clearTimeout(timer);

    // get the text of search input
    var input = x.e.value;
    var key = String.fromCharCode(keyCode);

    if(input!="" && /[a-zA-Z0-9-_ ]/.test(key)){
      timer = setTimeout(function(){
      	queryReq(x.data, x.id, x.url);
      },x.delay);
    }
}

function lossDropDown(x){
	document.getElementById(id+"-option").style.visibility = "visible";
}

function setValue(id,values){
	for(var i=0;i<values.length;i++){
		add_sel2(id,values[i].value,values[i].text);
	}
}

function new_sSearch(x) {
	// initiate property
	var id = x.id;
	var nama = x.name;
	var timerr = x.timing;
	var response = x.response;
	var values = x.values;
	var url = x.url;
	// add queue selected items
	selItems[id] = "`";

	// Handle optional value
	if (timerr===undefined) timerr = 700;
	if (response===undefined)
		response = {"value":"value", "text":"text"};

	// add new element to store selected items
	$("#"+id).append("<input style='display:none' type='text' id='"+id+"-name' name='"+nama+"' />");

	// Optional to set value
	if(values!==undefined)
		setValue(id, values);

	// add evenListener to search bar
	var s = document.getElementById(id+"-search");
	s.addEventListener("keyup", (event) => {
		keyCode = event.keyCode;
		if(keyCode==27){
			lossDropDown(id);
		}
	});
	s.addEventListener("keyup", function(){
      filter({
      	"id" : id,
        "e" : this,
        "delay" : timerr,
        "data" : response,
        "url" : url
      });
    });
	s.addEventListener("keydown",function(){
		stop_filter(id);
	});
}
