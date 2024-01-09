var door=0; //katera vrata se odprejo
var type=0; //kako se odprejo
var input 	= ""; //deklaracija stringa za pin
var initialX = null; //deklaracija pozicija za slider pri mobilnih napravah (vreme)
var initialY = null; //deklaracija pozicija za slider pri mobilnih napravah (vreme)
var barva='#8bd0ec'; //default brava zalogovnika

function declare() {
	var input 	= "";
	var	correct = "2382";
	
	var dots    = document.querySelectorAll(".dot"), 
		numbers = document.querySelectorAll(".number");
		dots    = Array.prototype.slice.call(dots);
		numbers = Array.prototype.slice.call(numbers);
		console.log(numbers.length);
		numbers.splice(-1,1)
		console.log(numbers.length);
	
	numbers.forEach(function(number, index) {
		number.addEventListener('click', function() {
			number.className += ' grow';
			input += (index+1);
			dots[input.length-1].className += ' active';
			if(input.length >= 4) {
				if(input !== correct) {
					dots.forEach(function(dot, index) {
						dot.className += " wrong";
					});
					document.body.className += " wrong";
				}
				else {
					pass_success(door,type)
					input = "";
					dots.forEach(function(dot, index) {
						dot.className += " correct";
					});
					document.body.className += " correct";
				}
				setTimeout(function() {
					dots.forEach(function(dot, index) {
						dot.className = "dot";
					});
					input = "";
					hide_pass();
				}, 900);
				setTimeout(function() {
					document.body.className = "";
				}, 1000);
			}
			setTimeout(function() {
				number.className = 'number';
			}, 1000);
		});
	});
	
	//omogoča vrtenje ikone če toplotna deluje
	if(fan_speed_chart!=0){
		document.getElementsByClassName("rotate")[0].style.animation="rotation 1s infinite linear";
	}
	
	//dodatna inicializacija za slider pri mobilnih napravah (vreme)
	var container = document.getElementById("page1");
	var container1 = document.getElementById("page2");
	container.addEventListener("touchstart", startTouch, false);
	container.addEventListener("touchmove", moveTouch, false);
	container1.addEventListener("touchstart", startTouch, false);
	container1.addEventListener("touchmove", moveTouch, false);
	
	//generiranje grafov
	draw(real_out_chart,'line','.chart_temp', 'Temperatura');
	draw(fan_speed_chart,'gauage','.chart_fan_speed','',);
	draw(pump_speed_chart,'gauage','.chart_pump_speed','',);
	draw(dm_chart,'line','.chart_dm', 'Stopinjske minute');
	
	//ob zagonu spletne strani odpre home page
	document.getElementById("defaultOpen").click();
	
	//sprememba ikone za lučiu glede na stanje luči.
	if(light_vhod=='vklop')
		document.getElementById('vhod').src='img/light_on.jpg';
	if(light_garaza=='vklop')
		document.getElementById('garaza').src='img/light_on.jpg';
	if(light_kuhinja=='vklop')
		document.getElementById('kuhinja').src='img/light_on.jpg';
	if(light_terasa=='vklop')
		document.getElementById('terasa').src='img/light_on.jpg';
	if(garbage!=''){
		document.getElementById('garbage').style.display='block';
		document.getElementById('typeOfGarabe').src='img/smeti.jpg';
	}
	
	//sprememba barve zalogovnika glede na količino vode
	if(watter<20){
		document.getElementById("tablink_voda").classList.add("blink_me");
		setTimeout(function(){
			document.getElementsByClassName("indicator")[0].classList.remove("blink_me")}, 30000);
		document.getElementById("tablink_voda").style.backgroundColor='#da4453b8';
		barva='#e80e0e'
	}
	
	if(ventil=='close_all'){
		document.getElementById("half").src="img/pipe.jpg";
		document.getElementById("full").src="img/pipe.jpg";
	}
	if(ventil=='half'){
		document.getElementById("half").src="img/pipe_on.jpg";
		document.getElementById("full").src="img/pipe.jpg";
	}
	if(ventil=='full'){
		document.getElementById("half").src="img/pipe.jpg";
		document.getElementById("full").src="img/pipe_on.jpg";
	}
	
	//generiranje tanka vode
	$(document).ready(function() {
			$('.waterTankHere1').waterTank({
				width: 150,
				height: 150,
				color: barva,
				level: watter
			});
		});
}

//ko se body dokončno naloži se loading screen pospravi
function on_startup(){
	document.getElementsByClassName("loader")[0].style.display='none';
}

//funckija se izvede če se geslo pravilno vtipka. Vrata, zalogovnik
function pass_success(door, type){
	if(door==1 || door==2){
		document.getElementsByClassName("indicator")[0].classList.add("blink_me")
		setTimeout(function(){
			document.getElementsByClassName("indicator")[0].classList.remove("blink_me")}, 5000);
		openDoor(door, type, user);
		door=0;
		type=0;
	}
	if(type==3){
		$.ajax({
			type: "POST",
			url: 'walve.php',
			data:{vrsta:"close_all",user:user},
		});
		document.getElementById("full").src="img/pipe.jpg";
		document.getElementById("half").src="img/pipe.jpg";
		type=0;
	}
}

//omogoča premikanje med stranmi
function openTab(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

//omogoča skritje zaslona za vnos gesla
function hide_pass(){
	input 	= "";
	document.getElementById("choser").style.display="none";
	document.getElementsByClassName('pin')[0].classList.remove('show');
}

//omogoča prikaz zaslona za vnos gesla
function show_pass(m){
	if(m==11){
		door=1;
		type=1;
	}
	if(m==21){
		door=2;
		type=1;
	}
	if(m!=11 && m!=21)
		type=m;
	document.getElementsByClassName('pin')[0].classList.add('show');
}

//omogoča prikaz izbere kako se bodo vrata odprla (delno/popolno)
function slide(n){
	if(n==-1)
		document.getElementById("choser").style.width="0px";
	else{
		door=n;
		document.getElementById("choser").style.display="flex";
	}
}

//omogoča klic php funckije ki poskrbi da se vrata odprejo
function openDoor(vrata, size, uporabnik){
	$.ajax({
		type: "POST",
		url: 'doors.php',
		data:{door:vrata,velikost:size,user:uporabnik},
	});
}

//omogoča prikaz zaslona za kamero
function show_camera(){
	if(document.getElementsByClassName('camera_preview')[0].style.display=="none")
		document.getElementsByClassName('camera_preview')[0].style.display="block";
	else
		document.getElementsByClassName('camera_preview')[0].style.display="none";
}

//omogoča generiranje grafov
function draw(podatki, typee, place, name){
console.log("in");
	if(typee=="gauage"){
		var options = {
		  chart: {
			height: '65%',
			type: "radialBar",
			fontFamily: 'Quicksand',
			 sparkline: {
				enabled: false,
			}
		  },
		  grid: {
                    padding: {
                        top: -15,
                        bottom: -15
                    }
                },
		  
		  series: [podatki],
		  
		  plotOptions: {
			  radialBar: {
				hollow: {
					margin: 0,
					size: "50%"
				  },
				dataLabels: {
					name: {
					  show: false,
					},
					value: {
					  fontSize: "18px",
					  show: true
					}
				  }
			  }
			},

		  stroke: {
			lineCap: "round",
		  }
		};

		var chart = new ApexCharts(document.querySelector(place), options);

		chart.render();
	}
	else{
		var options = {
		  chart: {
			height: '100%',
			width:'100%',
			type: "area",
			fontFamily: 'Quicksand',
			toolbar:{
				show: false
			}
		  },
		  yaxis: {
			  labels: {
				offsetX: -10
			  }
			},
			grid: {
			  padding: {
				left: -5
			  }
			},
		  stroke:{
			curve: 'smooth'
		  },
		  dataLabels: {
			enabled: false
		  },
		  series: [
			{
			  name: name,
			  data: podatki[0]
			}
		  ],
		  fill: {
			type: "gradient",
			gradient: {
			  shadeIntensity: 1,
			  opacityFrom: 0.7,
			  opacityTo: 0.9,
			  stops: [0, 90, 100]
			}
		  },
		  xaxis: {
			categories: podatki[1],
			labels : {
				show: false,
				rotate: 0
			},
			tickAmount: 5
		  }
		};

		var chart = new ApexCharts(document.querySelector(place), options);

		chart.render();
	}
}

//omogoča spremebo zalogovnika
function walve(data){
	if(data=='full'){
		document.getElementById(data).src="img/pipe_on.jpg";
		document.getElementById("half").src="img/pipe.jpg";
		$.ajax({
			type: "POST",
			url: 'walve.php',
			data:{vrsta:"full",user:user},
		});
	}
	if(data=='half'){
		document.getElementById(data).src="img/pipe_on.jpg";
		document.getElementById("full").src="img/pipe.jpg";
		$.ajax({
			type: "POST",
			url: 'walve.php',
			data:{vrsta:"half",user:user},
		});
	}
	if(data=='kill'){
		show_pass(3);
	}
}

//omogoča vklop in izklop luči
function light(para,id){
	if(document.getElementById(para).src=='img/light_off.jpg'){
		var formData = new FormData();
		formData.append("auth_key", "NTE2YWN1aWQ556D1F746554910D6E95B3C9CE36195D0096F4BB103BE21271DB89201B9D7C4AD680CB86F76338C5");
		formData.append("id", id);
		formData.append("turn", "on");
		formData.append("channel", "0");
		var request = new XMLHttpRequest();	
		request.open("POST", "https://shelly-24-eu.shelly.cloud/device/relay/control/");
		request.onreadystatechange = function() { // Call a function when the state changes.
			if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
				document.getElementById(para).src='img/light_on.jpg'
			}
		}
		request.send(formData);
	}
	else{
		var formData = new FormData();
		formData.append("auth_key", "NTE2YWN1aWQ556D1F746554910D6E95B3C9CE36195D0096F4BB103BE21271DB89201B9D7C4AD680CB86F76338C5");
		formData.append("id", id);
		formData.append("turn", "off");
		formData.append("channel", "0");
		var request = new XMLHttpRequest();	
		request.open("POST", "https://shelly-24-eu.shelly.cloud/device/relay/control/");
		request.onreadystatechange = function() { // Call a function when the state changes.
			if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
				document.getElementById(para).src='img/light_off.jpg'
			}
		}
		request.send(formData);
	}
}

//slider za vreme na mobilnih napravah
function startTouch(e) {
	initialX = e.touches[0].clientX;
	initialY = e.touches[0].clientY;
};

//slider za vreme na mobilnih napravah
function moveTouch(e) {
	if (initialX === null) {
	  return;
	}

	if (initialY === null) {
	  return;
	}

	var currentX = e.touches[0].clientX;
	var currentY = e.touches[0].clientY;

	var diffX = initialX - currentX;
	var diffY = initialY - currentY;

	if (Math.abs(diffX) > Math.abs(diffY)) {
	  // sliding horizontally
	  if (diffX > 0) {
		// swiped left
		showPage2();
	  } else {
		// swiped right
		showPage1();
	  }  
	}

	initialX = null;
	initialY = null;

	e.preventDefault();
};

//slider za vreme na mobilnih napravah
function showPage1()
{
    document.getElementById("content").setAttribute("class", "")
}

//slider za vreme na mobilnih napravah
function showPage2()
{
    document.getElementById("content").setAttribute("class", "showPage2")
}

//omogoča prikaz napovedi vremena na mobilnih napravah
function open_weater(){
	if(document.getElementsByClassName("weater")[0].style.gridTemplateColumns=="0px 100%"){
		document.getElementsByClassName("weater")[0].style="grid-template-columns:70% 30%;"
		document.getElementsByClassName("day0")[0].style="width:100%;"
		document.getElementsByClassName("day1")[0].style="display:none;"
		document.getElementsByClassName("day2")[0].style="display:none;"
		document.getElementsByClassName("future")[0].style="display:flex;"
	}
	else{
		document.getElementsByClassName("weater")[0].style="grid-template-columns:0 100%;"
		document.getElementsByClassName("day0")[0].style="width:33.3%;"
		document.getElementsByClassName("day1")[0].style="display:block;"
		document.getElementsByClassName("day2")[0].style="display:block;"
		document.getElementsByClassName("future")[0].style="display:flex;"
	}
}

//slider za skrizje smetnjaka
function close_garbage(){
	document.getElementById("typeOfGarabe").style.display="none";
}

//omogoča testno pošioljanje sporočil na admin strani
function sendmessage(title, message){
	$.ajax({
		type: "POST",
		url: 'Sendmessage.php',
		data:{title:title,message:message},
	});
}

//naslednji dve funkciji omogočita da se v paragrafu prikažejo vsi uporabniki ki so shranjeni v tabeli.
function show(item, index){
	document.getElementById("users").innerHTML += index+1 + ": " + item + "<br>"; 
}

function loadusers(users){
	users.forEach(show);
}