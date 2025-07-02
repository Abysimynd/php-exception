<?php

declare(strict_types = 1);

namespace KeilielOliveira\Exception;

class MessageTemplate {
    /**
     * Mensagem sendo gerada.
     */
    private string $message;

    /**
     * Dados usados na geração da mensagem.
     *
     * @var array<int|string, mixed>
     */
    private array $data;

    /**
     * Constroi uma mensagem de exceção com base no template passado.
     */
    public function __construct( string $message ) {
        $this->message = $message;

        $this->hasData();
        $data = Exception::get( Config::TEMPLATE_DATA_KEY->value );

        /** @var array<int|string, mixed> $data */
        $this->data = $data;
    }

    /**
     * Constroi e retorna a mensagem.
     *
     * @throws Exception
     */
    public function build(): string {
        foreach ( $this->data as $key => $value ) {
            /** @var int|string $key */
            $this->isValidParam( $key, $value );

            /** @var string $value */
            $pattern = "/@{$key}/";
            $message = preg_replace( $pattern, $value, $this->message );

            if ( !is_string( $message ) ) {
                throw new Exception(
                    'Ocorreu um erro inesperado ao gerar a mensagem.',
                    ExceptionCodes::UNKNOW_MESSAGE_TEMPLATE_ERROR->value
                );
            }

            $this->message = $message;
        }

        $this->isValidMessage();

        return $this->message;
    }

    /**
     * Verifica se foram passados parametros para serem usados na construção do template.
     *
     * @throws Exception
     */
    private function hasData(): void {
        try {
            Exception::get( Config::TEMPLATE_DATA_KEY->value );
        } catch ( Exception $e ) {
            throw new Exception(
                'Não foi possível recuperar os dados usados na geração da mensagem.',
                ExceptionCodes::TEMPLATE_DATA_NOT_DEFINED->value,
                $e
            );
        }
    }

    /**
     * Verifica se não há nenhum parametro faltando na mensagem.
     *
     * @throws Exception
     */
    private function isValidMessage(): void {
        $pattern = Config::TEMPLATE_DATA_PATTERN->value;

        if ( preg_match( $pattern, $this->message, $matches ) ) {
            $data = str_replace( '@', '', $matches[0] );

            throw new Exception(
                "Dado '{$data}' para geração da mensagem faltando.",
                ExceptionCodes::MISSING_TEMPLATE_DATA->value
            );
        }
    }

    /**
     * Verifica se o parametro sendo usado na mensagem é uma string.
     *
     * @throws Exception
     */
    private function isValidParam( int|string $key, mixed $value ): void {
        if ( is_array( $value ) ) {
            throw new Exception(
                "Valores usados para gerar uma mensagem devem ser dos tipos (string, int, float), array foi recebido no indice '{$key}'.",
                ExceptionCodes::INVALID_REPLACEMENT_VALUE->value
            );
        }
    }
}
