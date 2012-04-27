<?php

if (isset($_POST['act']))
  include_once 'mod.exec.php';
else if (isset($_GET['insert']) XOR isset($_GET['update']))
	include_once 'form.php';
else if (isset($_GET['notas']))
	include_once 'form.notas.php';
else if (isset($_GET['lancar-notas']))
	include_once 'ajax.form.notas.php';
elseif (isset($_GET['delete']))
	include_once 'mod.delete.php';
elseif (isset($_GET['delete_galeria']))
	include_once 'mod.galeria.delete.php';
elseif (isset($_GET['status']))
	include_once 'mod.status.php';
elseif (isset($_GET['alterasenha']))
	include_once 'alterasenha.form.php';
elseif (isset($_GET['desempenho']))
	include_once 'form/desempenho.php';
else 
	include_once 'list.php';
