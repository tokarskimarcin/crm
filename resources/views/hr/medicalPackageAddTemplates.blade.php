<script>
    $(document).ready(function() {
        /******* Obsługa  pakietu medycznego ****/
//ilość członków w przypadku pakietu rodzinnego
        var members_counter = 0;

    //Wyzerowanie
    function clearPackages() {
        $('#new_members div').remove();
        $('#new_members').fadeOut(0);
        $('#family_selected').fadeOut(0);
        $('#partner_selected').fadeOut(0);
        $('#partner_selected div').remove();
        $('#old_members').remove();
    }

    //Podmiana dodatkowych opcji
    $('#package_variable').change(function(e) {
        var package_variable = $('#package_variable').val();

        if (package_variable == 'Wybierz') {
            clearPackages();
            totalMemberCounter(0, 0);
        } else if (package_variable == 'RODZINNY') {
            clearPackages();
            totalMemberCounter(1, 1);
            $('#new_members').fadeIn(0);
            $('#family_selected').fadeIn(0);
        } else if (package_variable == 'PARTNERSKI') {
            clearPackages();
            $('#partner_selected').fadeIn(0);
            totalMemberCounter(1, 2);
            addPartnerTemplate();
        } else if (package_variable == 'INDYWIDUALNY') {
            clearPackages();
            totalMemberCounter(1, 1);
        }
    });

    //Templatka zawierająca partnera
    function addPartnerTemplate() {
        var partnerTemplate = `
            <div class="row">
                <div class="col-md-12">
                    <h3 style="color: #aaa">Dane osobowe partnera:</h3>
                </div>
            </div>
            <div class="row">
                <input type="hidden" value="0" name="medical_id[]" />
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Imie:</label>
                        <input type="text" class="form-control" name="user_first_name[]" placeholder="Imie"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nazwisko:</label>
                        <input type="text" class="form-control" name="user_last_name[]" placeholder="Nazwisko"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Numer telefonu:</label>
                        <input type="number" class="form-control" placeholder="000" id="phone_number" name="phone_number[]">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>PESEL:</label>
                        <input type="number" placeholder="00000000000" class="form-control" name="pesel[]"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Data urodzenia: *<span style="font-size: 10px">W przypadku braku PESEL (format u000-00-00).</span></label>
                        <input type="text" placeholder="u0000-00-00" class="form-control" name="birth_date[]"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Miejscowość:</label>
                        <input type="text" class="form-control" placeholder="Miejscowość" name="city[]">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kod pocztowy:</label>
                        <input type="text" class="form-control" placeholder="00-000" name="postal_code[]">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Ulica:</label>
                        <input type="text" class="form-control" placeholder="Ulica" name="street[]">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Numer domu:</label>
                        <input type="number" class="form-control" placeholder="000" name="house_number[]">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Numer mieszkania:</label>
                        <input type="number" class="form-control" placeholder="000" name="flat_number[]">
                    </div>
                </div>
            </div>
            `;
        $('#partner_selected').append(partnerTemplate);
    }

    //Funkcja dodająca templatkę z kolejnym członkiem
    $('#add_family_member').click((e) => {
        e.preventDefault();
    members_counter++;
    totalMemberCounter(1);

    var new_member_template = `
            <div class="check_for_family" id="member${members_counter}">
                <div class="row">
                    <hr>
                    <div class="col-md-6">
                        <h3>Dane osobowe członka rodziny:</h3>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-danger pull-right" style="margin-top: 15px" type="button" onclick="deleteMember(${members_counter})">
                            <span class="glyphicon glyphicon-minus"></span> Usuń członka
                        </button>
                    </div>
                </div>
                <div class="row">
                <input type="hidden" value="0" name="medical_id[]" />
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Imie:</label>
                            <input type="text" class="form-control" name="user_first_name[]" placeholder="Imie"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nazwisko:</label>
                            <input type="text" class="form-control" name="user_last_name[]" placeholder="Nazwisko"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Numer telefonu:</label>
                            <input type="number" class="form-control" placeholder="000" id="phone_number" name="phone_number[]">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>PESEL:</label>
                            <input type="number" placeholder="00000000000" class="form-control" name="pesel[]"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Data urodzenia: *<span style="font-size: 10px">W przypadku braku PESEL (format u000-00-00).</span></label>
                            <input type="text" placeholder="u0000-00-00" class="form-control" name="birth_date[]"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Miejscowość:</label>
                            <input type="text" class="form-control" placeholder="Miejscowość" name="city[]">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kod pocztowy:</label>
                            <input type="text" class="form-control" placeholder="00-000" name="postal_code[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ulica:</label>
                            <input type="text" class="form-control" placeholder="Ulica" name="street[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Numer domu:</label>
                            <input type="number" class="form-control" placeholder="000" name="house_number[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Numer mieszkania:</label>
                            <input type="number" class="form-control" placeholder="000" name="flat_number[]">
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#new_members').append(new_member_template);
        });
    });
</script>
