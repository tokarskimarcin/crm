
    function CreateHotelInfo(placeToAppend, hotelId) {
        this.placeToAppend = '.' + placeToAppend;
        this.hotelId = hotelId;
        this.DOMTree = null;
        this.createDOMContent = function() {

            //***************First row****************
            let firstRow = document.createElement('div');
            firstRow.classList.add('row');

            let firstRow_FirstCol = document.createElement('div');
            firstRow_FirstCol.classList.add('col-md-4');

            let firstRow_FirstCol_FormGroup = document.createElement('div');
            firstRow_FirstCol_FormGroup.classList.add('form-group');

            let firstRow_FirstCol_FormGroup_Label = document.createElement('label');
            firstRow_FirstCol_FormGroup_Label.setAttribute('for', 'name');
            firstRow_FirstCol_FormGroup_Label.textContent = 'Nazwa Hotelu';

            let firstRow_FirstCol_FormGroup_Input = document.createElement('input');
            firstRow_FirstCol_FormGroup_Input.setAttribute('type', 'text')
            firstRow_FirstCol_FormGroup_Input.setAttribute('disabled', 'true')
            firstRow_FirstCol_FormGroup_Input.id = 'name';

            firstRow_FirstCol_FormGroup.appendChild(firstRow_FirstCol_FormGroup_Label);
            firstRow_FirstCol_FormGroup.appendChild(firstRow_FirstCol_FormGroup_Input);
            firstRow_FirstCol.appendChild(firstRow_FirstCol_FormGroup);


            let firstRow_SecondCol = document.createElement('div');
            firstRow_SecondCol.classList.add('col-md-4');

            let firstRow_SecondCol_FormGroup = document.createElement('div');
            firstRow_SecondCol_FormGroup.classList.add('form-group');

            let firstRow_SecondCol_FormGroup_Label = document.createElement('label');
            firstRow_SecondCol_FormGroup_Label.setAttribute('for', 'name');
            firstRow_SecondCol_FormGroup_Label.textContent = 'Województwo';

            let firstRow_SecondCol_FormGroup_Select = document.createElement('select');

            let firstRow_SecondCol_formGroup_Select_BasicElement = document.createElement('option');
            firstRow_SecondCol_formGroup_Select_BasicElement.value = '0';
            firstRow_SecondCol_formGroup_Select_BasicElement.textContent = 'Wybierz';

            // firstRow_SecondCol_FormGroup_Select.add(firstRow_SecondCol_formGroup_Select_BasicElement);
            firstRow_SecondCol_FormGroup_Select.appendChild(firstRow_SecondCol_formGroup_Select_BasicElement);

            voivodes.forEach(voivode => {
                var firstRow_SecondCol_formGroup_Select_RegularOption = document.createElement('option');
                firstRow_SecondCol_formGroup_Select_RegularOption.value = voivode.id;
                firstRow_SecondCol_formGroup_Select_RegularOption.textContent = voivode.name;
                firstRow_SecondCol_FormGroup_Select.appendChild(firstRow_SecondCol_formGroup_Select_RegularOption);
            });

            firstRow.appendChild(firstRow_FirstCol);



            firstRow.appendChild(firstRow_SecondCol);
            //**************END FIRST ROW**************

            let firstRowThirdCol = document.createElement('div');
            firstRowThirdCol.classList.add('col-md-4');

            this.DOMTree = firstRow;
        }
        this.getDOMElement = function() {
            return this.DOMTree;
        }
}