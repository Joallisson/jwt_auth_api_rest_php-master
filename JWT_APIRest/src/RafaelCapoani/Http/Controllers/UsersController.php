<?php
	namespace Rafa\Http\Controllers; //Criando o namespace

	class UsersController { 
		public function getall() //Método para pegar os dados dos usuários
		{
			if (AuthController::checkAuth()) { //Se estiver autenticado
				return array(1 => 'Rafael', 2 => 'Bruna', 3 => 'Marcelo'); //Dados de exemplo, mas o correto mesmo era usar um banco de dados
			}
			
			throw new \Exception('Não autenticado'); //Senão estiver autenticado
		}
	}