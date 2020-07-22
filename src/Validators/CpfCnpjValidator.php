<?php
namespace WeDevBr\Bankly\Validators;
/**
 * ValidaCPFCNPJ valida e formata CPF e CNPJ
 *
 * Exemplo de uso:
 * $cpf_cnpj  = new ValidaCPFCNPJ('71569042000196');
 * $formatado = $cpf_cnpj->formata(); // 71.569.042/0001-96
 * $valida    = $cpf_cnpj->valida(); // True -> Válido
 *
 * @author Luiz Otávio Miranda <contato@tutsup.com>
 * @author Adeildo Amorim <adeildo@wedev.software>
 * @version  v1.3
 * @access   public
 * @see      http://www.tutsup.com/
 */

class CpfCnpjValidator
{
    private $document;
    /**
     * Configura o valor (Construtor)
     *
     * Remove caracteres inválidos do CPF ou CNPJ
     *
     * @param string $document
     */
    function __construct ($document = null) {
        // Deixa apenas números no valor
        $this->document = preg_replace( '/[^0-9]/', '', $document );

        // Garante que o valor é uma string
        $this->document = (string) $this->document;
    }

    /**
     * Verifica se é CPF ou CNPJ
     *
     * Se for CPF tem 11 caracteres, CNPJ tem 14
     *
     * @access protected
     * @return string
     * @throws \InvalidArgumentException;
     */
    protected function verifyCpfCnpj () {
        // Verifica CPF
        if ( strlen( $this->document ) === 11 ) {
            return 'CPF';
        }
        // Verifica CNPJ
        elseif ( strlen( $this->document ) === 14 ) {
            return 'CNPJ';
        }
        throw new \InvalidArgumentException('cpf_cnpj invalid');
    }

    /**
     * Multiplica dígitos vezes posições
     *
     * @access protected
     * @param  string    $digitos      Os digitos desejados
     * @param  int       $posicoes     A posição que vai iniciar a regressão
     * @param  int       $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int                     Os dígitos enviados concatenados com o último dígito
     */
    protected function calcPositionDigits($digitos, $posicoes = 10, $soma_digitos = 0 ) {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
            // Preenche a soma com o dígito vezes a posição
            $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );

            // Subtrai 1 da posição
            $posicoes--;

            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ( $posicoes < 2 ) {
                // Retorno a posição para 9
                $posicoes = 9;
            }
        }

        // Captura o resto da divisão entre $soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        $soma_digitos = $soma_digitos % 11;

        // Verifica se $soma_digitos é menor que 2
        if ( $soma_digitos < 2 ) {
            // $soma_digitos agora será zero
            $soma_digitos = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos $soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $soma_digitos = 11 - $soma_digitos;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        // Retorna
        return $digitos . $soma_digitos;
    }

    /**
     * Valida CPF
     *
     * @author                Luiz Otávio Miranda <contato@tutsup.com>
     * @access protected
     * @return bool           True para CPF correto - False para CPF incorreto
     */
    protected function validateCpf() {
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($this->document, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = $this->calcPositionDigits( $digitos );

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = $this->calcPositionDigits( $novo_cpf, 11 );

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        return $novo_cpf === $this->document;
    }

    /**
     * Valida CNPJ
     *
     * @author                  Luiz Otávio Miranda <contato@tutsup.com>
     * @access protected
     * @return bool             true para CNPJ correto
     */
    protected function validateCnpj ()
    {
        // O valor original
        $cnpj_original = $this->document;

        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr( $this->document, 0, 12 );

        // Faz o primeiro cálculo
        $primeiro_calculo = $this->calcPositionDigits( $primeiros_numeros_cnpj, 5 );

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = $this->calcPositionDigits( $primeiro_calculo, 6 );

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $segundo_calculo;

        // Verifica se o CNPJ gerado é idêntico ao enviado
        return $cnpj === $cnpj_original;
    }

    /**
     * Valida
     *
     * Valida o CPF ou CNPJ
     *
     * @access public
     * @return bool      True para válido, false para inválido
     */
    public function validate ()
    {
        // Valida CPF
        $validation = $this->verifyCpfCnpj();
        if ($validation === 'CPF' ) {
            // Retorna true para cpf válido
            return $this->validateCpf() && $this->verifySort(11);
        }
        // Valida CNPJ
        elseif ( $validation === 'CNPJ' ) {
            // Retorna true para CNPJ válido
            return $this->validateCnpj() && $this->verifySort(14);
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Formata CPF ou CNPJ
     *
     * @access public
     * @return string  CPF ou CNPJ formatado
     */
    public function format()
    {
        // O valor formatado
        $formatado = false;
        $validation = $this->verifyCpfCnpj();

        // Valida CPF
        if ( $validation === 'CPF' ) {
            // Verifica se o CPF é válido
            if ( $this->validateCpf() ) {
                // Formata o CPF ###.###.###-##
                $formatado  = substr( $this->document, 0, 3 ) . '.';
                $formatado .= substr( $this->document, 3, 3 ) . '.';
                $formatado .= substr( $this->document, 6, 3 ) . '-';
                $formatado .= substr( $this->document, 9, 2 ) . '';
            }
        }
        // Valida CNPJ
        elseif ( $validation === 'CNPJ' ) {
            // Verifica se o CPF é válido
            if ( $this->validateCnpj() ) {
                // Formata o CNPJ ##.###.###/####-##
                $formatado  = substr( $this->document,  0,  2 ) . '.';
                $formatado .= substr( $this->document,  2,  3 ) . '.';
                $formatado .= substr( $this->document,  5,  3 ) . '/';
                $formatado .= substr( $this->document,  8,  4 ) . '-';
                $formatado .= substr( $this->document, 12, 14 ) . '';
            }
        }

        // Retorna o valor
        return $formatado;
    }

    /**
     * Método para verifica sequencia de números
     * @param  integer $multiplos Quantos números devem ser verificados
     * @return boolean
     */
    public function verifySort($multiplos)
    {
        // cpf
        for($i=0; $i<10; $i++) {
            if (str_repeat($i, $multiplos) == $this->document) {
                return false;
            }
        }

        return true;
    }
}
