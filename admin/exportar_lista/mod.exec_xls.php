<?php
    /*
     *salva id da agenda em uma variavel
     */
    $agd_id = (int)trim($_GET['agd_id']);

    /*
     *verifica se id da agenda é número
     */
    if(!is_int($agd_id))
      die('ERRO! Abortando...');




  include_once '../_inc/global.php';
  include_once '../_inc/db.php';
  include_once '../_inc/global_function.php';



      function cleanData(&$str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        return $str;
      }

      # file name for download
      $filename = "vips_nao_cadastrados_".date('Ymd').".xls";

      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Content-Type: application/vnd.ms-excel");


       $flag=false;
       $sql = "SELECT
                       vip_nome Nome,
                       vip_email Email,
                       DATE_FORMAT(vip_timestamp,'%d/%m/%Y') Cadastro

                       FROM ".TABLE_PREFIX."_vip
                        WHERE vip_email IS NOT NULL
                        AND NOT EXISTS(SELECT null FROM ".TABLE_PREFIX."_cadastro WHERE cad_email=vip_email)
                        AND vip_email<>'' AND vip_email<>'-'
                        GROUP BY vip_email
                        ORDER BY vip_timestamp ASC";


      if($qry = $conn->prepare($sql)) {

        $qry->execute();
        $qry->store_result();
        $qry->bind_result($nome, $email, $cadastro);
        $num = $qry->num_rows;

          $arrNome = $arrEmail = $arrCadastro = '';
          while($qry->fetch()){

            $arrNome .= $nome.',';
            $arrEmail .= $email.',';
            $arrCadastro .= $cadastro.',';

          }

        $qry->close();


        /*
         *cabeçalho dos dados
         */
        $row = array('nome'=>explode(',',substr($arrNome,0,-1)), 
                      'email'=>explode(',',substr($arrEmail,0,-1)), 
                      'cadastro'=>explode(',',substr($arrCadastro,0,-1)));




          if($num>0) {

              if(!$flag) {
                # display field/column names as first row
                echo $num." emails"."\n";
                echo "Gerado em ".date('d/m/Y H:i')."\n\n";
                echo "Nome\t";
                echo "E-mail\t";
                echo "Data de Cadastro na Lista\t";
                echo "\n";
                $flag = true;
              }



              for($i=0;$i<count($row['nome']);$i++){

                echo cleanData($row['nome'][$i])."\t";
                echo cleanData($row['email'][$i])."\t";
                echo cleanData($row['cadastro'][$i])."\t";
                echo "\n";

              }

          }



      }

