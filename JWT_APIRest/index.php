<?php
	header('Content-Type: application/json; charset=UTF-8'); //Está dizendo para o navegador que em algum momento haverá um retorno em JSON

	require_once 'vendor/autoload.php'; //Está incluindo um arquivo php
	

	use Rafa\Http\Rest; //Está importando uma Classe chamada Rest 

	if (isset($_REQUEST) && !empty($_REQUEST)) { //Se existir uma Requisição e se a essa requisição retornar uma valor que não seja falso
		$rest = new Rest($_REQUEST); //Criando um objeto e enviando como parâmetro a requisição para o método construtor
		echo $rest->run(); //Executando o método
	}