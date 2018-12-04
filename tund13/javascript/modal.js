let modal;
let modalImg;
let captionText;
let closeBtn;
let photoDir = "../vppicuploads/";
let modalId;

window.onload = function(){
	modal = document.getElementById("myModal");
	modalImg = document.getElementById("modalImg");
	captionText = document.getElementById("caption");
	closeBtn = document.getElementsByClassName("close")[0];
	let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
	let thumbCount = allThumbs.length;
	for(let i = 0;i < thumbCount; i++){
		allThumbs[i].addEventListener("click", openModal);
	}
	closeBtn.addEventListener("click", closeModal);
	modalImg.addEventListener("click", closeModal);
}

function openModal(e){
	modalImg.src = photoDir + e.target.dataset.fn;
	modalId = e.target.dataset.id;
	//console.log(modalId);
	captionText.innerHTML = "<p>" + e.target.alt + "</p>";
	modal.style.display = "block";
	document.getElementById("storerating").addEventListener("click", storeRating);
}

function storeRating(){
	//console.log("Siin");
	rating = 0;
	for(let i = 1; i < 6; i ++){
		if(document.getElementById("rating" + i).checked){
			rating = i;
		}
	}
	if(rating > 0){
		//console.log("seal");
	// Siit algab AJAX
	let req = new XMLHttpRequest();
	req.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200) {
			// siia kõik see, mida javascript teeb serverilt saadud vastusega
			document.getElementById("avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
		}
	};
	// Teeme päringu, määrame aadressi ja parameetrid
	//storerating.php?id=6&rating=3
	req.open("GET", "storerating.php?id=" + modalId + "&rating=" + rating, true);
    req.send();
	//AJAX lõppes
	}
}

function closeModal(){
	modal.style.display = "none";
}
