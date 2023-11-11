<?php

namespace App\Http\Middleware;

class HeaderApiMiddleware
{
  /**
   * Função/Método responsável por Definir um cabeçalho de resposta para 
   * permitir CORS (Cross-Origin Resource Sharing), 
   * um cabeçalho de resposta JSON, 
   * permitir apenas os métodos GET e POST de origens específicas e 
   * permitir cabeçalhos personalizados.
   * 
   * @param 
   * @return header
   */
  public function handle()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      header("Access-Control-Allow-Origin: *"); // Insira o domínio que a API receberá as solicitações
      // header("Access-Control-Allow-Origin: https://nsvdev.com.br"); // Insira o domínio que a API receberá as solicitações
      header("Access-Control-Allow-Methods: GET, POST");
      header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization");
      header("Access-Control-Allow-Credentials: true");
      header("Access-Control-Max-Age: 3600");
      header("Access-Control-Expose-Headers: X-Custom-Header");
      exit;
    }

    header("Access-Control-Allow-Origin: *"); // Insira o domínio que a API receberá as solicitações
    // header("Access-Control-Allow-Origin: https://nsvdev.com.br"); // Insira o domínio que a API receberá as solicitações
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization");
  }  
}
