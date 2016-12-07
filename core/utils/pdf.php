<?php

namespace utils;

class pdf {

    public $dompdf;
    public $fields = array(
        // ANIMAL
        'id' => array(
            'label' => 'Código',
            'align' => 'right'
        ),
        'dataNascimento' => array(
            'label' => 'Data de Nascimento',
            'align' => 'center',
            'filter' => 'data'
        ),
        'custo' => array(
            'label' => 'Custo',
            'align' => 'right',
            'filter' => 'money'
        ),
        'statusVenda' => array(
            'label' => 'Status da Venda',
            'align' => 'right',
            'filter' => 'statusVenda'
        ),
        'observacao' => array(
            'label' => 'Observação',
            'align' => 'right'
        ),
        'dataCadastro' => array(
            'label' => 'Data de Cadastro',
            'align' => 'right',
            'filter' => 'data'
        ),
        'origem' => array(
            'label' => 'Origem',
            'align' => 'right'
        ),
        // PEDIDO
        'numeroDocumento' => array(
            'label' => 'N° do Documento',
            'align' => 'right'
        ),
        'valorTotal' => array(
            'label' => 'Valor Total',
            'align' => 'right',
            'filter' => 'money'
        ),
        'situacao' => array(
            'label' => 'Situação',
            'align' => 'right',
            'filter' => 'situation'
        ),
        'cliente' => array(
            'label' => 'Cliente',
            'align' => 'right'
        ),
        'dataEmissao' => array(
            'label' => 'Data de Emissão',
            'align' => 'center',
            'filter' => 'data'
        ),
        // NOME
        'nome' => array(
            'label' => 'Nome',
            'align' => 'right'
        ),
        'descricao' => array(
            'label' => 'Descrição',
            'align' => 'right'
        ),
        'dosagem' => array(
            'label' => 'Dosagem',
            'align' => 'right'
        ),
        'dataAplicacao' => array(
            'label' => 'Data de Aplicação',
            'align' => 'right'
        ),
        'vacina' => array(
            'label' => 'Vacina',
            'align' => 'right'
        ),
        'idVacina' => array(
            'label' => 'Código',
            'align' => 'right'
        ),
        'endereco' => array(
            'label' => 'Endereço',
            'align' => 'right'
        ),
        'celular' => array(
            'label' => 'Celular',
            'align' => 'right'
        )
    );
    public $title;
    public $css = "
            <style>
                table {
                    border: 1px solid #e7e7e7;
                }
                thead td{
                    font-weight: bold !important;
                }
                table tr:nth-child(odd){
                    background: #f2f2f2;
                }
                .cont{
                    background: #f2f2f2; 
                    width: 100%; 
                    padding: 20px 0;
                }
                .cont p{
                    margin: 0 !important; 
                    float: left;
                }
                .teste{
                    text-align: right; 
                    font-size: 27px;
                    font-weight: bold; 
                }
            </style>";

    // use Dompdf\Dompdf;

    function __construct() {
        require_once CORE . 'helper/dompdf/autoload.inc.php';
        $this->dompdf = new \Dompdf\DomPdf();
    }

    private function verifyIgnore($key) {
        $ignore = array('idCliente', 'dataCriacao', 'observacao', 'descricao', 'numeroCasa', 'telefone', 'bairro', 'cpf', 'idEstado', 'idCidade');

        if (!in_array($key, $ignore)) {
            return true;
        } else {
            return false;
        }
    }

    private function getHead($rs) {
        $pdf = $this->css;
        $pdf .= '<thead><tr>';
        foreach ($rs[0] as $key => $value) {
            if ($this->verifyIgnore($key)) {
                $pdf .= '<td align="' . $this->fields[$key]['align'] . '">' . $this->fields[$key]['label'] . '</td>';
            }
        }
        $pdf .= '</tr></thead>';

        return $pdf;
    }

    public function export($rs, $title) {
        $pdf = '<p>Gerado por: Agro Boi <br><span class="teste">' . $title . '<span></p>';
        
        $pdf .= '<table width="100%" cellpadding="5px" cellspacing="0">';
        
        $pdf .= $this->getHead($rs);

        $pdf .= '<tbody>';
        for ($x = 0; $x < count($rs); $x++) {
            $pdf .= '<tr>';
            foreach ($rs[$x] as $key => $value) {
                if ($this->verifyIgnore($key)) {
                    $field = $this->fields[$key];

                    // Chama função para aplicar filtro, semelhante ao call_user_func
                    if (isset($field['filter'])) {
                        $value = $this->$field['filter']($value);
                    }
                    $pdf .= '<td align="' . $field['align'] . '">' . $value . '</td>';
                }
            }
            $pdf .= '</tr>';
        }
        $pdf .= '</tbody></table>';

        date_default_timezone_set('America/Sao_Paulo');

        $pdf .= '<div style="width: 100%; background: #f2f2f2; position: absolute; bottom: 0">
                    <div style="text-align: right">' . date('d/m/Y H:i:s') . '</div>
                </div>';
//        echo $pdf;
//        exit();

        $this->dompdf->loadHtml($pdf);
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();
        $this->dompdf->stream();
    }

    /* -------------------------------------------------------------------------
      MÉTODOS/FILTROS PARA FORMATAR DADOS
     * ------------------------------------------------------------------------- */

    private function data($data) {
        if (empty($data)) {
            return '--';
        }
        return date('d/m/Y', strtotime($data));
    }

    private function money($money) {
        if (empty($money)) {
            return 'R$ 0,00';
        }
        return 'R$ ' . number_format($money, 2, ',', '.');
    }

    private function statusVenda($cod) {
        $status = array(
            0 => 'Aberto',
            1 => 'Vendido',
        );
        return $status[$cod];
    }

    private function situation($cod) {
        $situation = array(
            1 => 'Aberto',
            2 => 'Pago',
            3 => 'Cancelado',
            4 => 'Estornado'
        );
        return $situation[$cod];
    }

}
