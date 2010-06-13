<?php include('globals.php'); ?>

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
  <label>
    Tipo de manifestação<br />
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