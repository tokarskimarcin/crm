
<?php include('views/v_start.php'); ?>
<!-- Header -->
<?php include('views/v_menu.php'); ?>

<style>
.edit{
	background-color: #FFFFFF;
	border:0;
}
.edit:hover{
	color:#a7a7a7;
}
.edit:active{
	border:0;
}
.edit-name{
	display:none;
}
.edit-surname{
	display:none;
}
.edit-doc{
	display:none;
}
.edit-stu{
	display:none;
}
.edit-statuswork{
	display:none;
}
.edit-phonework{
	display:none;
}
.edit-phonepriv{
	display:none;
}
.edit-datework{
	display:none;
}
.edit-money{
	display:none;
}
.edit-addmoney{
	display:none;
}
</style>

<!-- Main -->
<div class="container">
	<div class="row">
    	<!-- center left-->	
		<div class="col-md-12">
			<h1 style ="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:40px;"><?php foreach ($_GET as $key => $value){$numcard = $key;}echo $_SESSION['namelink'][$numcard];?></h1>
			<hr>
			<!-- <div class="alert alert-danger" role="alert">Niestety ta funkcja nie jest jeszcze dostępna.</div> -->
			<div class="panel panel-info">
				<div class="panel-heading">
            	  <h3 class="panel-title">Profil Pracownika</h3>
            	</div>
            	<div class="panel-body">
            		<!-- <div class="col-md-2 col-lg-2 " align="center"> <img alt="User Pic" src="http://saintgeorgelaw.com/wp-content/uploads/2015/01/male-formal-business-hi.png" class="img-circle img-responsive" style="border:2px solid #222;"> </div> -->
            		<div class="col-md-2 col-lg-2 " align="center"> <img alt="User Pic" src="res/profile_images/standard.jpg" class="img-circle img-responsive" style="border:2px solid #222;"> </div>
            		<div class="col-md-10">
            			
            			<div class=" col-md-7 col-lg-7 "> 
		                  <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Dane Osobowe</b>
		                      <tr>
		                        <td style="width: 170px;height:52px;"><b>Imię:</b></td>
		                        <td>
															<p class="name">Wojciech</p>
															<input class="form-control edit-name" type="text" value="Wojciech">
														</td>
														<td>
															<button class="edit name" id="edit-name-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-name edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 170px;height:52px;"><b>Nazwisko:</b></td>
		                        <td>
															<p class="surname">Mazur</p>
															<input class="form-control edit-surname" type="text" value="Mazur">
														</td>
														<td>
															<button class="edit surname" id="edit-surname-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-surname edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 170px;height:52px;"><b>Login/E-Mail:</b></td>
		                        <td>wojciech.mazur@veronaconsulting.pl</td>
														<td></td>
		                      </tr>
		                  	  <tr>
		                        <td style="width: 170px;height:52px;"><b>Dokumenty:</b></td>
		                        <td>
															<p class="doc">Tak/<s>Nie</s></p>
															<select class="form-control edit-doc">
																<option value="1">Tak</option>
																<option value="0">Nie</option>
															</select>
														</td>
														<td>
															<button class="edit doc" id="edit-doc-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-doc edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 170px;height:52px;"><b>Student:</b></td>
		                        <td>
															<p class="stu">Tak/<s>Nie</s></p>
															<select class="form-control edit-stu">
																<option value="1">Tak</option>
																<option value="0">Nie</option>
															</select>
														</td>
														<td>
															<button class="edit stu" id="edit-stu-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-stu edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
													<tr>
		                        <td style="width: 170px;height:52px;"><b>Pracuje:</b></td>
		                        <td>
															<p class="statuswork">Tak/<s>Nie</s></p>
															<select class="form-control edit-statuswork">
																<option value="1">Tak</option>
																<option value="0">Nie</option>
															</select>
														</td>
														<td>
															<button class="edit statuswork" id="edit-statuswork-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-statuswork edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 170px;height:52px;"><b>Telefon Służbowy:</b></td>
		                        <td>
															<p class="phonework">505449849</p>
															<input class="form-control edit-phonework" type="text" value="505449849">
														</td>
														<td>
															<button class="edit phonework" id="edit-phonework-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-phonework edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
													<tr>
		                        <td style="width: 170px;height:52px;"><b>Telefon Prywatny:</b></td>
		                        <td>
															<p class="phonepriv">570193239</p>
															<input class="form-control edit-phonepriv" type="text" value="570193239">
														</td>
														<td>
															<button class="edit phonepriv" id="edit-phonepriv-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-phonepriv edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
													<tr>
		                        <td style="width: 170px;height:52px;"><b>Data Roz. i Zak. Pracy:</b></td>
		                        <td>
															<p class="datework">2017-02-01 <span class="glyphicon glyphicon-resize-horizontal"></span> 2017-03-25</p>
															<input class="form-control edit-datework" placeholder="RRRR-MM-DD" style="width:50%; float:left" type="text" value="2017-02-01">
															<input class="form-control edit-datework" placeholder="RRRR-MM-DD" style="width:50%;" type="text" value="">
														</td>
														<td>
															<button class="edit datework" id="edit-datework-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-datework edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
		                      </tr>
		                     
		                    </tbody>
		                  </table>
		                </div>
		                <div class=" col-md-5 col-lg-5 "> 
		                  <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Wynagrodzenia</b>
		                      <tr style="width: 170px;height:52px;">
		                        <td style="width: 150px;"><b>Staż Pracy:</b></td>
		                        <td>2 miesiące <span style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">(53 dni)</span></td>
		                      	<td></td>
													</tr>
		                      <tr style="width: 170px;height:52px;">
		                        <td><b>Wynagrodzenie</b></td>
		                        <td>
															<span class="money" style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">2500zł</span>
															<input class="form-control edit-money" type="number" value="2500">
														</td>
		                      	<td>
															<button class="edit money" id="edit-money-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-money edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
													</tr>
		                      <tr style="width: 170px;height:52px;">
		                        <td><b>Dodatek Służbowy</b></td>
		                        <td>
															<span class="addmoney" style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">1000zł</span>
															<input class="form-control edit-addmoney" type="number" value="1000">
														</td>
		                      	<td>
															<button class="edit addmoney" id="edit-addmoney-button"><span class="glyphicon glyphicon-pencil"></span></button>
															<form method="POST"><button class="edit-addmoney edit"><span class="glyphicon glyphicon-save"></span></button></form>
														</td>
													</tr>
		                      <tr style="width: 170px;height:52px;">
		                        <td><b>Suma do wypłaty</b></td>
		                        <td><span style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">3500zł</span></td>
		                      	<td></td>
													</tr>
		                  	  <tr style="width: 170px;height:52px;">
		                        <td><b>Premia (Kwiecień)</b></td>
		                        <td><span style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">Brak</span></td>
		                      	<td></td>
													</tr>
		                      <tr style="width: 170px;height:52px;">
		                        <td><b>Premia (Marzec)</b></td>
		                        <td><span style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">50zł</span></td>
		                      	<td></td>
													</tr>
		                      <tr style="width: 170px;height:52px;">
		                        <td><b>Premia (Luty)</b></td>
		                        <td><span style="background-color: #d9edf7; padding: 4px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">50zł</span></td>
		                      	<td></td>
													</tr>
		                     
		                    </tbody>
		                  </table>
		                </div>
		                <div class=" col-md-7 col-lg-7 "> 
		                  <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Kary i Premie</b>
		                      <tr>
		                        <td style="width: 10px;"><b>Lp.</b></td>
		                        <td><b>Data</b></td>
		                        <td><b>Kara/Premia</b></td>
		                        <td><b>Dodał</b></td>
		                        <td><b>Powód</b></td>
		                        <td></td>
		                      </tr>

		                      <?php for ($i=1; $i < 3; $i++) { ?>
		                      <tr>
		                        <td style="width: 10px;"><b><?php echo $i; ?></b></td>
		                        <td nowrap="nowrap">2017-05-0<?php echo $i; ?></td>
		                        <td nowrap="nowrap"><span style="background-color: #ff7b7b; padding: 4px 10px;border-radius: 5px;border:1px solid #ff6a6a; color:#7f2222;">Kara: -50zł</span></td>
		                        <td nowrap="nowrap"><span style="background-color: #d9edf7; padding: 4px 10px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">Paweł Zieliński</span></td>
		                        <td>Brak powodu</td>
		                        <td><a type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash" style="height: 20px; padding-top:3px; padding-left: 0px;" data-toggle="modal" data-target="#karta_oceny"></i></a></td>
		                      </tr>
		                      <?php } ?>
		                      <?php for ($i=3; $i < 6; $i++) { ?>
		                      <tr>
		                        <td style="width: 10px;"><b><?php echo $i; ?></b></td>
		                        <td nowrap="nowrap">2017-05-0<?php echo $i; ?></td>
		                        <td nowrap="nowrap"><span style="background-color: #70ff5c; padding: 4px 10px;border-radius: 5px;border:1px solid #33ff36; color:#4b5c44;">Premia: 100zł</span></td>
		                        <td nowrap="nowrap"><span style="background-color: #d9edf7; padding: 4px 10px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">Paweł Zieliński</span></td>
		                        <td>Brak powodu</td>
		                        <td><a type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash" style="height: 20px; padding-top:3px; padding-left: 0px;" data-toggle="modal" data-target="#karta_oceny"></i></a></td>
		                      </tr>
		                      <?php } ?>
							  <tr>
		                        <td colspan="2"><select class="form-control">
		                        	<option value="0">Wybierz</option>
		                        	<option value="1">Kara</option>
		                        	<option value="2">Premia</option>
		                        </select></td>
		                        <td><input type="text" placeholder="Kwota" class="form-control"></td>
		                        <td colspan="2"><input type="text" placeholder="Powód" class="form-control"></td>
		                        <td><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-floppy-disk" style="padding-top: 3px; padding-left: 2px;" data-toggle="modal" data-target="#karta_oceny"></i></a></td>
		                      </tr>
		                     
		                    </tbody>
		                  </table>

		                  <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Wizyty Trenera/Koordynatora Głównego</b>
		                      <tr>
		                        <td style="width: 10px;"><b>Lp.</b></td>
		                        <td><b>Data.</b></td>
		                        <td><b>Osoba.</b></td>
		                        <td><b>Ocena.</b></td>
		                        <td style="width: 10px;"></td>
		                      </tr>
		                      <?php for ($i=1; $i < 10; $i++) { ?>
		                      <tr>
		                        <td><b><?php echo $i; ?>.</b></td>
		                        <td>2017-02-0<?php echo $i; ?></td>
		                        <td>Daniel Abramowicz</td>
		                        <td><span style="background-color: #d9edf7; padding: 4px 10px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">3 / 5</span></td>
		                        <td><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-signal" data-toggle="modal" data-target="#karta_oceny"></i></a></td>
		                      </tr>
		                      <?php } ?>

		                     
		                    </tbody>
		                  </table>
		                  
		                </div>
		                <div class=" col-md-5 col-lg-5 "> 
		                  <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Posiadany Sprzęt</b>
		                      <tr>
		                        <td style="width: 10px;"><b>Lp.</b></td>
		                        <td><b>Data Wyd.</b></td>
		                        <td><b>Sprzęt</b></td>
		                        <td style="width: 10px;"></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 10px;"><b>1.</td>
		                        <td>2017-02-01</td>
		                        <td>Laptop</td>
		                        <td style="width: 10px;"><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#laptop"></i></a></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 10px;"><b>2.</td>
		                        <td>2017-02-01</td>
		                        <td>Telefon</td>
		                        <td style="width: 10px;"><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#telefon"></i></a></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 10px;"><b>2.</td>
		                        <td>2017-02-01</td>
		                        <td>Karta SIM</td>
		                        <td style="width: 10px;"><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#sim"></i></a></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 10px;"><b>3.</td>
		                        <td>2017-02-01</td>
		                        <td>Monitor</td>
		                        <td style="width: 10px;"><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#monitor"></i></a></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 10px;"><b>4.</td>
		                        <td>2017-02-01</td>
		                        <td>Monitor</td>
		                        <td style="width: 10px;"><a type="button" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-info-sign" data-toggle="modal" data-target="#monitor"></i></a></td>
		                      </tr>
		                     
		                    </tbody>
		                  </table>
		                </div>
		                <div class=" col-md-12 col-lg-12 ">
		                <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Komentarz</b>
		                      <tr>
		                        <td style="width: 10px;"><b>Lp.</b></td>
		                        <td style="width: 100px;text-align: center;"><b>Data.</b></td>
		                        <td><b>Komentarz.</b></td>
		                        <td style="text-align: center;"><b>Osoba.</b></td>
		                      </tr>
		                      <?php for ($i=1; $i < 10; $i++) { ?>
		                      <tr>
		                        <td><b><?php echo $i; ?>.</b></td>
		                        <td style="text-align: center;">2017-02-0<?php echo $i; ?> 12:55</td>
		                        <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae aliquid adipisci iste eligendi, sapiente tempora similique quia magnam saepe explicabo ducimus praesentium ipsa. Error accusamus, facere rem numquam quod consectetur.</td>
		                        <td style="text-align: center;" nowrap="nowrap"><span style="background-color: #d9edf7; padding: 4px 10px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">Paweł Zieliński</span></td>
		                      </tr>
		                      <?php } ?>
							<tr>
		                        <td style="width: 80%;" colspan="3"><textarea class="form-control" style="width: 100%; max-width: 100%;"></textarea></td>
		                        <td style="text-align: center;"><input type="submit" style="width: 100%;" value="Zapisz" class="btn btn-info"></td>
		                      </tr>
		                     
		                    </tbody>
		                </table>
		                </div>
            		</div>

            	</div>
            	<div class="panel-footer">
                        <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope" data-toggle="modal" data-target="#komunikat"></i></a>
                        <span class="pull-right">
                            <a type="button" class="btn btn-sm btn-warning" ><i class="glyphicon glyphicon-edit"></i></a>
                        </span>
                    </div>
			</div>


<!-- Modal -->
<div class="modal fade" id="karta_oceny" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div style="width: 90%; margin:0 auto; margin-top:30px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Karta oceny Trenera</h4>
      </div>
      <div class="modal-body">
       					<table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">KARTA OCENY TRENERA</b>
		                      <tr>
		                        <td><b>Data audytu:</b></td>
		                        <td>2017-03-04</td>	
		                        <td style="width: 170px;"><b>Data weryfikacji:</b></td>
		                        <td>2017-03-10</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 170px;"><b>Imię Nazwisko Lidera:</b></td>
		                        <td colspan="3">Wojciech Mazur</td>	
		                      </tr>
		                      <tr>
		                        <td><b>Filia:</b></td>
		                        <td colspan="3">Radom</td>	
		                      </tr>
		                    </tbody>
		                  </table>
		                  <table class="table table-user-information">
		                    <tbody>
		                      <tr>
		                        <td style="width: 250px; text-align:center;"><b>Charakterystyka Złej Pracy</b></td>
		                        <td style="text-align:center;"><b>Progress</b></td>	
		                        <td style="width: 250px; text-align:center;"><b>Charakterystyka dobrej Pracy</b></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nie planuje dnia / brak prowadzonego kalendarza</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
								    <b>1 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">samodzielnie planuje dzień pilnuje terminów</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nieudolny coaching / brak pytań naprowadzających</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
								    <b>3 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">wzorowy coaching</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">fatalna odprawa prowadząca do "niczego"</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
								    <b>2 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">wzorowa motywująca odprawa</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nieefektywna  inf. Zwrotna </td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
								    <b>5 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">inf. Zwrotna zgodna z poznanymi technikami</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">brak prowadzenia tabeli postepów</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
								    <b>4 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">wzorowo wypełniona tabela postepów</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nieefktywne nieopłacalne konkursy</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
								    <b>3 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">wzorowe konkursy ciągłe pomysły na nowe rozwiązania</td>
		                      </tr>
		                      
		                      <tr>
		                        <td style="width: 250px; text-align:center;">brak wiedzy o programie (gmail, operacyjny)</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
								    <b>5 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">znakomita obsługa aplikacjii komputerowych</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">praca na Sali(niezauważalny, niepotrzebny nie wnosi nic do pracy konsultantów)</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
								    <b>3 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">potrzebny, od razu podnosi efektywność, dba o atmosferę pilnując dyscypliny</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nie prowadzi szkoleń lub nieudolne szkolenie wstępne pracownicy wymagają doszkolenia</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
								    <b>1 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">szybkie efektywne szkolenie. Pracownicy są przygotowani pod względem jakości i ilości</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nieustannie popełnia błedy</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
								    <b>2 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">nie popełnia błedów</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">pracuje tylko w trybie zadaniowym</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
								    <b>3 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">wie co trzeba zrobić rozdziela zadania</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">brak inicjatywy</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
								    <b>1 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">ciągle ma nowe pomysły</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">nie wykona więcej obowiązków</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
								    <b>4 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">jest w stanie wziąć na siebie dużo więcej</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">jest toksyczny w grupie</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
								    <b>2 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">jest dobrym kolegą, ma autorytet</td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">wymaga nieustannej kontroli</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
								    <b>1 / 5</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">godny zufania, przejmie odpowiedzialność</td>
		                      </tr>
		                      <tr>
		                        <td style="text-align:center;" colspan="3"><b>Ocena Ogólna</b></td>
		                      </tr>
		                      <tr>
		                        <td style="width: 250px; text-align:center;">Bradzo Źle</td>
		                        <td>
								<div class="progress">
								  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 54.66%;">
								    <b>41 / 75</b>
								  </div>
								</div>
		                        </td>	
		                        <td style="width: 250px; text-align:center;">Bardzo Dobrze</td>
		                      </tr>
		                      <tr>
		                        <td style="text-align:center;" colspan="3"><b>UWAGI (Nad czym będę pracować)</b></td>
		                      </tr>
		                      <tr>
		                        <td style="text-align:center;" colspan="3">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae quo repellat hic dicta dignissimos sapiente earum nesciunt omnis dolorum voluptatibus dolor, obcaecati dolorem? Iusto unde velit facilis, reiciendis, consequuntur quae.</td>
		                      </tr>
		                    </tbody>
		                  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
      </div>
    </div>
  </div>
</div>
<!-- Laptop -->
<div class="modal fade" id="laptop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div style="width:370px; margin:0 auto; background-color:#fff; border-radius:6px;border: 1px solid rgba(0,0,0,.2);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informacje o sprzęcie Służbowym</h4>
      </div>
      <div class="modal-body">
       				 <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Laptop</b>
		                      <tr>
		                        <td style="width: 100px;"><b>Data Wydania</b></td>
		                        <td style="width: 10px;">2017-03-04</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Model</b></td>
		                        <td style="width: 10px;">MSI CX70</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Numer seryjny</b></td>
		                        <td style="width: 10px;">FWE186WE418CWE1</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Procesor</b></td>
		                        <td style="width: 10px;">Intel Core i7</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Pamięć Ram</b></td>
		                        <td style="width: 10px;">8GB DDR3(PC3)</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Dysk</b></td>
		                        <td style="width: 10px;">1000GB</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Modyfikacje</b></td>
		                        <td style="width: 10px;">Rozszerzenie Ram 8GB</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Uwagi</b></td>
		                        <td style="width: 10px;">Brak</td>	
		                      </tr>
		                    </tbody>
		                  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
      </div>
    </div>
  </div>
</div>
<!-- Telefon -->
<div class="modal fade" id="telefon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div style="width:370px; margin:0 auto; background-color:#fff; border-radius:6px;border: 1px solid rgba(0,0,0,.2);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informacje o sprzęcie Służbowym</h4>
      </div>
      <div class="modal-body">
       				 <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Telefon</b>
		                      <tr>
		                        <td style="width: 100px;"><b>Data Wydania</b></td>
		                        <td style="width: 10px;">2017-03-04</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Model</b></td>
		                        <td style="width: 10px;">Samsung S5611</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Numer IMEI</b></td>
		                        <td style="width: 10px;">58916416486486324168</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Ładowarka</b></td>
		                        <td style="width: 10px;">TAK / <s>NIE</s></td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Pudełko</b></td>
		                        <td style="width: 10px;">TAK / <s>NIE</s></td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Uwagi</b></td>
		                        <td style="width: 10px;">Brak</td>	
		                      </tr>
		                    </tbody>
		                  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
      </div>
    </div>
  </div>
</div>
<!-- Monitor -->
<div class="modal fade" id="monitor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div style="width:370px; margin:0 auto; background-color:#fff; border-radius:6px;border: 1px solid rgba(0,0,0,.2);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informacje o sprzęcie Służbowym</h4>
      </div>
      <div class="modal-body">
       				 <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Monitor</b>
		                      <tr>
		                        <td style="width: 100px;"><b>Data Wydania</b></td>
		                        <td style="width: 10px;">2017-03-04</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Model</b></td>
		                        <td style="width: 10px;">Dell F550</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Numer Seryjny</b></td>
		                        <td style="width: 10px;">VSD1V5SD1618513VSD13</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Kabel Sygnałowy</b></td>
		                        <td style="width: 10px;">TAK / <s>NIE</s></td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Kabel Zasilający</b></td>
		                        <td style="width: 10px;">TAK / <s>NIE</s></td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Uwagi</b></td>
		                        <td style="width: 10px;">Brak</td>	
		                      </tr>
		                    </tbody>
		                  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
      </div>
    </div>
  </div>
</div>
<!-- Monitor -->
<div class="modal fade" id="sim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div style="width:370px; margin:0 auto; background-color:#fff; border-radius:6px;border: 1px solid rgba(0,0,0,.2);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informacje o sprzęcie Służbowym</h4>
      </div>
      <div class="modal-body">
       				 <table class="table table-user-information">
		                    <tbody>
		                    <b style="font-size: 20px; font-family: sans-serif;">Karta SIM</b>
		                      <tr>
		                        <td style="width: 100px;"><b>Data Wydania</b></td>
		                        <td style="width: 10px;">2017-03-04</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Typ</b></td>
		                        <td style="width: 10px;">Abonament Orange</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Numer Telefonu</b></td>
		                        <td style="width: 10px;">505449849</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>PIN</b></td>
		                        <td style="width: 10px;">1111</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>PUK</b></td>
		                        <td style="width: 10px;">1234567898</td>	
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Internet</b></td>
		                        <td style="width: 10px;">TAK(10GB) / <s>NIE</s></td>		
		                      </tr>
		                      <tr>
		                        <td style="width: 100px;"><b>Uwagi</b></td>
		                        <td style="width: 10px;">Brak</td>	
		                      </tr>
		                    </tbody>
		                  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="komunikat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div style="width:370px; margin:0 auto; background-color:#fff; border-radius:6px;border: 1px solid rgba(0,0,0,.2);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Wyświetl komunikat</h4>
      </div>
      <div class="modal-body">
       	<form action="">
       		<label for="">Treść</label>
       		<textarea class="form-control" style="max-width: 100%;"></textarea>
       		<label for="">Data wygaśnięcia</label>
       		<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
				<input class="form-control" name="addpbdate" type="text" value="<?php if (isset($_POST['addpbdate'])){ echo $_POST['addpbdate']; }else{ echo date("Y-m-d"); }  ?>" readonly >
				<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
			</div>
			<label for="">Ważność</label>
			<select class="form-control">
				<option value="">Wybierz</option>
				<option value="">Info</option>
				<option value="">Warning</option>
				<option value="">Primary</option>
				<option value="">Danger</option>
			</select>
       	</form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
        <button type="button" class="btn btn-info">Wyślij wiadomość</button>
      </div>
    </div>
  </div>
</div>
			

		</div>
	</div>
</div>
<!--/container-->
<!-- /Main -->
<div class="modal">
	
</div>
<?php include('views/v_minialert.php'); ?>
<?php include('views/v_footer.php'); ?>