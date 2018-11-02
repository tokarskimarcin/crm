<div id="home" class="tab-pane fade">
    <h4>Legenda</h4>
    <div class="alert alert-info">
        <ul class="list-group">
            @if(in_array($user, $adminPanelAccessArr))
            <li class="list-group-item">
                <b>Kategorie</b> - zakładka pozwalająca zarządzać kategoriami.<br>
                W tej zakładce można dodawać/edytować/usuwać/włączać/wyłączać dostępne w programie kategorie. <br>
                Podczas dodawania/edycji można, ale <u>nie trzeba</u> wgrać własny obrazek reprezentujący daną kategorię. <br>
                Aby umieścić kategorię, na stronie głównej podczas <i>dodawania lub edycji</i>, należy wybrać z listy rozwijanej <u>Kategoria</u> wartość - <u>Główna kategoria</u> <br>
                Aby umieścić kategorię, jako podkategoria do istniejącej kategori podczas <i>dodawania lub edycji</i>, należy wybrać z listy rozwijanej <u>Kategoria</u> nazwę kategorii <br>
            </li>
            @endif

            <li class="list-group-item">
                @if(in_array($user, $adminPanelAccessArr))
                    <b>Rozmowy</b> - zakładka pozwalająca zarządzać rozmowami. <br>
                    W tej zakładce, można dodawać/edytować/usuwać/włączać/wyłączać dostępne w programie rozmowy, które wszyscy będą widzieć. <br>
                    Każda rozmowa, musi mieć kategorię do której zostanie przypisana. <br>
                    Jeśli rozmowa ma zostać tymczasowo <u>wyłączona</u>(niewidoczna) należy nacisnąć przycisk <button class=" btn btn-warning">Wyłącz</button> <br>
                    Jeśli rozmowa ma zostać ponownie <u>włączona</u>(wszędzie widoczna) należy nacisnać przycisk  <button class="btn btn-success">Włącz </button>
                @else
                    <b>Rozmowy</b> - zakładka pozwalająca zarządzać rozmowami tymczasowymi. <br>
                    W tej zakładce, można dodawać/edytować/usuwać/włączać/wyłączać wgrane przez użytkownika rozmowy tymczasowe, które zostaną usunięte po 3 dniach od wgrania. <br>
                    Każda rozmowa tymczasowa, znajduje się w kategorii "<u>Własne pliki</u>". <br>
                    Jeśli rozmowa ma zostać tymczasowo <u>wyłączona</u>(niewidoczna) należy nacisnąć przycisk <button class=" btn btn-warning">Wyłącz</button> <br>
                    Jeśli rozmowa ma zostać ponownie <u>włączona</u>(wszędzie widoczna) należy nacisnać przycisk  <button class="btn btn-success">Włącz </button>
                @endif
            </li>

            <li class="list-group-item">
                @if(in_array($user, $adminPanelAccessArr))
                    <b>Playlisty</b> - zakładka pozwalająca zarządzać wszystkimi playlistami. <br>
                    W tej zakładce, można edytować/usuwać/dodawać dostępne w programie playlisty, również innych użytkowników. <br>
                    Aby podejżeć elementy playlisty, należy <u>nacisnąć na wiersz</u> w tabeli, z daną playlistą. Następnie w drugiej tabeli pojawią się rozmowy z tej playlisty. <br>
                    Podczas dodawania/edycji można, ale <u>nie trzeba</u> wgrać własny obrazek reprezentujący playlistę w zakładce <b>Playlista</b>.
                @else
                    <b>Playlisty</b> - zakładka pozwalająca zarządzać własnymi playlistami. <br>
                    W tej zakładce, można edytować/usuwać/dodawać własne playlisty <br>
                    Aby podejżeć elementy playlisty, należy <u>nacisnąć na wiersz</u> w tabeli, z daną playlistą. Następnie w drugiej tabeli pojawią się rozmowy z tej playlisty. <br>
                    Podczas dodawania/edycji można, ale <u>nie trzeba</u> wgrać własny obrazek reprezentujący playlistę w zakładce <b>Playlista</b>.
                @endif

            </li>
        </ul>
    </div>
</div>