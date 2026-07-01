<?php
if (!function_exists('camposObrigatorios')) {
    function camposObrigatorios(array $campos, array $dados): array
    {
        $faltando = [];

        foreach ($campos as $campo) {
            $valor = $dados[$campo] ?? '';

            if (is_string($valor)) {
                $valor = trim($valor);
            } elseif (is_array($valor)) {
                $valor = '';
            }

            if ($valor === '' || $valor === null) {
                $faltando[] = $campo;
            }
        }

        return $faltando;
    }
}
