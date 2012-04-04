<?php

  include_once '../_inc/global.php';
  include_once '../_inc/db.php';
  include_once '../_inc/global_function.php';




   /*
    *pega o nome da festa e verifica se ela existe
    */
   $sql_cad = "SELECT cad_nome,cad_registro, DATE_FORMAT(cad_timestamp,'%d/%m/%Y') cad_data, cad_status, cad_telefone_residencial, cad_telefone_celular, cad_departamento, cad_nome_filho, cad_idade_filho, cad_nome_esposa
               FROM ".TABLE_PREFIX."_cadastro 
               WHERE cad_nome IS NOT NULL ORDER BY cad_nome";
   if ($qry_cad = $conn->prepare($sql_cad)){

     $qry_cad->execute();
     $qry_cad->store_result();
     $qry_cad->bind_result($cad_nome, $cad_registro, $cad_cadastro, $cad_status, $cad_telefone_residencial, $cad_telefone_celular, $cad_departamento, $cad_nome_filho, $cad_idade_filho, $cad_nome_esposa);
     $num = $qry_cad->num_rows;

   } else echo $qry_cad->error;




   if($num==0)
     die('Nenhum cadastro');

   else {


      function cleanData(&$str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        return html_entity_decode($str);
      }

      # file name for download
      $filename = "cadastros_".date('Ymd').".xls";

      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Content-Type: application/vnd.ms-excel");


       $flag=false;
          $arrNome = $arrRegistro = $arrData = $arrStatus = $arrTelefoneResidencial = $arrTelefoneCelular = $arrDepartamento = $arrNomeFilho = $arrIdadeFilho = $arrCadastro = $arrNomeEsposa = '';
          while($qry_cad->fetch()){

            $arrNome .= $cad_nome.',';
            $arrRegistro .= $cad_registro.',';
            $arrTelefoneResidencial .= $cad_telefone_residencial.',';
            $arrTelefoneCelular .= $cad_telefone_celular.',';
            $arrDepartamento .= $cad_departamento.',';
            $arrNomeFilho .= $cad_nome_filho.',';
            $arrIdadeFilho .= $cad_idade_filho.',';
            $arrNomeEsposa .= $cad_nome_esposa.',';
            $arrCadastro .= $cad_cadastro.',';
            $arrStatus .= ($cad_status==1)?'ativo':'inativo';
            $arrStatus .= ',';

          }

        $qry_cad->close();


        /*
         *cabeçalho dos dados
         */
        $row = array('nome'=>explode(',',substr($arrNome,0,-1)),
                      'registro'=>explode(',',substr($arrRegistro,0,-1)),
                      'telefoneResidencial'=>explode(',',substr($arrTelefoneResidencial,0,-1)),
                      'telefoneCelular'=>explode(',',substr($arrTelefoneCelular,0,-1)),
                      'departamento'=>explode(',',substr($arrDepartamento,0,-1)),
                      'nomeFilho'=>explode(',',substr($arrNomeFilho,0,-1)),
                      'idadeFilho'=>explode(',',substr($arrIdadeFilho,0,-1)),
                      'nomeEsposa'=>explode(',',substr($arrNomeEsposa,0,-1)),
                      'status'=>explode(',',substr($arrStatus,0,-1)),
                      'cadastro'=>explode(',',substr($arrCadastro,0,-1)),
                    );




          if($num>0) {

              if(!$flag) {
                # display field/column names as first row
                echo "Lista de Cadastros\n";
                echo "Gerado em ".date('d/m/Y H:i')."\n\n";
                echo "Registro\t";
                echo "Nome\t";
                echo "Departamento\t";
                echo "Telefone Residencial\t";
                echo "Telefone Celular\t";
                echo "Nome Filho\t";
                echo "Idade Filho\t";
                echo "Nome Esposa\t";
                echo "Status\t";
                echo "Data de Cadastro na Lista\t";
                echo "\n";
                $flag = true;
              }



              for($i=0;$i<count($row['nome']);$i++){

                echo cleanData($row['registro'][$i])."\t";
                echo cleanData($row['nome'][$i])."\t";
                echo cleanData($row['departamento'][$i])."\t";
                echo cleanData($row['telefoneResidencial'][$i])."\t";
                echo cleanData($row['telefoneCelular'][$i])."\t";
                echo cleanData($row['nomeFilho'][$i])."\t";
                echo cleanData($row['idadeFilho'][$i])."\t";
                echo cleanData($row['nomeEsposa'][$i])."\t";
                echo cleanData($row['status'][$i])."\t";
                echo cleanData($row['cadastro'][$i])."\t";
                echo "\n";

              }

          }



   }
