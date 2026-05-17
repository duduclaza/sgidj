<?php

namespace App\Services;

class AIService
{
    public function assist(string $task, string $context): string
    {
        $apiKey = env('AI_API_KEY');
        $apiUrl = env('AI_API_URL');

        if ($apiKey && $apiUrl) {
            $response = $this->callApi((string) $apiUrl, (string) $apiKey, $task, $context);
            if ($response) {
                return $response;
            }
        }

        return $this->localSuggestion($task, $context);
    }

    private function callApi(string $url, string $key, string $task, string $context): ?string
    {
        $payload = json_encode([
            'task' => $task,
            'context' => $context,
            'language' => 'pt-BR',
        ], JSON_UNESCAPED_UNICODE);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\nAuthorization: Bearer {$key}\r\n",
                'content' => $payload,
                'timeout' => 20,
                'ignore_errors' => true,
            ],
        ];

        $result = @file_get_contents($url, false, stream_context_create($options));
        if (!$result) {
            return null;
        }

        $json = json_decode($result, true);
        return $json['text'] ?? $json['response'] ?? $json['message'] ?? null;
    }

    private function localSuggestion(string $task, string $context): string
    {
        $context = trim($context) ?: 'Sem contexto informado.';
        return match ($task) {
            'pdca' => "PLAN: delimite o problema, meta mensurável e responsável.\nDO: execute um piloto simples e registre evidências.\nCHECK: compare antes/depois com prazo, custo e qualidade.\nACT: padronize se funcionou ou ajuste a causa raiz.\n\nContexto considerado: {$context}",
            'swot' => "Forças: conhecimento interno e oportunidade de padronização.\nFraquezas: dependência de rotina manual e possível falta de dados.\nOportunidades: redução de retrabalho, ganho de produtividade e melhor rastreabilidade.\nAmeaças: resistência à mudança, prazo curto e impacto em áreas relacionadas.\n\nContexto considerado: {$context}",
            '5w2h' => "What: implementar a melhoria proposta.\nWhy: reduzir perdas, atrasos ou variabilidade do processo.\nWhere: área/processo afetado.\nWhen: definir prazo por etapa e marco de validação.\nWho: responsável da área com apoio do gestor.\nHow: mapear causa raiz, executar piloto, medir resultado e padronizar.\nHow much: estimar horas, materiais e ganho esperado.\n\nContexto considerado: {$context}",
            'causa_raiz' => "Sugestão: aplique 5 Porquês com evidências do processo, separando sintomas de causas controláveis. Valide a causa com dados antes de criar ações definitivas.\n\nContexto considerado: {$context}",
            default => "Sugestão estruturada: descreva o problema com impacto, frequência, evidência, causa provável, ação inicial, responsável, prazo e indicador de sucesso.\n\nContexto considerado: {$context}",
        };
    }
}
