<?php

namespace Spider;

use GuzzleHttp\Client;

class SintegraCrawler
{
    private $baseURL = 'http://www.sintegra.fazenda.pr.gov.br/sintegra/';

    public function getInscricoesEstaduais($cnpj)
    {
        $url = $this->baseURL . '?cnpj=' . $cnpj;

        $response = $this->fetchData($url);

        if ($this->isCnpjIENotFound($response)) {
            return ['error' => 'CNPJ/IE não encontrado'];
        }

        $inscricoesEstaduais = $this->extractInscricoesEstaduais($response);

        return $inscricoesEstaduais;
    }

    private function fetchData($url)
    {
        $client = new Client();
        $response = $client->get($url);
        $data = json_decode($response->getBody(), true);

        return $data;
    }

    private function isCnpjIENotFound($data)
    {
        return empty($data['inscricoes_estaduais']);
    }

    private function extractInscricoesEstaduais($data)
    {
        return $data['inscricoes_estaduais'] ?? [];
    }
}
