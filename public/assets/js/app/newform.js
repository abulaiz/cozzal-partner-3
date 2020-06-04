function uniqueID() {
	return Math.random().toString().substr(2, 30);
}

function countID() {
	return Math.random().toString().substr(2, 10);
}

function addQuestion(){
	// var count = document.getElementById("countQuestions").value;
	var check = document.getElementById('addQuestion');
	var unique = $(this).data('id');
	var wizard = $(this).data('wizard');
	var id = uniqueID(); var queue = countID();
    var newDiv = document.createElement('div');
    var selectHTML = "";
    selectHTML="<div class='form-group'><div class='checkbox pull-right'><label><input type='checkbox' name='required["+wizard+"]["+id+"]' value='1'> Harus diisi</label></div>";
    selectHTML+="<label for='question["+wizard+"]["+id+"]'>Pertanyaan</label><a id='deleteQuestion' class='btn btn-link pull-right' data-id='"+id+"' data-wizard='"+wizard+"'><span class='fa fa-close'></span> Hapus pertanyaan</a>";
    selectHTML+="<textarea name='question["+wizard+"]["+id+"]' data-id='"+id+"' data-wizard='"+wizard+"' required class='form-control'></textarea><br>";
    selectHTML+="<div class='row'>";
    selectHTML+="<div class='col-md-8'><input name='short["+wizard+"]["+id+"]' data-short='"+id+"' placeholder='Deskripsi singkat' class='form-control' type='text'/>";
    selectHTML+="</div><div class='col-md-4'>";
    selectHTML+="<select class='form-control pull-right changeType_"+id+"' data-id='"+id+"' data-wizard='"+wizard+"' data-unique='"+queue+"' id='changeTypeInput' name='changeTypeInput["+wizard+"]["+id+"]'><option value='text'>Teks</option><option value='radio'>Pilih satu</option><option value='checkbox'>Pilih banyak</option><option value='textarea'>Jawaban Panjang</option><option value='scale'>Skala</option><option value='uploadfile'>Upload File</option></select>";
    selectHTML+="</div></div>";
    selectHTML+="</div>";
    // form
    selectHTML+="<div id='questionOptions-"+id+"'></div>";
    selectHTML+="<div style='padding-top:20px; padding-bottom:20px;'><hr></div>";
    newDiv.innerHTML = selectHTML;
    newDiv.setAttribute("id","question-"+id);
    // ['{{$wizardID}}']
    document.getElementById("questions-"+unique).appendChild(newDiv);
}

function addScreening(){
	// var count = document.getElementById("countQuestions").value;
	// var check = document.getElementById('addQuestion');
	var unique = $(this).data('id');
	var wizard = $(this).data('wizard');
	var id = uniqueID(); var queue = countID();
    var newDiv = document.createElement('div');
    var selectHTML = "";
    selectHTML += "<input name='screeningWizard["+wizard+"]["+id+"]' data-id='"+wizard+"' value='"+wizard+"' type='hidden'>";
    selectHTML += "<div id='screening-"+id+"'><div class='form-group'><label for='screening["+wizard+"]["+id+"]'>Pertanyaan</label>";
    selectHTML += "<div class='row'><div class='col-md-8'><input type='text' name='screening["+wizard+"]["+id+"]' data-id='"+id+"' data-wizard='"+wizard+"' class='form-control' required /></div>";
    selectHTML += "<div class='col-md-4'><select class='form-control pull-right screenChangeType_"+id+"' required data-id='"+id+"' data-wizard='"+wizard+"' data-unique='"+queue+"' id='screenChangeType' name='screenChangeType["+wizard+"]["+id+"]'><option value='radio'>Pilih satu</option><option value='checkbox'>Pilih banyak</option></select></div></div><br>";
    selectHTML += "<div id='screeningOptions-"+id+"'>";
    selectHTML += "<div id='screening-option-"+id+"' class='screening_option_"+id+"' data-id='"+id+"' data-queue='"+id+"'>";
    selectHTML += "<div class='input-group'><span id='addOption' class='input-group-addon'><input type='radio' name='optionTrue["+id+"]["+queue+"]' ></span>";
    selectHTML += "<input name='screeningOptions["+id+"]["+queue+"]' class='form-control' id='row-opt'/>";
    selectHTML += "<span id='screeningAddOption' data-wizard='"+wizard+"' data-id='"+id+"' data-queue='"+id+"' class='input-group-addon fa fa-plus success'></span>";
    selectHTML += "<span id='screeningDeleteOption' data-wizard='"+wizard+"' data-id='"+id+"' data-queue='"+id+"' class='input-group-addon fa fa-minus danger'></span></div>&nbsp;</div>";
    selectHTML += "</div></div><div style='padding-top:20px; padding-bottom:20px;'><hr></div></div>";


    newDiv.innerHTML = selectHTML;
    newDiv.setAttribute("id","screening-"+id);
    // ['{{$wizardID}}']
    document.getElementById("screenings-"+unique).appendChild(newDiv);
}

function addPage(){
	var id = uniqueID(); var queue = countID();
	var wizard = uniqueID();
    var newDiv = document.createElement('div');
    var selectHTML = "";
    selectHTML+="<h2>Halaman selanjutnya</h2><span class='fa fa-close pull-left text text-danger' id='removeWizard' style='cursor:pointer;' data-id='"+id+"'> Hapus halaman</span><br><div id='questions-"+id+"'><input name='wizard["+wizard+"]["+id+"]' data-id='"+wizard+"' value='"+wizard+"' type='hidden'><div id='question-"+id+"'>"
    selectHTML+="<div class='form-group'><div class='checkbox pull-right'><label><input type='checkbox' name='required["+wizard+"]["+id+"]' value='1'> Harus diisi</label></div>";
    selectHTML+="<label for='question["+wizard+"]["+id+"]'>Pertanyaan</label><a id='deleteQuestion' class='btn btn-link pull-right' data-id='"+id+"'><span class='fa fa-close'></span> Hapus pertanyaan</a>";
    selectHTML+="<textarea name='question["+wizard+"]["+id+"]' data-id='"+id+"' data-wizard='"+wizard+"' required class='form-control'></textarea><br>";
    selectHTML+="<div class='row'>";
    selectHTML+="<div class='col-md-8'><input name='short["+wizard+"]["+id+"]' data-short='"+id+"' placeholder='Deskripsi singkat' class='form-control' type='text'/>";
    selectHTML+="</div><div class='col-md-4'>";
    selectHTML+="<select class='form-control pull-right changeType_"+id+"' data-id='"+id+"' data-wizard='"+wizard+"' data-unique='"+queue+"' id='changeTypeInput' name='changeTypeInput["+wizard+"]["+id+"]'><option value='text'>Teks</option><option value='radio'>Pilih satu</option><option value='checkbox'>Pilih banyak</option><option value='textarea'>Jawaban Panjang</option><option value='scale'>Skala</option><option value='uploadfile'>Upload File</option></select>";
    selectHTML+="</div></div>";
    selectHTML+="</div>";
    // form
    selectHTML+="<div id='questionOptions-"+id+"'></div>";
    selectHTML+="<div style='padding-top:20px; padding-bottom:20px;'><hr></div></div></div>";
    selectHTML+="<div class='btn btn-primary btn-xs' id='addQuestion' data-id='"+id+"' data-wizard='"+wizard+"' title='Tambah Pertanyaan'><span class='fa fa-plus'></span> Tambah Pertanyaan</div>"
    newDiv.innerHTML = selectHTML;
    newDiv.setAttribute("id","wizard-"+id);
    document.getElementById("wizards").appendChild(newDiv);
}

function removeWizard() {
	var page = $(this).data('id');
	var question = document.getElementById('question-'+page);
	if(question){
		alert('Masih terdapat form untuk page ini, harap hapus terlebih dahulu pertanyaannya');
	}else{
		document.getElementById('wizard-'+page).remove();
	}
	// body...
}

function deleteQuestion() {
	var question = document.getElementById("deleteQuestion");
	var data  = $(this).data('id');
	var question = document.getElementById("question-"+data);
	if(question){
		question.remove();
	}
}

function deleteScreening() {
	var screening = document.getElementById("deleteScreening");
	var data  = $(this).data('id');
	var screening = document.getElementById("screening-"+data);
	if(screening){
		screening.remove();
	}
}

function changeTypeInput() {
	// var typeInput = document.getElementById("changeTypeInput");
	var id = $(this).data("id");
	var wizard = $(this).data('wizard');
	var unique = $(this).data("unique");
	var typeInput = $('.changeType_'+id).val();
	var optionQuestion = document.getElementById("question-option-"+id);
	if((typeInput == 'radio') || (typeInput == 'checkbox')){
		if(optionQuestion){
			$('.option_'+id).filter('[data-id="'+id+'"]').remove();
		}
		var newDiv = document.createElement('div');
		newDiv.setAttribute('id','question-option-'+id);
		newDiv.setAttribute('class','option_'+id);
		newDiv.setAttribute('data-id',id);
		newDiv.setAttribute('data-queue',unique);
		var selectHTML = "<div class='input-group'>";
		selectHTML += "<input name='opt["+id+"]["+unique+"]' required='required' class='form-control' data-id='"+id+"' data-queue='"+unique+"' id='row-opt'/>";
		selectHTML += "<span id='addOption' data-id='"+id+"' data-queue='"+unique+"'  class='input-group-addon fa fa-plus success'></span>";
		selectHTML += "<span id='deleteOption' data-id='"+id+"' data-queue='"+unique+"'  class='input-group-addon fa fa-minus danger'></span>";
		selectHTML += "</div>&nbsp;";
		newDiv.innerHTML = selectHTML;
		document.getElementById("questionOptions-"+id).appendChild(newDiv);	
	}else if(typeInput == 'scale'){
		if(optionQuestion){
			$('.option_'+id).filter('[data-id="'+id+'"]').remove();
		}
		var newDiv = document.createElement('div');
		newDiv.setAttribute('id','question-option-'+id);
		newDiv.setAttribute('class','option_'+id);
		newDiv.setAttribute('data-id',id);
		newDiv.setAttribute('data-queue',unique);
		var selectHTML = "<div class='input-group'>";
		selectHTML += "<span class='input-group-addon'>Minimum:</span>";
		selectHTML += "<select name='opt["+id+"]["+unique+"][min]' required='required' class='form-control'><option value='0'>0</option><option value='1'>1</option></select>";
		selectHTML += "<span class='input-group-addon'>Maksimum:</span>";
		selectHTML += "<select name='opt["+id+"]["+unique+"][max]' required='required' class='form-control'><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option></select>";
		selectHTML += "</div>&nbsp;";
		selectHTML += "<div class='input-group'>";
		selectHTML += "<span class='input-group-addon'>Minimum:</span>";
		selectHTML += "<input type='text' name='opt["+id+"]["+unique+"][min_desc]' required placeholder='Deskripsi' class='form-control'>";
		selectHTML += "<span class='input-group-addon'>Maksimum:</span>";
		selectHTML += "<input type='text' name='opt["+id+"]["+unique+"][max_desc]' required placeholder='Deskripsi' class='form-control'>";
		selectHTML += "</div>&nbsp;";
		newDiv.innerHTML = selectHTML;
		document.getElementById("questionOptions-"+id).appendChild(newDiv);	
	}else{
		if(optionQuestion){	
			$('.option_'+id).filter('[data-id="'+id+'"]').remove();
		}
	}
}

function screenChangeType() {
	// var typeInput = document.getElementById("changeTypeInput");
	var id = $(this).data("id");
	var wizard = $(this).data('wizard');
	var unique = $(this).data("unique");
	var queue = countID();
	// alert($(this).val());
	var typeInput = $(this).val();
	// var typeInput = $('.screenChangeType_'+id).val();
	
	var optionQuestion = document.getElementById("screening-option-"+id);
	if(optionQuestion){
		$('.screening_option_'+id).filter('[data-id="'+id+'"]').remove();
	}

	var newDiv = document.createElement('div');
	newDiv.setAttribute('id','screening-option-'+id);
	newDiv.setAttribute('class','screening_option_'+id);
	newDiv.setAttribute('data-id',id);
	newDiv.setAttribute('data-queue',unique);

	var selectHTML = "<div class='input-group'><span id='addOption' class='input-group-addon'>";
	if(typeInput == 'radio'){
		selectHTML += "<input type='radio' name='optionTrue["+id+"]["+queue+"]' value='"+queue+"}'></span>";
	}else if(typeInput == 'checkbox'){
		selectHTML += "<input type='checkbox' name='optionTrue["+id+"]["+queue+"]' value='"+queue+"}'></span>";
	}

	selectHTML += "<input name='screeningOptions["+id+"]["+queue+"]' class='form-control' id='row-opt'/>";
	selectHTML += "<span id='screeningAddOption' data-wizard='"+wizard+"' data-id='"+id+"' data-queue='"+countID()+"' class='input-group-addon fa fa-plus success'></span>";
	selectHTML += "<span id='screeningDeleteOption' data-wizard='"+wizard+"' data-id='"+id+"' data-queue='"+countID()+"' class='input-group-addon fa fa-minus danger'></span></div>&nbsp;";

	newDiv.innerHTML = selectHTML;
	document.getElementById("screeningOptions-"+id).appendChild(newDiv);	
	// }
	// else{
	// 	if(optionQuestion){	
	// 		$('.option_'+id).filter('[data-id="'+id+'"]').remove();
	// 	}
	// }
}

function addOption() {
	// var option document.getElementById("addOption");
	var id = $(this).data('id');
	var queue = $(this).data('queue');
	var q = countID();
	// var q = parseInt(queue)+parseInt(1);
	var newDiv = document.createElement('div');
		newDiv.setAttribute('id','question-option-'+id);
		newDiv.setAttribute('class','option_'+id);
		newDiv.setAttribute('data-id',id);
		newDiv.setAttribute('data-queue',q);
		var selectHTML = "<div class='input-group'>";
		selectHTML += "<input name='opt["+id+"]["+q+"]' required='required' class='form-control' data-id='"+id+"' data-queue='"+q+"' id='row-opt'/>";
		selectHTML += "<span id='addOption' data-id='"+id+"' data-queue='"+q+"'  class='input-group-addon fa fa-plus success'></span>";
		selectHTML += "<span id='deleteOption' data-id='"+id+"' data-queue='"+q+"'  class='input-group-addon fa fa-minus danger'></span>";
		selectHTML += "</div>&nbsp;";
		newDiv.innerHTML = selectHTML;
		document.getElementById("questionOptions-"+id).appendChild(newDiv);
	// alert('add option');
}

function screeningAddOption() {
	// var option document.getElementById("addOption");
	var id = $(this).data('id');
	var wizard  = $(this).data('wizard');
	var queue = $(this).data('queue');
	// var type = document.getElementById("screenChangeType").value;
	// var type = document.getElementById("screenChangeType").value;

	var type = $('.screenChangeType_'+id).val();
	// alert(type);
	var q = countID();
	// var q = parseInt(queue)+parseInt(1);
	var newDiv = document.createElement('div');
		newDiv.setAttribute('id','screening-option-'+id);
		newDiv.setAttribute('class','screening_option_'+id);
		newDiv.setAttribute('data-id',id);
		newDiv.setAttribute('data-queue',q);
		var selectHTML = "<div class='input-group'><span id='addOption' class='input-group-addon'>";
		if(type == 'radio'){
			selectHTML += "<input type='radio' name='optionTrue["+id+"]["+q+"]' value='"+q+"' data-id='"+id+"' data-queue='"+q+"' ></span>";
		}else if(type == 'checkbox'){
			selectHTML += "<input type='checkbox' name='optionTrue["+id+"]["+q+"]' value='"+q+"' data-id='"+id+"' data-queue='"+q+"' ></span>";
		}
		selectHTML += "<input name='screeningOptions["+id+"]["+q+"]' required='required' class='form-control' data-id='"+id+"' data-queue='"+q+"' id='row-opt'/>";
		selectHTML += "<span id='screeningAddOption' data-wizard='"+wizard+"' data-id='"+id+"' data-queue='"+q+"'  class='input-group-addon fa fa-plus success'></span>";
		selectHTML += "<span id='screeningDeleteOption' data-wizard='"+wizard+"' data-id='"+id+"' data-queue='"+q+"'  class='input-group-addon fa fa-minus danger'></span>";
		selectHTML += "</div>&nbsp;";
		newDiv.innerHTML = selectHTML;
		document.getElementById("screeningOptions-"+id).appendChild(newDiv);
	// alert('add option');
}

function deleteOption() {
	// $(this).remove();
	var id = $(this).data('id');
	var queue = $(this).data('queue');
	$(".option_"+id).filter('[data-queue="'+queue+'"]').remove();
}

function screeningDeleteOption() {
	// $(this).remove();
	var id = $(this).data('id');
	var queue = $(this).data('queue');
	$(".screening_option_"+id).filter('[data-queue="'+queue+'"]').remove();
}

function totalPoint() {
	var gift = document.getElementById("giftPoint").value;
	var target = document.getElementById("userTarget").value;
	var metas = document.getElementsByTagName('meta'); 
	var url = document.querySelector("meta[name='base-url']").getAttribute("content");
	if((gift) && (target)){
		var total = gift*target;
		var mypoint =0;
	   	for (var i=0; i<metas.length; i++) { 
	      	if (metas[i].getAttribute("name") == "my-point") { 
	        	mypoint = metas[i].getAttribute("content"); 
	      	} 
	   	}
		document.getElementById("totalPoint").value = gift*target;
		var msg_point = document.getElementById("msg-point");
		var newDiv = document.createElement('div');
		if(total > mypoint){
			var tot = parseInt(total)-parseInt(mypoint);
			// alert(".");
			var selectHTML = "<div class='alert alert-danger alert-dismissable'>";
			selectHTML += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
			selectHTML += "<p>Poin anda kurang <strong>"+tot+"</strong> untuk mengaktifkan survey, ";
			selectHTML += "Silahkan lakukan pembelian poin <a href='"+url+"/store' class='btn btn-link'>disini</a></p>";
			selectHTML += "</div>&nbsp;";
			newDiv.innerHTML = selectHTML;
			msg_point.appendChild(newDiv);
		}else{
			msg_point.remove();
		}
	}
}

function saveDraft() {
	
}

$(document).on( "click","#addQuestion", addQuestion);
$(document).on( "click","#addPage", addPage);
$(document).on( "click","#removeWizard", removeWizard);
// $(document).on("click","#saveDraft",saveDraft);
// $(document).on("click","#saveAndActive",saveAndActive);
$(document).on( "click","#addOption", addOption);
$(document).on( "click","#deleteOption", deleteOption);
$(document).on( "click","#deleteQuestion", deleteQuestion);
$(document).on("change","#changeTypeInput", changeTypeInput);
$(document).on("blur","#giftPoint",totalPoint);
$(document).on("blur","#userTarget",totalPoint);




/////////////////////////////////////////////////
/////////////////////////////////////////////////
//////////////// SCREENING FROM /////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////

$(document).on( "click","#addScreening", addScreening);
// $(document).on( "click","#addPage", addPage);
// $(document).on( "click","#removeWizard", removeWizard);
$(document).on("change","#screenChangeType", screenChangeType);

$(document).on( "click","#screeningAddOption", screeningAddOption);
$(document).on( "click","#screeningDeleteOption", screeningDeleteOption);
$(document).on( "click","#deleteScreening", deleteScreening);