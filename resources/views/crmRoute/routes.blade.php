<style>
    .routes-wrapper {
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .routes-container {
        background-color: white;
        padding: 2em;
        box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
        border: 0;
        border-radius: .1875rem;
        margin: 1em;

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

    .glyphicon-remove {
        font-size: 2em;
        transition: all 0.8s ease-in-out;
        float: right;
        color:red;
    }
    .glyphicon-remove:hover {
        transform: scale(1.2) rotate(180deg);
        cursor: pointer;
    }

    .header {
        text-align: center;
        font-size: 2em;
        font-weight: bold;
        box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
        width: 100%;
        padding-top: 1em;
        padding-bottom: 1em;
    }

</style>
<div class="routes-wrapper">
    @if(Session::has('adnotation'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">{{Session::get('adnotation') }}</div>
            </div>
        </div>
        @php
            Session::forget('adnotation');
        @endphp
    @endif

    <div class="header">
        <span>Nowa trasa</span>
    </div>
</div>

