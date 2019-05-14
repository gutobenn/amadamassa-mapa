<?php

defined( 'ABSPATH' ) || exit;

class AmadaMassaMapa_AddressField extends NF_Fields_Textbox {
    protected $_name = 'amada_massa_endereco';
    protected $_section = 'common'; // section in backend
    protected $_type = 'text'; // field type
    protected $_templates = 'text'; // template; it's possible to create custom field templates

    public function __construct() {
        parent::__construct();

        $this->_nicename = __( 'Amada Massa - Endereço de Entrega', 'ninja-forms' );
    }
}
?>
