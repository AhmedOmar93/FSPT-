   function saveDate() {
   var d = document.getElementById("datetimepicker").value;
   var date = new Date(d) ;
                          /* 2015-04-02 17:08:37 */
   var dueDate = date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate() + " " +
                          date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() ;
   document.getElementById('DueDate').value = dueDate;
  }
  /*|Upload a new assignment                    |
    |Triggers the google drive api functionality|
    |Shows the upload form                      |*/
  function editInfo() {
    var addForm = document.getElementById("Add_Assignment");
      if(addForm.style.display == "block" ) {
              addForm.style.display = "none";
              document.getElementById('editFormActivated').value = "false";
        }
      else {
            document.getElementById('editFormActivated').value = "true";
            addForm.style.display = "block";
      }
  }
  function changeFile(){
            document.getElementById('resultName').value = "Loading File Upload";
            var my_awesome_script = document.createElement('script');
            my_awesome_script.setAttribute('src','https://apis.google.com/js/api.js?onload=onApiLoad');
            document.head.appendChild(my_awesome_script);
            addForm.style.display = "block";
  }
  function showSearch(){
    var select = document.getElementById("SelectBy");
    var search = document.getElementById("search");
    var level = document.getElementById("level");
    var dept = document.getElementById("dept");
    if (select.selectedIndex == 3 || select.selectedIndex == 4)
    {
      level.style.display = "none";
      dept.style.display = "none";
      search.style.display = "block";
      if(select.selectedIndex == 3)
      search.value="IdEx: 20110141";
      else
      search.value="NameEx: First Middle Last";
    }
    else if (select.selectedIndex == 0||select.selectedIndex == 1||select.selectedIndex == 2) {
      level.style.display = "block";
      dept.style.display = "block";
      search.style.display = "none";
    }
  }