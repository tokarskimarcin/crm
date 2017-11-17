@extends('layouts.main')

@section('content')
<ul class="nav nav-tabs">
    <li id="menu_computers" class="active menu_item"><a href="#">Komputery</a></li>
    <li id="menu_phones" class="menu_item"><a href="#">Telefony</a></li>
    <li id="menu_printers" class="menu_item"><a href="#">Drukarki</a></li>
    <li id="menu_sim_cards" class="menu_item"><a href="#">Karty sim</a></li>
</ul>

<div id="table_content">

    <!-- //tutaj dodajemy wszystkie tabele -->
    <div id="content_computers" class="table-responsive">
        <table class="table">
          <tr>
              <td>Title 1</td>
              <td>Title 2</td>
              <td>Title 3</td>
              <td>Title 4</td>
              <td>Title 5</td>
          </tr>
            <?php for($i = 1; $i <= 10; $i++){
echo<<<END
            <tr>
                <td>Item 1</td>
                <td>Item 2</td>
                <td>Item 3</td>
                <td>Item 4</td>
                <td>Item 5</td>
            </tr>
END;
}?>
        </table>
    </div>

    <div id="content_phones" class="table-responsive" style="display: none">
        <table class="table">
          <tr>
              <td>Title 1</td>
              <td>Title 2</td>
              <td>Title 3</td>
              <td>Title 4</td>
              <td>Title 5</td>
          </tr>
            <?php for($i = 1; $i <= 5; $i++){
echo<<<END
            <tr>
                <td>Item 1</td>
                <td>Item 2</td>
                <td>Item 3</td>
                <td>Item 4</td>
                <td>Item 5</td>
            </tr>
END;
}?>
        </table>
    </div>

    <div id="content_printers" class="table-responsive" style="display: none">
        <table class="table">
          <tr>
              <td>Title 1</td>
              <td>Title 2</td>
              <td>Title 3</td>
              <td>Title 4</td>
              <td>Title 5</td>
          </tr>
            <?php for($i = 1; $i <= 15; $i++){
echo<<<END
            <tr>
                <td>Item 1</td>
                <td>Item 2</td>
                <td>Item 3</td>
                <td>Item 4</td>
                <td>Item 5</td>
            </tr>
END;
}?>
        </table>
    </div>

</div>

<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
<script>

$(document).ready(function () {

    $('.menu_item').on('click', function(){
        var id = this.id;

        function deletePrevious() {
            $("#menu_computers, #menu_phones, #menu_printers, #menu_sim_cards").removeClass('active');
            $("#content_computers, #content_phones, #content_printers").fadeOut(0);
        }

        if(id == "menu_computers") {
            deletePrevious();
            $("#menu_computers").addClass('active');
            $("#content_computers").fadeIn(0);
        }

        if(id == "menu_phones") {
            deletePrevious();
            $("#menu_phones").addClass('active');
            $("#content_phones").fadeIn(0);
        }

        if(id == "menu_printers") {
            deletePrevious();
            $("#menu_printers").addClass('active');
            $("#content_printers").fadeIn(0);
        }

        if(id == "menu_sim_cards") {
            deletePrevious();
            $("#menu_sim_cards").addClass('active');
        }
    });

});



</script>
@endsection
