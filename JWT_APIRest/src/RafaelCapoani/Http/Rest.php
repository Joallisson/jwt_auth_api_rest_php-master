<?php
	namespace Rafa\Http; //Criando um nome para a classe

	class Rest{ // Criando uma classe
		
		private $request; //Criando requisição

		private $class; //Criando uma classe
		private $method; //Criando um método
		private $params = array(); //Criando um array

		public function __construct($req) { //Classe construtor recebendo a requição da url como parâmetro
			$this->request = $req; //Passando os dados da requisição para outra variável
			$this->load(); //Chamando  e executando função
		}

		public function load()//Criando método
		{
			$newUrl = explode('/', $this->request['url']); //Quebrando a requisiçaõ passada na URL para se transformar em um array e colocá-la em outra variável
			array_shift($newUrl); //Tirando os dados do primeiro ídice do vetor

			if (isset($newUrl[0])) { //Se tiver os dados que é o nome método no primeiro índice do vetor
				$this->class = ucfirst($newUrl[0]).'Controller'; //
				array_shift($newUrl);

				if (isset($newUrl[0])) {
					$this->method = $newUrl[0];
					array_shift($newUrl);

					if (isset($newUrl[0])) {
						$this->params = $newUrl;
					}
				}
			}
		}

		public function run()
		{
			if (class_exists('\Rafa\Http\Controllers\\'.$this->class) && method_exists('\Rafa\Http\Controllers\\'.$this->class, $this->method)) {

				try {
					$controll = "\Rafa\Http\Controllers\\".$this->class;
					$response = call_user_func_array(array(new $controll, $this->method), $this->params);
					return json_encode(array('data' => $response, 'status' => 'sucess'));
				} catch (\Exception $e) {
					return json_encode(array('data' => $e->getMessage(), 'status' => 'error'));
				}
				
			} else {

				return json_encode(array('data' => 'Operação Inválida', 'status' => 'error'));

			}
		}
	}