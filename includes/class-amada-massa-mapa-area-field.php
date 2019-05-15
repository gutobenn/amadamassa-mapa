<?php

defined( 'ABSPATH' ) || exit;

class AmadaMassaMapa_AreaField extends NF_Abstracts_Input{
    protected $_name = 'amada_massa_area';
    protected $_nicename = 'Amada Massa - Área de Entrega';
    protected $_section = 'misc';
    protected $_type = 'textbox';
    protected $_templates = 'amada_massa_area';
    //protected $_wrap_template = 'wrap';
    protected $_settings_only = array(
        'label', 'key', 'required'
    );

    public function __construct(){
        parent::__construct();

        $this->_nicename = __( 'Amada Massa - Área de Entrega', 'ninja-forms' );
    }

}

?>
