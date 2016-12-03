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
        'unidadeMedida' => array(
            'label' => 'Unidade de Medida',
            'align' => 'right'
        ),
        'descricao' => array(
            'label' => 'Descrição',
            'align' => 'right'
        ),
    );
    public $css = "
            <style>
                table td {
                    border: 1px solid #c7c7c7;
                }
            
            </style>
            ";

    // use Dompdf\Dompdf;

    function __construct() {
        require_once CORE . 'helper/dompdf/autoload.inc.php';
        $this->dompdf = new \Dompdf\DomPdf();
    }

    private function verifyIgnore($key) {
        $ignore = array('idCliente', 'dataCriacao', 'observacao', 'descricao');

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

    public function export($rs) {
        $pdf = '<table width="100%" cellpadding="5px" cellspacing="0">';
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

        echo $pdf;
        exit();

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
