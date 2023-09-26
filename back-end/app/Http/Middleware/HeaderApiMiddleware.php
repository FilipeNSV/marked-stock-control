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
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Methods: GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Authorization");
  }
}
