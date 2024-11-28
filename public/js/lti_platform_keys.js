$(document).ready(function() {
    $("#searchit").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#platformlist tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
var myModal = document.getElementById('myModal')
var myInput = document.getElementById('myInput')

myModal.addEventListener('shown.bs.modal', function() {
    myInput.focus()
})

var elems = document.getElementsByClassName('delete_confirmation');
var confirmIt = function (e) {
    if (!confirm('Are you sure do you want to delete a platform key?')) e.preventDefault();
};
for (var i = 0, l = elems.length; i < l; i++) {
    elems[i].addEventListener('click', confirmIt, false);
}