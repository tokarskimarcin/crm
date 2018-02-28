<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-info text-center" id="add_medical_package" disabled="true"  title="Opcja dostepna wkrótce" style="width: 100%">
                <span id="span_medical" class="glyphicon glyphicon-plus"></span> <span id="span_medical_text">Dodaj pakiet medyczny</span>
            </button>
        </div>
    </div>
</div>
<div id="add_medical_package_div" style="display: none;">
    <div class="col-md-12 alert alert-info" >
        <div class="col-md-1">
            <span style="font-size: 50px;color: #64beeb" class="glyphicon glyphicon-info-sign"></span>
        </div>
        <div class="col-md-11" style="font-size: 20px;">
            <b>Dodawanie pakietów medycznych:</b>
            <p>
                Dodając pakiet medyczny wybieramy jego początkowy zakres obowiązywania - jest to data rozpoczęcia świadczenia usług medycznych.
                Należy również dodać skan umowy w formacie PDF.
            </p>
        </div>
    </div>
    <input type="hidden" id="medical_package_active" name="medical_package_active" value="0"/>
    <input type="hidden" id="totalMemberSum" name="totalMemberSum" value="0"/>
    <input type="hidden" name="medical_package_is_new" value="1"/>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Pakiet:</label>
                <select class="form-control" name="package_name" id="package_name">
                    <option>Wybierz</option>
                    <option selected>STANDARD</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Wariant:</label>
                <select class="form-control" name="package_variable" id="package_variable">
                    <option>Wybierz</option>
                    <option>INDYWIDUALNY</option>
                    <option>PARTNERSKI</option>
                    <option>RODZINNY</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Dodaj skan umowy:</label>
                <input id="user_scan" name="user_scan" type="file"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Zakres obowiązywania od:</label>
                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                    <input class="form-control" name="medical_start" id="medical_start" type="text" value="{{date("Y-m-d")}}" readonly >
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div id="user_template_div">
        <div class="row">
            <div class="col-md-12">
                <h3 style="color: #aaa">Dane osobowe pracownika:</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Imie:</label>
                    <input type="text" class="form-control" id="tempate_user_first_name" name="user_first_name[]" placeholder="Imie"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nazwisko:</label>
                    <input type="text" class="form-control" id="template_user_last_name" name="user_last_name[]" placeholder="Nazwisko"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Numer telefonu:</label>
                    <input type="number" class="form-control" placeholder="000" id="template_phone_number" name="phone_number[]">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>PESEL:</label>
                    <input type="number" placeholder="00000000000" class="form-control" id="pesel" name="pesel[]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Data urodzenia: *<span style="font-size: 10px">Tylko gdy brak PESEL (format u000-00-00).</span></label>
                    <input type="text" placeholder="u0000-00-00" class="form-control" id="birth_date" name="birth_date[]"/>
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
    <div id="new_members">

    </div>
    <div class="check_for_family" id="partner_selected" style="display: none;">

    </div>
    <div class="row" id="family_selected" style="display: none;">
        <div class="col-md-12">
            <div class="form-group">
                <button class="btn btn-info" style="width: 100%" id="add_family_member">
                    <span class="glyphicon glyphicon-plus"></span> Dodaj członka rodziny
                </button>
            </div>
        </div>
    </div>
</div>
