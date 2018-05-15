<style>
    .routes-wrapper {
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .routes-container {
        border: 1px solid lightgray;
        background-color: white;
        padding: 2em;

        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 90%;
        max-width: 90%;
    }

    header {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
    }




</style>


<div class="row">
    <div class="col-lg-12">
        <input type="button" class="btn btn-info" id="add_new_route" value="Dodaj nową trasę" style="width:100%;margin:1em;">
    </div>
</div>
<div class="routes-wrapper">
    <div class="routes-container">

        <header>Trasa #1</header>

        <section>
            <div class="form-group">
                <label for="woj">Województwo</label>
                <select name="" id="woj" class="form-control">
                    <option value="0">Wybierz</option>
                    <option value="1">Lubelskie</option>
                    <option value="2">Mazowieckie</option>
                </select>
            </div>

            <div class="form-group">
                <label for="city">Miasto</label>
                <select name="" id="city" class="form-control">
                    <option value="0">Wybierz</option>
                    <option value="1">Lublin</option>
                    <option value="2">Świdnik</option>
                </select>
            </div>

            <div class="form-group">
                <label for="karencja">Karencja</label>
                <select name="" id="karencja" class="form-control">
                    <option>Wybierz</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Data:</label>
                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                    <input class="form-control" name="start_date" type="text">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
            </div>

        </section>
    </div>
</div>

