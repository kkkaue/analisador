<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalisadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Analisador/Index');
    }

    /**
     * Recebe o código fonte e retorna o resultado da análise.
     */
    public function analisar(Request $request)
    {
        //dd($request->input('codigo'));
        $codigo = $request->input('codigo');
        $arrayDeDadosAnalisados = $this->analisarCodigo($codigo);
        $resultado = $this->arrayParaString($arrayDeDadosAnalisados);
        return Inertia::render('Analisador/Index', [
            'resultado' => $resultado,
        ]);
    }

    /**
     * Analisa o código fonte e retorna o resultado.
     */
    private function analisarCodigo(string $codigo){
        // Expressões reguláres para diferentes tipos de tokens
        $operadores = '/(\+|\-|\*|\/|\%|\=|\>|\<|\!|\&|\|)/';
        $numeros = '/(\d+)/';
        $identificadores = '/([a-zA-Z_][a-zA-Z0-9_]*)/';
        $delimitadores = '/(\(|\)|\{|\}|\;|\,)/';
        $palavras_reservadas = '/(if|else|while|for|break|continue|return|main|int|float|double)/';
        $comentarios = '/(\/\/.*)/';
        $strings = '/(\".*\")/';
    
        // Array para armazenar os tokens
        $tokens = [];
    
        // Dividir o código em linhas
        $linhas = explode("\n", $codigo);
    
        // Processar cada linha
        foreach ($linhas as $numeroLinha => $linha) {
            // Dividir a linha em tokens
            $tokensLinha = preg_split('/\s+/', $linha);
    
            // Processar cada token
            foreach ($tokensLinha as $token) {
                if (preg_match($operadores, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: Operador, Valor: " . $token;
                } elseif (preg_match($numeros, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: Número, Valor: " . $token;
                } elseif (preg_match($palavras_reservadas, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: Palavra Reservada, Valor: " . $token;
                } elseif (preg_match($identificadores, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: Identificador, Valor: " . $token;
                } elseif (preg_match($delimitadores, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: Delimitador, Valor: " . $token;
                } elseif (preg_match($comentarios, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: Comentário, Valor: " . $token;
                } elseif (preg_match($strings, $token)) {
                    $tokens[] = "Linha: " . ($numeroLinha + 1) . ", Tipo do token: String, Valor: " . $token;
                }
            }
        }
    
        return $tokens;
    }

    private function arrayParaString(array $array){
        $string = implode("\n", $array);
        return nl2br($string);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
