var sawlRet = false;

function getSwalBody(title, text){
	return {
		title : title,
		text : text,
		icon : 'info',
		buttons : true,
		dangerMode : true
	}
}

function _confirm(title, text, callback){
	if(!sawlRet){
		swal(getSwalBody(title, text))
		.then((willDelete) => {
			if(willDelete){
				callback();
			}
		});
	}
}