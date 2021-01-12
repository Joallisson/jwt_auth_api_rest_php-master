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
			$newUrl = explode('/', $this->request['url']); //Quebrando a requisiçaõ passada na URL para se transformar em um array e colocá-la em um novo array
			array_shift($newUrl); //Tirando os dados do primeiro ídice do vetor

			if (isset($newUrl[0])) { //Se tiver um valor na primeira posição do vetor
				$this->class = ucfirst($newUrl[0]).'Controller'; //Pega a primeira letra do valor da primeira posição do vetor e deixa ela em maiúcula atribui ela a um atributo
				array_shift($newUrl); //Tirando os dados do primeiro ídice do vetor

				if (isset($newUrl[0])) { //Se tiver um valor na primeira posição do vetor
					$this->method = $newUrl[0]; //Pega a primeira letra do valor da primeira posição do vetor e deixa ela em maiúcula atribui ela a um atributo
					array_shift($newUrl); //Tirando os dados do primeiro ídice do vetor

					if (isset($newUrl[0])) { //Se tiver um valor na primeira posição do vetor
						$this->params = $newUrl; //Colocar todos os arrays que sobraram como parâmetros dentro de um atributo
					}
				}
			}
		}

		public function run() //Método run()
		{
			if (class_exists('\Rafa\Http\Controllers\\'.$this->class) && method_exists('\Rafa\Http\Controllers\\'.$this->class, $this->method)) { //Verifica se a classe existe | Verifica se o método dentro da classe existe

				try {
					$controll = "\Rafa\Http\Controllers\\".$this->class; //Especificando que a variável $controll vai receber a classe passada na requisição
					$response = call_user_func_array(array(new $controll, $this->method), $this->params); //Especificando que a variável $response vai receber o retorno do método do objeto instanciado pela classe
					return json_encode(array('data' => $response, 'status' => 'sucess')); //
				} catch (\Exception $e) {
					return json_encode(array('data' => $e->getMessage(), 'status' => 'error')); //Retorna um erro
				}
				
			} else {

				return json_encode(array('data' => 'Operação Inválida', 'status' => 'error')); //Retorna um erro

			}
		}
	}