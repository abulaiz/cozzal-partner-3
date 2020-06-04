const message_utility = {
	icon : function($e){
		$icons = {
			"warning" : "fa fa-warning",
			"info" : "fa fa-info-circle",
			"success" : "fa fa-check-circle",
			"danger" : "fa fa-times-circle",
			"update" : "fa fa-pencil"
		};
		return $icons[$e];
	},
	bg : function($e){
		$bgs = {
			"warning" : "bg-warning",
			"info" : "bg-info",
			"success" : "bg-success",
			"danger" : "bg-danger",
			"update" : "bg-primary"
		};
		return $bgs[$e];
	},
	caption : function($e){
		$capt = {
			"warning" : "Warning",
			"info" : "Information",
			"success" : "Success",
			"danger" : "Failed",
			"update" : "Updated"
		};
		return $capt[$e];
	}
};

function getMessageElement(message, type){
	return '<div class="alert '+message_utility.bg(type)+' alert-icon-left alert-dismissible mb-2" role="alert">'
			+'<span class="alert-icon"><i class="'+message_utility.icon(type)+'"></i></span>'
			+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
			+'<span aria-hidden="true">&times;</span>'
			+'</button>'
			+'<strong>'+ message_utility.caption(type) +'. </strong>'+message
			+'</div>';	
}

function setMessage(message, type){
	$("#message-bar").append( getMessageElement(message, type) );
	$("#message-bar").css('opacity', '1');
}

window._checkMessage = function(path){
	if( sessionStorage.getItem(path) !== undefined && sessionStorage.getItem(path) != null ){
	    let m = JSON.parse(sessionStorage.getItem(path));
	    setMessage(m.message, m.type);
	    sessionStorage.removeItem(path);
	}	
}

window._setMessage = function(path, message, type){
	sessionStorage.setItem(path, JSON.stringify({message : message, type : type}));
}