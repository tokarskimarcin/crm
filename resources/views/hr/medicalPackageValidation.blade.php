//Zmienna niezbędna dla pakietu medycznego
validationResult = true;
//Tutaj walidacja pakietu medycznego

if (medicalPackageShow == true || showEditMedical == true) {
//pobranie typu pakietu medycznego
var package_variable = $('#package_variable').val();
var package_name = $('#package_name').val();

// Sprawdzenie czy został wybrany pakiet
if (package_name == 'Wybierz') {
swal('Wybierz pakiet!');
validationResult = false;
return false;
}

// Sprawdzenie czy został wybrany rodzaj pakietu
if (package_variable == 'Wybierz') {
swal('Wybierz wariant pakietu!');
validationResult = false;
return false;
}

// Sprawdzenie czy podane są wsyzstkie numery domow
$("input[name='house_number[]']").each(function(key, value){
if ($(value).val().trim().length == 0) {
swal('Podaj numery domów!');
validationResult = false;
return false;
}
});

// Sprawdzenie czy podane sa wszystkie nazwy ulic
$("input[name='street[]']").each(function(key, value){
if ($(value).val().trim().length == 0) {
swal('Podaj wszystkie nazwy ulic!');
validationResult = false;
return false;
}
});

// Sprawdzenie czy podane sa wszystkie kody pocztowe

var postalCodeRegex = /[0-9][0-9]-[0-9][0-9][0-9]/;

$("input[name='postal_code[]']").each(function(key, value){
if ($(value).val().trim().length == 0  || !$(value).val().match(postalCodeRegex) || $(value).val().trim().length > 6) {
swal('Podaj prawidłowe kody pocztowe!');
validationResult = false;
return false;
}
});

//Sprawdzenie czy podane sa wszystkie miasta
$("input[name='city[]']").each(function(key, value){
if ($(value).val().trim().length == 0) {
swal('Podaj wszystkie nazwy miejscowości!');
validationResult = false;
return false;
}
});

//Sprawdzenie czy podane sa numery telefonow
$("input[name='phone_number[]']").each(function(key, value){
if ($(value).val().trim().length == 0) {
swal('Podaj wszystkie numery telefonów!');
validationResult = false;
return false;
}
});

//SPrawdzenue czy podane sa wszystkie imiona
$("input[name='user_last_name[]']").each(function(key, value){
if ($(value).val().trim().length == 0) {
swal('Podaj wszystkie nazwiska!');
validationResult = false;
return false;
}
});

// Sprawdzenie czy podane sa wszystkie nazwiska
$("input[name='user_first_name[]']").each(function(key, value){
if ($(value).val().trim().length == 0) {
swal('Podaj wszystkie imiona!');
validationResult = false;
return false;
}
});

//Sprawdzenie czy podane są poprawne pesele lub daty urodzenia w formacie "u0000-00-00"
if ($('#pesel').val().trim().length == 0 && $('#birth_date').val().trim().length == 0) {
swal('Podaj numer pesel lub datę urodzenia w formacie "u000-00-00"!');
validationResult = false;
return false;
}

var pesel = $('#pesel').val();
var birth_date = $('#birth_date').val();

if (pesel =! null && pesel.length > 0 && (pesel.length != 11 || isNaN(pesel))) {
swal('Podaj prawidłowy numer pesel!');
validationResult = false;
return false;
}

var birthDateRegex = /u[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]/;

if (birth_date != null && birth_date.length > 0 && (birth_date.length != 11 || !birth_date.match(birthDateRegex))) {
swal('Podaj datę w formacie "u0000-00-00"!');
validationResult = false;
return false;
}

//Próba sprawdzenia czy pesele lub daty urodzenia sa podane
if (package_variable == 'PARTNERSKI' || package_variable == 'RODZINNY') {
$(".check_for_family").each(function(key, value){
var memberPesel = $(value).find('input[name="pesel[]"]').val();
var memberBirthDate = $(value).find('input[name="birth_date[]"]').val();

if (memberPesel.length == 0 && memberBirthDate.length == 0) {
swal('Członkowie rodziny muszą mieć podany pesel lub datę urodzenia!');
validationResult = false;
return false;
}

if (memberPesel =! null && memberPesel.length > 0 && (memberPesel.length != 11 || isNaN(memberPesel))) {
swal('Podaj prawidłowy numer pesel!');
validationResult = false;
return false;
}

var birthDateRegex = /u[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]/;

if (memberBirthDate != null && memberBirthDate.length > 0 && (memberBirthDate.length != 11 || !memberBirthDate.match(birthDateRegex))) {
swal('Podaj datę w formacie "u0000-00-00"!');
validationResult = false;
return false;
}

if (validationResult == false) {
return false;
}
});
}

//Sprawdzenie czy dodany został skan umowy
if($('#user_scan')[0].files.length == 0 && medicalScanIsSet == false) {
swal('Dodaj skan umowy pakietu medycznego w formacie PDF!');
validationResult = false;
return false;
}

if ($('#user_scan')[0].files[0].size > 5242880) {
swal('Rozmiar pliku przekracza 2 MB!');
validationResult = false;
return false;
}

if ($('#user_scan')[0].files[0].type != 'application/pdf') {
swal('Wymagany format pliku to PDF!');
validationResult = false;
return false;
}

}

if (validationResult == false) {
e.preventDefault();
return false;
}