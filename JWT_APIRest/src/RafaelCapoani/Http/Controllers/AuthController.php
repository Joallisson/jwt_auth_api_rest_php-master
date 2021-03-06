<?php
    namespace Rafa\Http\Controllers;

    class AuthController{
        public static function login(){

            if ($_POST['email'] == 'teste@gmail.com' && $_POST['password'] == '123') {
                //Application Key
                $key = '123456'; //É a senha do token

                //Header Token
                $header = [
                    'typ' => 'JWT', //Tipo de criptografia
                    'alg' => 'HS256' //Algoritmo usado
                ];

                //Payload - Content
                $payload = [ //Campos que devem ser autenticados
                    'name' => 'Joallisson', 
                    'email' => $_POST['email'],
                ];

                //JSON
                $header = json_encode($header); //Transforma o $header em JSON
                $payload = json_encode($payload); //Transforma o $payload em JSON

                //Base 64
                $header = base64_encode($header); //Criptografar os dados
                $payload = base64_encode($payload); //Criptografar os dados

                //Sign
                $sign = hash_hmac('sha256', $header . "." . $payload, $key, true); //Crinado uma assinatura no qual o 'sha256' é o tipo de criptografia, o $header e o $payload são concatenados, depois vem a chave de criptografia e em seguia a poarametro true
                $sign = base64_encode($sign); //Criptografar a assinatura

                //Token
                $token = $header . '.' . $payload . '.' . $sign; //Criando Token

                return $token; //Retornando o token
            }

            throw new \Exception('Não Autenticado'); //Caso der error exibe essa mensagem
            
        }

        public static function checkAuth() //Esse método vai basicamente ver se o usuário que está tentando acessar a api tem um token e se ele é válido
        {
            $http_header = apache_request_headers(); //Vai pegar todos os dados do cabeçalho chamado Authorization

            if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
                $bearer = explode(' ', $http_header['Authorization']); //Vai tirar o nome "bearer" do cabeçalho e deixar somente as informações do token

                //bearer[0] = 'bearer';
                //bearer[1] = 'token jwt';

                $token = explode('.', $bearer[1]); //Está pegando o token jwt e separando dividindo sempre que tiver um ponto
                $header = $token[0]; //Contém as informações de cabeçalho
                $payload = $token[1]; //Contém as informações que devem ser criptografadas
                $sign = $token[2]; //Contém as informações da assinatura que nada mais é que a concatenação do header e payload que abre com a chave $key

                //Conferindo a assinatura
                $valid = hash_hmac('sha256', $header . "." . $payload, '123456', true); //Crinado uma assinatura no qual o 'sha256' é o tipo de criptografia, o $header e o $payload são concatenados, depois vem a chave de criptografia e em seguia a poarametro true
                $valid = base64_encode($valid); //Criptografar a assinatura

                if ($sign === $valid) { //Está verificando se as duas assinatutas são iguais
                    return true; //Se for igual retorna true
                }
            }
            
            return false; //Senão for igual

        }

    }


























































/*
    namespace Rafa\Http\Controllers;
    
    class AuthController {
        public function login(){

            if ($_POST['email'] == 'teste@gmail.com' && $_POST['password'] == '123') {
                //Application Key
                $key = '123456';

                //Header Token
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                //Payload - Content
                $payload = [
                    'name' => 'Rafael Capoani',
                    'email' => $_POST['email'],
                ];

                //JSON
                $header = json_encode($header);
                $payload = json_encode($payload);

                //Base 64
                $header = $this->base64urlEncode($header);
                $payload = $this->base64urlEncode($payload);

                //Sign
                $sign = hash_hmac('sha256', $header . "." . $payload, $key, true);
                $sign = $this->base64urlEncode($sign);

                //Token
                $token = $header . '.' . $payload . '.' . $sign;

                return $token;
            }
            
            throw new \Exception('Não autenticado');

        }

        public static function checkAuth()
        {
            $http_header = apache_request_headers();

            if (isset($http_header['Authorization']) && $http_header['Authorization'] != null) {
                $bearer = explode (' ', $http_header['Authorization']);
                //$bearer[0] = 'bearer';
                //$bearer[1] = 'token jwt';

                $token = explode('.', $bearer[1]);
                $header = $token[0];
                $payload = $token[1];
                $sign = $token[2];

                //Conferir Assinatura
                $valid = hash_hmac('sha256', $header . "." . $payload, '123456', true);
                $valid = $this->base64urlEncode($valid);

                if ($sign === $valid) {
                    return true;
                }
            }

            return false;
        }
        /*
        /*Criei os dois métodos abaixo, pois o jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'*/

        /*
        private function base64urlEncode($data)
        {
            // First of all you should encode $data to Base64 string
            $b64 = base64_encode($data);

            // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
            if ($b64 === false) {
                return false;
            }

            // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
            $url = strtr($b64, '+/', '-_');

            // Remove padding character from the end of line and return the Base64URL result
            return rtrim($url, '=');
        }

                    
        /*private function base64url_decode($data, $strict = false)
        {
            // Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
            $b64 = strtr($data, '-_', '+/');

            // Decode Base64 string and return the original data
            return base64_decode($b64, $strict);
        }*/
    //}

