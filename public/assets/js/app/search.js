function encodeSearch(form) {
	form.elements['q'].value = encodeURIComponent(form.elements['q'].value);
}