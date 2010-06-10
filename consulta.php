<?php /* Template Name: Consulta */ ?>

<?php get_header(); ?>

<div id="content">
  <?php
    query_posts('category_name=Consulta');
    while (have_posts()) : the_post();
  ?>
  <div class="post">
    <span class="title">Consulta</span>
    <?php the_content(); ?>
  </div>
  <?php endwhile; ?>

  <div id="comments">
    <div id="navegaComments">
      <h3>Lista de páginas com comentários</h3>
      <ol>
        <li><a href="#" title="Página 1">1</a></li>
        <li><a href="#" title="Página 2">2</a></li>
        <li><a href="#" title="Página 3">3</a></li>
        <li><a href="#" title="Página 4">4</a></li>	
        <li><a href="#" title="Página 5">5</a></li>
      </ol>
      <form action="get">
        <label for="">Campo busca de comentários</label>
        <input type="text" name="busca nos comentários" />
      </form>
    </div><!--fim #navegaComments-->
    <div class="comment">
      <div class="infoUser">
        <span class="date">27/10/2009</span>
        <span class="user" >Severino Jose do Santos de Oliveira e Silva</span>
      </div>
      <p>
        Nunc porttitor tincidunt magna, at rutrum lorem molestie
        eu. Proin vitae magna elit. Etiam enim dui, vestibulum eu
        vestibulum eget, egestas nec justo. Aenean at mi arcu, nec
        egestas velit. Aenean vitae justo augue.
      </p>
    </div><!--fim .comment-->
    <div class="comment">
      <div class="infoUser">
        <span class="date">27/10/2009</span>
        <span class="user" >Severino Jose do Santos de Oliveira e Silva</span>
      </div>
      <p>
        Nunc porttitor tincidunt magna, at rutrum lorem molestie
        eu. Proin vitae magna elit. Etiam enim dui, vestibulum eu
        vestibulum eget, egestas nec justo. Aenean at mi arcu, nec
        egestas velit. Aenean vitae justo augue.
      </p>
    </div><!--fim .comment-->
  </div><!--fim #comments-->

</div>

<?php get_footer(); ?>
