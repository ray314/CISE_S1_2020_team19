/*
Description:Contains a function that checks form validity before submitting data to web server
*/
var form = document.querySelector('.needs-validation');

form.addEventListener('submit', function(event){  //add event listener to submit button
    if(form.checkValidity() == false) {           //check form constraints
        event.preventDefault();                   //prevent submit button submitting form to server
        event.stopPropagation();                  //prevent event being sent to buttons parent element
    }
    form.classList.add('was-validated');
})
