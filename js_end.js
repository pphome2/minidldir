<script>

    function delrow(obj){
		obj2=obj.parentNode;
		obj2.parentNode.style.display='none';
    }

// card open/close

function cardclose(th,th2){
	//var x=document.getElementById(th);
	//var x=th.parentElement.parentElement.childNodes[2];
	if (th.style.display=='none'){
		th.style.display='block';
		th2.innerHTML=' -- ';
	} else {
		th.style.display='none';
		th2.innerHTML=' + ';
	}
}

//Tab function for administration

function opentab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("card-body");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    //document.getElementById(tabName).className += " active";
    evt.currentTarget.className += " active";
}

</script>
