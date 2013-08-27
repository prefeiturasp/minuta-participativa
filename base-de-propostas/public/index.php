<?php
require '../app/bootstrap.php';

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

$app->get('/proposals/:id', function ($id) use ($app) {
    $proposals = Proposal::where('incorporado_minuta', '=', $id)->get();
    $app->render(200,$proposals->toArray());
});

$app->get('/minuta/format', function () use ($app) {

    $config = new LexerConfig();
    $config
        ->setDelimiter(";") // Customize delimiter. Default value is comma(,)
        ->setEscape("\\")    // Customize escape character. Default value is backslash(\)
        ->setToCharset('UTF-8'); // Customize target encoding. Default value is null, no converting.
    $lexer = new Lexer($config);
    $interpreter = new Interpreter();
    $paragraph=1;
    $final_content='';
    $interpreter->addObserver(function(array $text) use (&$paragraph, &$final_content) {
        /*if ( (!empty($text[0])) && (strlen($text[0])>2) && (strstr($text[0], 'TÍTULO'))) {
            $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$text[0]."[/commentable]<br>";
            $paragraph++;
        } else if ( (!empty($text[0])) && (strlen($text[0])>2) && (strstr($text[0], 'CAPÍTULO'))) {
            $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$text[0]."[/commentable]<br>";
            $paragraph++;
        }else if ( (!empty($text[0])) && (strlen($text[0])>2) && (strstr($text[0], 'Seção'))) {
            $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$text[0]."[/commentable]<br>";
            $paragraph++;
        }else if ( (!empty($text[0])) && (strlen($text[0])>2) && (strstr($text[0], 'Art. '))) {
            $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$text[0]."[/commentable]<br>";
            $paragraph++;
        }else if ( (!empty($text[0])) && (strlen($text[0])>2) && (strstr($text[0], '§ '))) {
            $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$text[0]."[/commentable]<br>";
            $paragraph++;
        }else if (strlen($text[0])>2) {
            $final_content .= $text[0]."<br>";
        }*/
        if ( (!empty($text[0])) && (strlen($text[0])>2) ) {
            $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$text[0]."[/commentable]<br>";
            $paragraph++;
        }
    });

    $lexer->parse(dirname(__FILE__).'/../app/data/minuta.csv', $interpreter);
echo $final_content;
//    $app->render(200,array($final_content));

});

$app->get('/minuta/format-html', function () use ($app) {

    $paragraph=1;
    $final_content='';
    //$file = file_get_contents(dirname(__FILE__).'/../app/data/minuta.html', FILE_USE_INCLUDE_PATH);
    $handle = @fopen(dirname(__FILE__).'/../app/data/minuta.html', "r");
    if ($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
            $buffer = preg_replace('/\s\s+/', ' ', $buffer);
            if ( (trim($buffer) === '<p align="JUSTIFY">') ) {
                $final_content .= '[commentable id="paragraph-'.$paragraph.'"]'.$buffer;
                $paragraph++;
            }else if ( (trim($buffer) === '</p>') ) {
                $final_content .= $buffer.'[/commentable]';
            }else {
                $final_content .= $buffer;
            }
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }


    //$lexer->parse(dirname(__FILE__).'/../app/data/minuta.csv', $interpreter);
    echo $final_content;
//    $app->render(200,array($final_content));

});

$app->get('/proposals/regenerate', function () {
    $schema = Capsule::schema();
    $schema->dropIfExists('proposals');
    $schema->create('proposals', function($table) {
        $table->increments('id');
        $table->text('code');
        $table->text('nome');
        $table->text('entidade');
        $table->text('email');
        $table->text('data_evento');
        $table->text('subprefeitura');
        $table->text('proponente');
        $table->text('objetivo');
        $table->text('tipo_instrumento');
        $table->text('investimento_prioritario');
        $table->text('instrumento_urbanisticos_gestao_ambiental');
        $table->text('instrumento_participacao_gestao');
        $table->text('instrumento_financiamento');
        $table->text('escala');
        $table->text('regiao');
        $table->text('votacao');
        $table->text('numero_votos');
        $table->text('proposta');
        $table->text('is_pertinente_pde');
        $table->text('is_analisado_revisao_pde');
        $table->text('como_presenca_pde');
        $table->text('responsavel_classificacao_gt');
        $table->text('desconhecido_1');
        $table->text('desconhecido_2');
        $table->text('desconhecido_3');
        $table->text('observacao');
        $table->text('palavra_chave_objetivo');
        $table->text('desconhecido_4');
        $table->text('observacao_1');
        $table->text('justificativa_nao_pertinente_pde');
        $table->text('justificativa');
        $table->text('responsavel');
        $table->text('is_incorporado_pde');
        $table->text('nao_incorporado_justificativa');
        $table->text('responsavel_encaminhamento');
        $table->text('incorporado_minuta');
        $table->text('id_minuta');
        $table->text('responsavel_localizacao_minuta');
        $table->text('observacao_incorporacao');
        $table->dateTime('created_at');
        $table->dateTime('updated_at');
    });

    $config = new LexerConfig();
    $config
        ->setDelimiter(";") // Customize delimiter. Default value is comma(,)
        ->setEscape("\\")    // Customize escape character. Default value is backslash(\)
        ->setToCharset('UTF-8'); // Customize target encoding. Default value is null, no converting.
    $lexer = new Lexer($config);

    $interpreter = new Interpreter();

    $interpreter->addObserver(function(array $columns) {
        $date = DateTime::createFromFormat("d/m/Y", $columns[4]);
        $columns[4] = $date->format('Y-m-d H:i:s');

        $data = array(
        'code'                                     =>$columns[0],
        'nome'                                     =>$columns[1],
        'entidade'                                 =>$columns[2],
        'email'                                    =>$columns[3],
        'data_evento'                              =>$columns[4],
        'subprefeitura'                            =>$columns[5],
        'proponente'                               =>$columns[6],
        'objetivo'                                 =>$columns[7],
        'tipo_instrumento'                         =>$columns[8],
        'investimento_prioritario'                 =>$columns[9],
        'instrumento_urbanisticos_gestao_ambiental'=>$columns[10],
        'instrumento_participacao_gestao'          =>$columns[11],
        'instrumento_financiamento'                =>$columns[12],
        'escala'                                   =>$columns[13],
        'regiao'                                   =>$columns[14],
        'votacao'                                  =>$columns[15],
        'numero_votos'                             =>$columns[16],
        'proposta'                                 =>$columns[17],
        'is_pertinente_pde'                        =>$columns[18],
        'is_analisado_revisao_pde'                 =>$columns[19],
        'como_presenca_pde'                        =>$columns[20],
        'responsavel_classificacao_gt'             =>$columns[21],
        'desconhecido_1'                           =>$columns[22],
        'desconhecido_2'                           =>$columns[23],
        'desconhecido_3'                           =>$columns[24],
        'observacao'                               =>$columns[25],
        'palavra_chave_objetivo'                   =>$columns[26],
        'desconhecido_4'                           =>$columns[27],
        'observacao_1'                             =>$columns[28],
        'justificativa_nao_pertinente_pde'         =>$columns[29],
        'justificativa'                            =>$columns[30],
        'responsavel'                              =>$columns[31],
        'is_incorporado_pde'                       =>$columns[32],
        'nao_incorporado_justificativa'            =>$columns[33],
        'responsavel_encaminhamento'               =>$columns[34],
        'incorporado_minuta'                       =>$columns[35],
        'id_minuta'                                =>$columns[36],
        'responsavel_localizacao_minuta'           =>$columns[37],
        'observacao_incorporacao'                 =>$columns[38]);

        $proposal = Proposal::create($data);
    });

    $lexer->parse(dirname(__FILE__).'/../app/data/proposals.csv', $interpreter);
});

$app->run();