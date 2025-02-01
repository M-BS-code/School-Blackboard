<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">  
    <title>Student Notes</title>
    <link rel="stylesheet" href="Notes.css">
    <link rel="stylesheet" href="../css/navbar.css"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <style>
    :root {
  --white-color: #fff;
  --paraText-color: #777;
  --heading-color: #333;
  --primary-color: rgb(31, 153, 167);
  --secondary-color: rgb(94, 7, 40);
}
.dashboard-header {
    background-color: var(--secondary-color);
    padding: 20px;
    display: flex;
    justify-content: space-between; /* Space content evenly */
    align-items: center; /* Center content vertically */
}

.menu-icon {
    font-size: 24px; /* Adjust the size of the menu icon */
    cursor: pointer; /* Show pointer cursor on hover */
    align-items: center; /* Center content vertically */
    

}

.dashboard-title {
    color: white; /* Set the color of the title */
    display: flex; /* Use flexbox */
    align-items: center; /* Center content vertically */
    margin-right: 600px; /* Push the title to the right */
}
  </style>

  </head>
    <body>
        
        <div class="container-fluid">
            <div class="row">  
            
                <!-- Dashboard Header -->
                <div class="col-md-12 dashboard-header">
                    <?php include('StudentNavbar.php'); ?>
                    <h1 class="dashboard-title"><i class="fas fa-bars menu-icon"></i>My Notes</h1>
                </div>
            </div>
        </div>



        <div class="popup-box">
        <div class="popup">
            <div class="content">
            <header>
                <p></p>
                <i class="uil uil-times"></i>
            </header>
            <form action="#">
                <div class="row title">
                <label>Title</label>
                <input type="text" spellcheck="false">
                </div>
                <div class="row description">
                <label>Description</label>
                <textarea spellcheck="false"></textarea>
                </div>
                <div class="row color-picker">
                    <label>Note Color</label>
                    <div class="color-options">
                    <div class="color-option" onclick="selectColor('#f28b82')" style="background-color: #f28b82;"></div>
                    <div class="color-option" onclick="selectColor('#fbbc04')" style="background-color: #fbbc04;"></div>
                    <div class="color-option" onclick="selectColor('#fff475')" style="background-color: #fff475;"></div>
                    <div class="color-option" onclick="selectColor('#ccff90')" style="background-color: #ccff90;"></div>
                    <div class="color-option" onclick="selectColor('#90fbff')" style="background-color: #90fbff;"></div>
                    <div class="color-option" onclick="selectColor('#ff90e7')" style="background-color: #ff90e7;"></div>
                    <div class="color-option" onclick="selectColor('#ed90ff')" style="background-color: #ed90ff;"></div>

                    </div>
                </div>
                <button></button>
            </form>
            </div>
        </div>
        </div>
        <div class="wrapper">
        <li class="add-box">
            <div class="icon"><i class="uil uil-plus"></i></div>
            <p>Add new note</p>
        </li>
        </div>

        <input type="hidden" id="selectedColor" name="color" value="#FFFFFF"> 

    
        
    </body>

    <script>
        const addBox = document.querySelector(".add-box"),
            popupBox = document.querySelector(".popup-box"),
            popupTitle = popupBox.querySelector("header p"),
            closeIcon = popupBox.querySelector("header i"),
            titleTag = popupBox.querySelector("input"),
            descTag = popupBox.querySelector("textarea"),
            addBtn = popupBox.querySelector("button");
            const months = ["January", "February", "March", "April", "May", "June", "July",
                        "August", "September", "October", "November", "December"];
            const notes = JSON.parse(localStorage.getItem("notes") || "[]");
            let isUpdate = false, updateId;
            addBox.addEventListener("click", () => {
                popupTitle.innerText = "Add a new Note";
                addBtn.innerText = "Add Note";
                popupBox.classList.add("show");
                document.querySelector("body").style.overflow = "hidden";
                if(window.innerWidth > 660) titleTag.focus();
            });
            addBtn.addEventListener("click", e => {
            e.preventDefault();
            let title = titleTag.value.trim();
            let description = descTag.value.trim();
            let color = document.getElementById('selectedColor').value; // Capture the selected color

            if (title || description) {
                let currentDate = new Date(),
                month = months[currentDate.getMonth()],
                day = currentDate.getDate(),
                year = currentDate.getFullYear();
                let noteInfo = { title, description, color, date: `${month} ${day}, ${year}` };

                if (!isUpdate) {
                    notes.push(noteInfo);
                } else {
                    isUpdate = false;
                    notes[updateId] = noteInfo;
                }

                localStorage.setItem("notes", JSON.stringify(notes));
                showNotes();
                closeIcon.addEventListener('click', () => {
                popupBox.classList.remove('show');
                document.body.style.overflow = 'auto'; 
                titleTag.value = '';
                descTag.value = '';
        
        });

            }
        });

            function showNotes() {
                if(!notes) return;
                document.querySelectorAll(".note").forEach(li => li.remove());
                notes.forEach((note, id) => {
                    let filterDesc = note.description.replaceAll("\n", '<br/>');
                    let liTag = `<li class="note">
                                    <div class="details">
                                        <p>${note.title}</p>
                                        <span>${filterDesc}</span>
                                    </div>
                                    <div class="bottom-content">
                                        <span>${note.date}</span>
                                        <div class="settings">
                                            <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                                            <ul class="menu">
                                                <li onclick="updateNote(${id}, '${note.title}', '${filterDesc}')"><i class="uil uil-pen"></i>Edit</li>
                                                <li onclick="deleteNote(${id})"><i class="uil uil-trash"></i>Delete</li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>`;
                    addBox.insertAdjacentHTML("afterend", liTag);
                });
            }
            showNotes();
            function showMenu(elem) {
                elem.parentElement.classList.add("show");
                document.addEventListener("click", e => {
                    if(e.target.tagName != "I" || e.target != elem) {
                        elem.parentElement.classList.remove("show");
                    }
                });
            }
            function deleteNote(noteId) {
                let confirmDel = confirm("Are you sure you want to delete this note?");
                if(!confirmDel) return;
                notes.splice(noteId, 1);
                localStorage.setItem("notes", JSON.stringify(notes));
                showNotes();
            }
            function updateNote(noteId, title, filterDesc) {
                let description = filterDesc.replaceAll('<br/>', '\r\n');
                updateId = noteId;
                isUpdate = true;
                addBox.click();
                titleTag.value = title;
                descTag.value = description;
                popupTitle.innerText = "Update a Note";
                addBtn.innerText = "Update Note";
            }

            addBtn.addEventListener("click", e => {
            e.preventDefault(); 

            let title = titleTag.value.trim();
            let description = descTag.value.trim();
            
            let selectedColorElement = document.querySelector('.color-option.selected');
            let color = selectedColorElement ? selectedColorElement.style.backgroundColor : '#ffffff'; 

            if (title || description) {  
            let noteInfo = {
                title,
                description,
                color, 
                date: `${month} ${day}, ${year}`
            };

            if (!isUpdate) {
                notes.push(noteInfo);
            } else {
                notes[updateId] = noteInfo;
                isUpdate = false; 
            }

            localStorage.setItem("notes", JSON.stringify(notes)); 
            showNotes(); 
            closeIcon.click(); 

            titleTag.value = '';
            descTag.value = '';
        }

        });

        function toggleMenu() {
            var sidebar = document.getElementById("sidebar");
            var notesWrapper = document.querySelector(".wrapper");
            var isExpanded = sidebar.style.width === "250px"; 
            sidebar.style.width = isExpanded ? "0" : "250px"; 
            notesWrapper.style.marginLeft = isExpanded ? "0px" : "250px";
        }

        function closeNav() {
            var sidebar = document.getElementById("sidebar");
            var notesWrapper = document.querySelector(".wrapper");
            sidebar.style.width = "0"; 
            notesWrapper.style.marginLeft = "50px"; 
        }



        function toggleDropdown(element) {
            var dropdownContent = element.nextElementSibling;
            dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        };




        document.querySelector('.notes-overlay').addEventListener('click', function() {
            document.getElementById('sidebar').style.width = '0';
            this.style.display = 'none';
        });


        document.querySelectorAll('.color-option').forEach(colorPicker => {
            colorPicker.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(cp => {
                    cp.classList.remove('selected'); 
                });
                this.classList.add('selected'); 
                document.querySelector('.popup-box .popup').style.backgroundColor = this.getAttribute('data-color'); 
            });
        });


        function selectColor(color) {
            document.querySelectorAll('.color-option').forEach(cp => {
                cp.classList.remove('selected');
                cp.style.border = '2px solid white'; 
            });

            event.target.classList.add('selected');
            event.target.style.border = '2px solid black'; 

            document.getElementById('selectedColor').value = color;
            document.querySelector('.popup .content').style.backgroundColor = color;
        }


        notes.forEach((note, id) => {
            let filterDesc = note.description.replaceAll("\n", '<br/>');
            let liTag = `<li class="note" style="background-color: ${note.color};"> 
                            <div class="details">
                                <p>${note.title}</p>
                                <span>${filterDesc}</span>
                            </div>
                            <div class="bottom-content">
                                <span>${note.date}</span>
                                <div class="settings">
                                    <!-- Settings icons -->
                                </div>
                            </div>
                        </li>`;
            addBox.insertAdjacentHTML("afterend", liTag);
        });

        function showNotes() {
            if (!notes) return; 
            document.querySelectorAll(".note").forEach(li => li.remove()); 

            notes.forEach((note, id) => {
                let filterDesc = note.description.replaceAll("\n", '<br/>');
                let liTag = `<li class="note" style="background-color: ${note.color || '#ffffff'};"> 
                                <div class="details">
                                    <p>${note.title}</p>
                                    <span>${filterDesc}</span>
                                </div>
                                <div class="bottom-content">
                                    <span>${note.date}</span>
                                    <div class="settings">
                                        <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                                        <ul class="menu">
                                            <li onclick="updateNote(${id}, '${note.title}', \`${note.description}\`, '${note.color}')"><i class="uil uil-pen"></i>Edit</li>
                                            <li onclick="deleteNote(${id})"><i class="uil uil-trash"></i>Delete</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>`;
                addBox.insertAdjacentHTML("afterend", liTag);
            });
        }



        document.querySelectorAll('.color-option').forEach(colorPicker => {
            colorPicker.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(cp => {
                    cp.classList.remove('selected');
                });
                this.classList.add('selected');
                document.getElementById('selectedColor').value = this.getAttribute('data-color');
            });
        });



    </script>
</html>