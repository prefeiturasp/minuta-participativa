<?php include('globals.php'); ?>
<h3>Direito autoral</h3>
<table class="form-table">
  <tr>
    <th><label for="cpf">CPF</label></th>
    <td>
      <strong>
        <?php echo esc_attr(get_the_author_meta('cpf', $user->ID)) ?>
      </strong>
    </td>
  </tr>

  <tr>
    <th><label for="estado">Estado</label></th>
    <td>
      <strong>
        <?php echo $estados[esc_attr(get_the_author_meta('estado', $user->ID))] ?>
      </strong>
    </td>
  </tr>

  <tr>
    <th><label for="cidade">Cidade</label></th>
    <td>
      <strong>
        <?php echo esc_attr(get_the_author_meta('cidade', $user->ID)) ?>
      </strong>
    </td>
  </tr>

  <tr>
    <th><label for="segmento">Segmento ou setor de atuação</label></th>
    <td>
      <strong>
        <?php echo esc_attr(get_the_author_meta('segmento', $user->ID)) ?>
      </strong>
    </td>
  </tr>

  <tr>
    <th><label for="manifestacao">Tipo de manifestação</label></th>
    <td>
      <strong>
        <?php echo esc_attr(get_the_author_meta('manifestacao', $user->ID)) ?>
      </strong>
    </td>
  </tr>

  <tr>
    <th><label for="instituicao">Instituição</label></th>
    <td>
      <strong>
        <?php echo esc_attr(get_the_author_meta('instituicao', $user->ID)) ?>
      </strong>
    </td>
  </tr>
</table>
