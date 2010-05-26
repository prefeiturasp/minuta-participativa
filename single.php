<?php get_header(); ?>

      <div id="content">
        <div class="post">
          <span class="title">Blog</span>
          <ul class="navegaPost">
            <li><a href="#" title="post anterior">Anterior</a></li>
            <li><a href="#" title="proximo post">Próximo</a></li>
          </ul>
          <h2>Lorem Ipsum dolor color</h2>
          <p class="date">01/05/2010</p>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing
          elit. Sed nulla tortor, iaculis eu dignissim pellentesque,
          malesuada vel est. In tortor dolor, consectetur consequat
          blandit sit amet, condimentum non odio. In adipiscing urna
          sit amet odio volutpat id tincidunt eros
          sodales. Pellentesque non velit libero, id eleifend
          nisl. Vestibulum ante ipsum primis in faucibus orci luctus
          et ultrices posuere cubilia Curae; In in semper libero.</p>
          <p>Integer bibendum mi id velit lobortis id venenatis elit
          hendrerit. Pellentesque ut sapien ipsum. Praesent eu
          vulputate nibh. Vestibulum ante ipsum primis in faucibus
          orci luctus et ultrices posuere cubilia Curae; Etiam eu diam
          sed risus cursus mattis. Pellentesque cursus nibh eu nisi
          laoreet ultricies. Integer cursus dignissim risus et
          varius. Nunc lorem odio, volutpat ut commodo non, posuere in
          enim. Class aptent taciti sociosqu ad litora torquent per
          conubia nostra, per inceptos himenaeos. Aliquam malesuada
          ante id orci sodales adipiscing. Nunc vitae risus vitae
          turpis eleifend vestibulum ut vel ligula. Nulla eget commodo
          ante. Donec a pretium arcu.</p>
          <p>Vestibulum malesuada neque a felis tincidunt facilisis
          elementum ipsum tempor. Aenean eget elit dui, eget dictum
          turpis. Nam dignissim tempor ornare. Phasellus sodales magna
          sed quam porta vitae congue urna viverra. Suspendisse vitae
          lorem elit, at sollicitudin lectus. Etiam venenatis
          malesuada aliquam. Nunc nec nunc quis tellus pellentesque
          malesuada placerat eu est. Etiam sed dui vel diam porta
          porttitor. Etiam at orci eget dui iaculis condimentum. In
          convallis tincidunt augue at dapibus.</p>
          <p>Morbi tristique mattis odio non feugiat. Fusce ultricies
          justo lectus. Ut ac tristique neque. Lorem ipsum dolor sit
          amet, consectetur adipiscing elit. Quisque tincidunt ante
          vel magna fringilla eu consectetur arcu pulvinar. Nulla id
          erat sem. Cras bibendum pellentesque nunc, eget rutrum arcu
          viverra vitae. Donec egestas pulvinar mi at
          porttitor. Nullam lectus ante, elementum a iaculis nec,
          rhoncus non eros. Quisque consequat congue mauris vel
          porta.</p>
          <p>Sed eleifend faucibus ligula, et vestibulum urna tempus
          non. Maecenas ac eros turpis, ac tincidunt neque. Sed a nunc
          ut nibh aliquet dapibus. Etiam egestas eros ac eros egestas
          eu interdum odio aliquet. Phasellus eu eleifend mi. Donec mi
          tortor, rhoncus at commodo eget, tincidunt et massa. Sed
          molestie neque a lorem tempor semper. Cum sociis natoque
          penatibus et magnis dis parturient montes, nascetur
          ridiculus mus. Sed egestas bibendum ipsum id
          dignissim. Praesent tortor odio, rhoncus et tristique sed,
          sollicitudin in metus. Morbi scelerisque tincidunt lorem nec
          vestibulum. Donec dolor tortor, pretium non congue non,
          tempus ut tortor. Mauris nec est magna, quis egestas
          tellus.</p>
          <a href="#" title="Exibir comentários" class="lerComentarios">ler comentários</a>
        </div><!--fim .post-->
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
              <img src="images/avatar.png" alt="Imagem de visualização do usuário" />
              <span class="date">27/10/2009</span>
              <span class="user" >Severino Jose do Santos de Oliveira e Silva</span>
            </div>
            <p>Nunc porttitor tincidunt magna, at rutrum lorem
            molestie eu. Proin vitae magna elit. Etiam enim dui,
            vestibulum eu vestibulum eget, egestas nec justo. Aenean
            at mi arcu, nec egestas velit. Aenean vitae justo
            augue.</p>
          </div><!--fim .comment-->
        </div><!--fim #comments-->
      </div><!--fim #content-->

<?php get_footer(); ?>
