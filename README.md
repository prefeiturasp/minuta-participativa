# Minuta Participativa

![Screenshot do tema](https://raw.github.com/pmsp-smdu/minuta-participativa/master/wp-content/themes/minuta-gestaourbana/screenshot.png "design proposto")

>Esse projeto é baseado no plugin e tema criado pelo pessoal do [Xemele git](http://xemele.cultura.gov.br/git) - [O que é o Projeto Xemele ?](http://www2.cultura.gov.br/site/2009/01/20/xemele-2/)

>As principais funcionalidades do plugin e do tema continuam na versão atual, sofrendo mudanças para atender as necessidades propostas.

>Nosso foco nessa atualização foi o design e a experiência do usuário.

## Plugins

* Dialogue

## Tema

* Minuta Gestão Urbana

## Como usar

1. Clone este repositório;
2. rode o comando ```composer install```;
3. Copie o arquivo ```.env.example``` para ```.env``` e faça os ajustes para a sua configuração;
4. Acesse a url onde você configurou e siga com a instalação do wordpress normalmente;
5. Habilite o plugin dialogue e o tema Minuta Participativa;
6. Crie um post com o texto da minuta e o adicione na categoria "Consulta"(crie uma se não existir);
7. No post, você deve habilitar os dois checkbox que ficam abaixo do texto principal;
8. Os parágrafos que poderão ser comentados devem seguir o formato: ```[commentable id="paragraph-1"]Parágrafo completo a ser editado[/commentable]```;
7. Crie uma página em branco e selecione o template "Consulta" para a página;
8. No menu Configurações > Leitura, altere as opções para página inicial mostrar a recém criada página consulta;
9. Habilite os permalinks;

## Algumas referências

* http://edemocracia.camara.gov.br/web/marco-civil-da-internet/wikilegis#.Up56O41DtIA
* http://www2.cultura.gov.br/consultadireitoautoral/consulta/
