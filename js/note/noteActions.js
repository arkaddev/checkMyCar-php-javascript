function menuAddNote() {
        document.getElementById('note-add-note').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

   
function closeMenuAddNote() {
        document.getElementById('note-add-note').style.display = 'none';
   document.getElementById('overlay').style.display = 'none';
    }

  
  
  // Funkcja dodająca nową notatkę do bazy danych
    function addNote(userId) {
  
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const noteTitle = document.getElementById('add-note-title-input').value;
        const noteContent = document.getElementById('add-note-content-input').value;

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `user_id=${userId}&noteTitle=${noteTitle}&noteContent=${noteContent}`
        })
        .then(response => response.json()) // Parsowanie odpowiedzi jako JSON
        .then(data => {
            if (data.status === "success") {
                alert(data.message); // Wyświetlenie komunikatu sukcesu
                location.reload(); // Odświeżenie strony
            } else {
                alert(data.message); // Wyświetlenie komunikatu błędu
            }
        })
        .catch(error => {
            console.error("Wystąpił błąd:", error);
            alert("Wystąpił błąd podczas dodawania notatki.");
        });
    }
  
  
  
  
  
    function deleteNote(noteId) {
  
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `note_id=${noteId}`
        })
        .then(response => response.json()) // Parsowanie odpowiedzi jako JSON
        .then(data => {
            if (data.status === "success") {
                alert(data.message); // Wyświetlenie komunikatu sukcesu
                location.reload(); // Odświeżenie strony
            } else {
                alert(data.message); // Wyświetlenie komunikatu błędu
            }
        })
        .catch(error => {
            console.error("Wystąpił błąd:", error);
            alert("Wystąpił błąd podczas usuwania notatki.");
        });
  
  
  
  }