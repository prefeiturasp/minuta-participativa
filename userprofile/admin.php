<?php include('globals.php'); ?>
<h3>Direito autoral</h3>
<table class="form-table">
  <tr>
    <th><label for="cpf">CPF</label></th>
    <td>
      <input
          type="text" name="cpf" id="cpf"
          value="<?php echo esc_attr(get_the_author_meta('cpf', $user->ID)) ?>"
      />
      <span class="description"></span>
    </td>
  </tr>

  <tr>
    <th><label for="estado">Estado</label></th>
    <td>
      <select name="estado" id="estado">
        <option></option>
        <?php
          foreach ($estados as $key => $value) {
            if (esc_attr(get_the_author_meta('estado', $user->ID)) == $key)
              $selected = 'selected="selected"';
            else
              $selected = "";
            echo "<option value='$key' $selected>$value</option>";
          }
        ?>
      </select>
      <span class="description"></span>
    </td>
  </tr>

  <tr>
    <th><label for="cidade">Cidade</label></th>
    <td>
      <input
          type="text" name="cidade" id="cidade"
          value="<?php echo esc_attr(get_the_author_meta('cidade', $user->ID)) ?>"
      />
      <span class="description"></span>
    </td>
  </tr>

  <tr>
    <th><label for="segmento">Segmento ou setor de atuação</label></th>
    <td>
      <select name="segmento" id="segmento">
        <option></option>
        <?php
          foreach ($segmentos as $key => $value) {
            if (esc_attr(get_the_author_meta('segmento', $user->ID)) == $key)
              $selected = 'selected="selected"';
            else
              $selected = "";
            echo "<option value=\"$key\" $selected>$value</option>";
          }
        ?>
      </select>
      <span class="description"></span>
    </td>
  </tr>

  <tr>
    <th><label for="manifestacao">Tipo de manifestação</label></th>
    <td>
      <input
          type="text" name="manifestacao" id="manifestacao"
          value="<?php echo esc_attr(get_the_author_meta('manifestacao', $user->ID)) ?>"
      />
      <span class="description"></span>
    </td>
  </tr>

  <tr>
    <th><label for="instituicao">Instituição</label></th>
    <td>
      <input
          type="text" name="instituicao" id="instituicao"
          value="<?php echo esc_attr(get_the_author_meta('instituicao', $user->ID)) ?>"
      />
      <span class="description"></span>
    </td>
  </tr>
</table>
