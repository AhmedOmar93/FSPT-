 function saveDate() {
	var d = document.getElementById("datetimepicker").value;
	var date = new Date(d) ;
	/* 2015-04-02 17:08:37 */
	var dueDate = date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate() + " " +
					 date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() ;

	document.getElementById("demo").innerHTML = "You selected the date: " + dueDate;
};