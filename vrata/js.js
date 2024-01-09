var input 	= "";
var queryString = window.location.search;
var urlParams = new URLSearchParams(queryString);
var door = urlParams.get('vrata')
var uporabnik="NFC";

function declare() {
	document.getElementsByClassName("loader")[0].style.display='none';
	pass_success(door)
}

function pass_success(door){
	if(door==1 || door==2){
		openDoor(door, 1, uporabnik);
		door=0;
	}
}

function openDoor(vrata,size, uporabnik){
	$.ajax({
		type: "POST",
		url: '../doors.php',
		data:{door:vrata,velikost:size,user:uporabnik},
	});
}