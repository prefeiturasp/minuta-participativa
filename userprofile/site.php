<?php include('globals.php'); ?>
<style type="text/css">
input[type="text"], select {font-size:24px;width:97%;padding:3px;margin-top:2px;margin-right:6px;margin-bottom:16px;border:1px solid #e5e5e5;background:#fbfbfb;}
select{width:100%; font-size:18px;padding:6px 3px 6px 3px;}
input{color:#555;}
fieldset{border:1px solid #e5e5e5!important; display:block; padding:6px 0px 6px 6px;	margin-bottom: 24px;}
legend{color: #777; font-size: 13px;padding-right:6px; margin-left:-6px;}
</style>
<p>
  <label>
    Nome completo<br />
    <input type="text" name="nomecompleto"
           value="<?php echo $_POST['nomecompleto'] ?>" />
  </label>
</p>

<p>
  <label>CPF<br />
  <input type="text" name="cpf" value="<?php echo $_POST['cpf'] ?>" />
</label>
</p>

<p>
  <label>Estado<br />
  <select name="estado">
    <option></option>
    <?php
      foreach ($estados as $key => $value) {
        if ($_POST['estado'] == $key)
          $selected = 'selected="selected"';
        else
          $selected = "";
        echo "<option value=\"$key\" $selected>$value</option>";
      }
    ?>
  </select>
  </label>
</p>

<p>
  <label>
    Cidade<br />
    <input type="text" name="cidade"
           value="<?php echo $_POST['cidade'] ?>"
    />
  </label>
</p>

<p>
  <label>
    Segmento ou setor de atuação<br />
    <select name="segmento">
      <option></option>
      <?php
        foreach ($segmentos as $key => $value) {
            if ($_POST['segmento'] == $key)
              $selected = 'selected="selected"';
            else
              $selected = "";
          echo "<option value=\"$key\" $selected>$value</option>";
        }
      ?>
    </select>
  </label>
</p>

<p>
<fieldset>
	<legend>Tipo de manifestação</legend>
	<label>
	  <input type="radio" name="manifestacao" value="individual"
	    <?php echo $_POST['manifestacao'] == 'individual' ?
	      'checked="checked"' : '' ?>/>
	  Individual
	</label>
	<label>
	  <input type="radio" name="manifestacao" value="institucional"
	    <?php echo $_POST['manifestacao'] == 'institucional' ?
	      'checked="checked"' : '' ?>/>
	  Institucional
	</label>
</fieldset>
</p>

<p>
  <label>
    Instituição<br />
    <input type="text" name="instituicao"
           value="<?php echo $_POST['instituicao'] ?>"
    />
  </label>
</p>

<p>
  <label>
    <input type="checkbox" name="agreeWithTermsOfUse" />
    Li e concordei com os <a href="<?php bloginfo('url')?>/referencias">
    termos de uso</a> do site
  </label>
</p>