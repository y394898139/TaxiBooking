var xhr = createRequest();

//create request
function createRequest() {
    var xhr = false;  
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xhr;
} 
//view the request
function getDataNum(dataSource, divID, referenceNum) {
	if (xhr) {
		var obj = document.getElementById(divID);
		var requestbody = "&referenceNum=" + encodeURIComponent(referenceNum);
		
		xhr.open("POST", dataSource, true);
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				obj.innerHTML = xhr.responseText;
			} 
		} 
		xhr.send(requestbody);
	}
} 

//change assign
function getData(dataSource, divID) {
	if (xhr) {
		var obj = document.getElementById(divID);
		xhr.open("POST", dataSource, true);
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				obj.innerHTML = xhr.responseText;
			} 
		} // end anonymous call-back function
		xhr.send(null);
	}
} 

